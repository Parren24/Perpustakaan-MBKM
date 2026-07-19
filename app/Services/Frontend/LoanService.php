<?php

namespace App\Services\Frontend;

use App\Services\Frontend\SiteIdentityService;
use App\Models\Biblio\Item;
use App\Models\Biblio\Biblio;
use App\Models\Biblio\Loan;
use App\Models\Biblio\LoanRules;
use App\Models\Biblio\Fine;
use App\Models\CartLoan;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Models\Penalties;
use Carbon\Carbon;
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
// use Mike42\Escpos\Printer;


class LoanService
{
    public static function loanRules($memberId)
    {
        $loanRules = LoanRules::getLoanRules($memberId);

        $loanLimit = $loanRules->loan_limit ?? 0;
        $loanPeriode = $loanRules->loan_periode ?? 0;
        $reborrowLimit = $loanRules->reborrow_limit ?? 0;
        $fineEachDay = $loanRules->fine_each_day ?? 0;

        return [$loanLimit, $loanPeriode, $reborrowLimit, $fineEachDay];
    }

    public static function getActiveRule($memberId, $collTypeId)
    {
        $rule = LoanRules::getActiveRule($memberId, $collTypeId);

        return $rule;
    }

    public static function storeLoanTransaction($request)
    {
        DB::beginTransaction();

        try {
            // Validasi session member
            $memberData = Session::get('biblio_user');
            //App

            checkMemberUserValid($memberData);

            //App
            checkMemberNomorIndukValid($memberData);

            // Ambil cart dan validasi
            $cart = CartLoan::getMemberIdInCart($memberData['member_id']);

            if (!$cart || empty($cart->list_item)) {
                return errResponse(400, 'Keranjang peminjaman kosong.');
            }

            $cartItems = $cart->list_item;

            // if (empty($cartItems)) {
            //     return errResponse(400, 'Keranjang peminjaman kosong atau data tidak valid.');
            // }

            // Validasi ulang ketersediaan semua item sebelum memproses
            $unavailableItems = [];
            // $modulItems = [];
            foreach ($cartItems as $cartItem) {
                $item = Item::where('item_code', $cartItem['item_code'])->first();
                if (!$item || !$item->is_available || $item->item_status_id != '0') {
                    $unavailableItems[] = $cartItem['item_code'];
                }

                // if ($item && $item->coll_type_id == 13) {
                //     $modulItems[] = $cartItem['item_code'];
                // }
            }

            // if (!empty($modulItems)) {
            //     DB::rollBack();
            //     return errResponse(400, 'Anda tidak dapat meminjam modul (Kode: ' . implode(', ', $modulItems) . '). Silakan hapus dari keranjang.');
            // }

            if ($unavailableItems) {
                DB::rollBack();
                return errResponse(400, 'Beberapa buku tidak tersedia: ' . implode(', ', $unavailableItems));
            }

            // Cek kembali batas maksimal peminjaman aktif
            $activeLoanCount = Loan::activeLoanCount($memberData['member_id']);
            $loanRules = self::loanRules($memberData['member_id']);
            $loanLimit = $loanRules[0];
            $loanPeriod = $loanRules[1];
            $loanDueDate = Carbon::now()->addDays($loanPeriod)->format('Y-m-d'); // Hitung due date berdasarkan loan periode
            //tambah
            $normalCartItemsCount = 0;
            foreach ($cartItems as $cartItem) {
                $item = Item::where('item_code', $cartItem['item_code'])->join('mst_coll_type', 'item.coll_type_id', '=', 'mst_coll_type.coll_type_id')
                ->select('item.*', 'mst_coll_type.is_limit_true')->first();
                if ($item && $item->is_limit_true == 1) {
                    $normalCartItemsCount++;
                }
            }
            //tambah
            // Hitung peminjaman aktif "biasa" yang belum dikembalikan (Abaikan modul)
            $activeNormalLoanCount = Loan::join('item', 'loan.item_code', '=', 'item.item_code')
                ->join('mst_coll_type', 'item.coll_type_id', '=', 'mst_coll_type.coll_type_id')
                ->where('loan.member_id', $memberData['member_id'])
                ->where('loan.is_return', 0)
                ->where('mst_coll_type.is_limit_true', 1)
                ->count();
            //tambah
            // Validasi Limit HANYA untuk buku reguler
            if ($activeNormalLoanCount + $normalCartItemsCount > $loanLimit) {
                DB::rollBack();
                return errResponse(400, 'Total peminjaman buku reguler akan melebihi batas maksimal. Limit modul tidak dibatasi.');
            }

            $loanIds = [];
            $processedItems = [];
            $printReceipts = [];

            foreach ($cartItems as $cartItem) {
                $item = Item::where('item_code', $cartItem['item_code'])->lockForUpdate()->first();

                // Ambil Rule untuk item ini
                $rule = self::getActiveRule($memberData['member_id'], $item->coll_type_id);
                if (!$rule) {
                    DB::rollBack();
                    return errResponse(400, "Aturan peminjaman untuk item {$cartItem['item_code']} belum dikonfigurasi.");
                }

                $loanPeriod = $rule->loan_periode ?? 14; // Fallback jika null
                $itemDueDate = Carbon::now()->addDays($loanPeriod)->format('Y-m-d');

                // Update Status Item
                $item->item_status_id = '1';
                $item->save();

                // Insert Loan
                $loanId = Loan::InsertDataTableLoan($cartItem['item_code'], $memberData['member_id'], $itemDueDate);

                $loanIds[] = $loanId;
                $processedItems[] = [
                    'loan_id' => $loanId,
                    'item_code' => $cartItem['item_code'],
                    'title' => $cartItem['title'] ?? 'N/A',
                    'due_date' => $itemDueDate,
                ];
            }

            $cart->forceDelete();
            Session::forget('biblio_user');

            DB::commit();

            return successResponse([
                'loan_ids' => $loanIds,
                'borrowed_items' => $processedItems,
                'total_borrowed' => count($processedItems),
                'return_reminder' => 'Jangan lupa mengembalikan buku tepat waktu untuk menghindari denda.'
            ], 'Transaksi peminjaman berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in storeLoanTransaction: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan saat memproses peminjaman. Silakan coba lagi.');
        }
    }



    public static function returnLoanItem($loanId, $memberData)
    {
        DB::beginTransaction();

        try {
            checkMemberUserValid($memberData);
            $loan = Loan::where('loan_id', $loanId)
                ->where('member_id', $memberData['member_id'])
                ->where('is_return', 0)
                ->first();

            if (!$loan) {
                return errResponse(404, 'Data peminjaman tidak ditemukan atau sudah dikembalikan.');
            }

            if (Carbon::now()->gt(Carbon::parse($loan->due_date))) {
                $penaltyAmount = self::calculatePenaltyForLoan($loan, $memberData['member_id']);
                $countOverdue = Carbon::parse($loan->due_date)->diffInDays(Carbon::now());

                if ($penaltyAmount > 0) {
                    $existingPenalty = Fine::where('loan_id', $loanId)
                        ->where('member_id', $memberData['member_id'])
                        ->first();

                    if (!$existingPenalty) {
                        Fine::insertFine(
                            $memberData['member_id'],
                            $penaltyAmount,
                            'Overdue fines for item ' . $loan->item_code,
                            $loanId,
                            $countOverdue
                        );
                    } else {
                        $existingPenalty->debet = $penaltyAmount;
                        $existingPenalty->count_overdue = $countOverdue;
                        $existingPenalty->fines_date = now();
                        $existingPenalty->save();
                    }

                    DB::commit();
                    return errResponse(400, 'Buku terlambat dikembalikan. Harap bayar denda sebesar Rp ' . number_format($penaltyAmount, 0, ',', '.') . ' kepada pustakawan agar peminjaman dapat diselesaikan.');
                }
            }

            $item = Item::where('item_code', $loan->item_code)->first();
            if ($item) {
                $item->item_status_id = '0';
                $item->save();
            }

            $loan->return_date = Carbon::now();
            $loan->is_return = 1;
            $loan->last_update = Carbon::now();
            $loan->save();

            DB::commit();

            return successResponse(null, 'Buku berhasil dikembalikan. Terima kasih!');
        }
        catch (\App\Exceptions\KiosSessionExpiredException $e) {
            DB::rollBack();
            throw $e; // lempar lagi ke controller, biar controller yang convert ke response (pola yang sama seperti getLoan/completeLoan)
        }  
        
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in returnLoanItem: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan saat memproses pengembalian. Silakan coba lagi.');
        }
    }

    // public static function LoanPenalty()
    // {
    //     DB::beginTransaction();

    //     try {
    //         $memberData = Session::get('biblio_user');
    //         checkMemberUserValid($memberData);

    //         $loans = Loan::where('member_id', $memberData['nomor_induk'])
    //             ->where('is_return', 0)
    //             ->where('due_date', '<', Carbon::now())
    //             ->get();

    //         $penalties = [];

    //         foreach ($loans as $loan) {
    //             $penaltyAmount = self::calculatePenaltyForLoan($loan);

    //             if ($penaltyAmount > 0) {
    //                 $penalties[] = [
    //                     'loan_id' => $loan->loan_id,
    //                     'item_code' => $loan->item_code,
    //                     'loan_date' => $loan->loan_date->format('d/m/Y'),
    //                     'due_date' => $loan->due_date->format('d/m/Y'),
    //                     'days_overdue' => Carbon::now()->diffInDays(Carbon::parse($loan->due_date)),
    //                     'penalty_amount' => number_format($penaltyAmount, 2, ',', '.')
    //                 ];
    //             }
    //         }

    //         Penalties::InsertDataPenalties($memberData['user_id'], $Penalties['item_code'] ?? null, $Penalties['loan_date'] ?? null, $Penalties['due_date'] ?? null, $Penalties['penalty_amount'] ?? null);

    //         DB::commit();

    //         return successResponse([
    //             'penalties' => $penalties,
    //             'total_penalties' => count($penalties)
    //         ], 'Data denda peminjaman berhasil diambil.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         Log::error('Error in LoanPenalty: ' . $e->getMessage(), [
    //             'member_id' => $memberData['user_id'] ?? null,
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return errResponse(500, 'Terjadi kesalahan saat mengambil data denda. Silakan coba lagi.');
    //     }
    // }

    public static function calculatePenaltyForLoan($loan, $memberId)
    {
        $dueDate = Carbon::parse($loan->due_date)->startOfDay();
        $currentDate = Carbon::now()->startOfDay();

        if ($currentDate->greaterThan($dueDate)) {
            $item = Item::where('item_code', $loan->item_code)->first();
            if (!$item) return 0;

            $rule = self::getActiveRule($memberId, $item->coll_type_id);
            
            // Baca fine_each_day spesifik dari rule terkait item tersebut
            $fineEachDay = $rule->fine_each_day ?? 0;

            if ($fineEachDay <= 0) {
                return 0; // Jika fine di set 0 di database rule (seperti modul), tidak ada denda
            }

            $daysOverdue = $dueDate->diffInDays($currentDate);
            return $daysOverdue * $fineEachDay;
        }

        return 0;
    }

    public static function getLoan()
    {
        $memberData = Session::get('biblio_user');
        checkMemberUserValid($memberData);

        $loans = Loan::getMemberActiveLoans($memberData['member_id']);

        return successResponse([
            'loan_ids' => $loans->pluck('loan_id')->toArray(),
            'active_loans' => $loans,
            'total_active_loans' => count($loans)
        ], 'Data peminjaman aktif berhasil diambil.');
    }

    public static function checkLoanExists($loanId, $memberData)
    {
        $loan = Loan::where('loan_id', $loanId)
            ->where('member_id', $memberData['member_id'])
            ->where('is_return', 0)
            ->first();

        if (!$loan) {
            return errResponse(404, 'Data peminjaman tidak ditemukan atau sudah dikembalikan.');
        }
    }



    // public static function printLoanReceipt($printReceipts, $memberId, $memberName, $printerId)
    // {
    //     try {
    //         // 1. Inisialisasi Koneksi ke Printer USB Windows
    //         // Pastikan parameter $printerId berisi nama Share Printer (misal: "XP58")
    //         $connector = new NetworkPrintConnector($printerId, 9100);
    //         $printer = new Printer($connector);

    //         // 2. Desain Header Struk (Format untuk kertas 58mm biasanya muat ~32 karakter)
    //         $printer->setJustification(Printer::JUSTIFY_CENTER);
    //         $printer->setEmphasis(true);
    //         $printer->text("PERPUSTAKAAN MAJU JAYA\n"); // Ganti dengan nama perpustakaan/toko Anda
    //         $printer->setEmphasis(false);
    //         $printer->text("Struk Peminjaman\n");
    //         $printer->text("--------------------------------\n"); // 32 Karakter

    //         // 3. Desain Informasi Anggota
    //         $printer->setJustification(Printer::JUSTIFY_LEFT);
    //         $printer->text("ID Anggota : " . $memberId . "\n");
    //         $printer->text("Nama       : " . $memberName . "\n");
    //         $printer->text("Tanggal    : " . Carbon::now()->format('d/m/Y H:i:s') . "\n");
    //         $printer->text("--------------------------------\n");

    //         // 4. Looping Daftar Item/Buku yang Dipinjam
    //         foreach ($printReceipts as $item) {
    //             // Asumsi: object/array $item memiliki key 'judul_buku'
    //             // Silakan sesuaikan 'judul_buku' dengan key yang benar pada data Anda
    //             $judul = $item->title ?? 'Buku Tidak Diketahui';
    //             $dueDate = $item->due_date ?? 'N/A';
    //             $kode = $item->item_code ?? 'N/A';
                
    //             // Memotong judul jika terlalu panjang agar tidak merusak format struk 58mm
    //             $judul = substr($judul, 0, 32); 

    //             $printer->text($judul . "\n");
    //             $printer->text("Kode Buku : " . $kode . "\n");
    //             $printer->text("Jatuh Tempo: " . $dueDate . "\n"); 
    //         }

    //         // 5. Desain Footer Struk
    //         $printer->text("--------------------------------\n");
    //         $printer->setJustification(Printer::JUSTIFY_CENTER);
    //         $printer->text("Harap kembalikan buku\n");
    //         $printer->text("tepat pada waktunya.\n");
    //         $printer->text("Terima Kasih!\n");

    //         // 6. Potong kertas (Cut) dan tutup koneksi
    //         $printer->feed(3); // Beri jarak 3 baris sebelum pisau memotong
    //         $printer->cut();   // Potong struk
    //         $printer->close(); // Tutup koneksi port

    //         return true;
    //     } catch (\Exception $e) {
    //         Log::error('Exception in printLoanReceipt: ' . $e->getMessage(), [
    //             'member_id' => $memberId,
    //             'printer_id' => $printerId
    //         ]);
    //         return false;
    //     }
    // }
}
