<?php

namespace App\Services\Frontend;

use App\Services\Frontend\SiteIdentityService;
use App\Models\Biblio\Item;
use App\Models\Biblio\Biblio;
use App\Models\Biblio\Loan;
use App\Models\CartLoan;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class LoanService {
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
            $cart = CartLoan::getMemberIdInCart($memberData['user_id']);

            if (!$cart || empty($cart->list_item)) {
                return errResponse(400, 'Keranjang peminjaman kosong.');
            }

            $cartItems = $cart->list_item;

            // if (empty($cartItems)) {
            //     return errResponse(400, 'Keranjang peminjaman kosong atau data tidak valid.');
            // }

            // Validasi ulang ketersediaan semua item sebelum memproses
            $unavailableItems = [];
            foreach ($cartItems as $cartItem) {
                $item = Item::where('item_code', $cartItem['item_code'])->first();
                if (!$item || !$item->is_available) {
                    $unavailableItems[] = $cartItem['item_code'];
                }
            }

            if ($unavailableItems) {
                DB::rollBack();
                return errResponse(400, 'Beberapa buku tidak tersedia: ' . implode(', ', $unavailableItems));
            }

            // Cek kembali batas maksimal peminjaman aktif
            $activeLoanCount = Loan::activeLoanCount($memberData['nomor_induk']);

            if ($activeLoanCount + count($cartItems) > 2) {
                DB::rollBack();
                return errResponse(400, 'Total peminjaman akan melebihi batas maksimal (2 buku).');
            }

            $loanIds = [];
            $processedItems = [];
            $duedate = Carbon::now()->addDays(7);

            //Notes :: Tambah parameter untuk due date 

            // Proses setiap item dalam cart
            foreach ($cartItems as $cartItem) {
                // Update status item menjadi dipinjam
                $item = Item::where('item_code', $cartItem['item_code'])->first();
                $item->item_status_id = '1'; // Status dipinjam
                $item->save();

                // Insert ke table loan
                $loanId = Loan::InsertDataTableLoan($cartItem['item_code'], $memberData['nomor_induk'], $duedate);

                $loanIds[] = $loanId;
                $processedItems[] = [
                    'loan_id' => $loanId,
                    'item_code' => $cartItem['item_code'],
                    'title' => $cartItem['title'] ?? 'N/A',
                    'due_date' => $duedate->format('d/m/Y')
                ];
            }

            // Hapus cart setelah berhasil
            $cart->forceDelete();

            // Clear session biblio user
            Session::forget('biblio_user');

            DB::commit();

            return successResponse([
                'loan_ids' => $loanIds,
                'borrowed_items' => $processedItems,
                'total_borrowed' => count($processedItems),
                'due_date' => $duedate->format('d/m/Y'),
                'return_reminder' => 'Jangan lupa mengembalikan buku tepat waktu untuk menghindari denda.'
            ], 'Transaksi peminjaman berhasil! Buku harus dikembalikan dalam 7 hari.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in storeLoanTransaction: ' . $e->getMessage(), [
                'member_id' => $memberData['user_id'] ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return errResponse(500, 'Terjadi kesalahan saat memproses peminjaman. Silakan coba lagi.');
        }
    }
}
