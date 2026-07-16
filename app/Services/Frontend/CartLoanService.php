<?php

namespace App\Services\Frontend;

use App\Services\Frontend\SiteIdentityService;
use App\Models\Biblio\Item;
use App\Models\Biblio\Biblio;
use App\Models\Biblio\Loan;
use App\Models\Biblio\LoanRules;
use App\Models\CartLoan;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CartLoanService{
    public static function loanRules($memberId)
    {
        $loanRules = LoanRules::getLoanRules($memberId);

        $loanLimit = $loanRules->loan_limit ?? 0;
        $loanPeriode = $loanRules->loan_periode ?? 0;
        $reborrowLimit = $loanRules->reborrow_limit ?? 0;
        $fineEachDay = $loanRules->fine_each_day ?? 0;

        return [$loanLimit, $loanPeriode, $reborrowLimit, $fineEachDay];
    }

   /**
     * Clear semua items dari cart
     */
    public static function clearCart()
    {
        try {
        
            $memberData = Session::get('biblio_user');

            checkMemberUserValid($memberData);

            $cart = CartLoan::getMemberIdInCart($memberData['user_id']);
            if ($cart) {
                $cart->delete();
            }
            return successMessage('Keranjang berhasil dikosongkan.');
        } catch (\Exception $e) {
            return errResponse(500, 'Terjadi kesalahan saat mengosongkan keranjang.');
        }
    }

    public static function removeFromCart($request)
    {
        try {
            $memberData = Session::get('biblio_user');

            checkMemberUserValid($memberData);

            $itemCode = $request->input('item_code');
            if (!$itemCode) {
                return errResponse(400, 'Kode item tidak boleh kosong.');
            }

            $cart = CartLoan::getMemberIdInCart($memberData['user_id']);
            if (!$cart) {
                return errResponse(404, 'Keranjang tidak ditemukan.');
            }

            $currentItems = $cart->list_item ?? [];
            $filteredItems = array_filter($currentItems, function ($item) use ($itemCode) {
                return $item['item_code'] !== $itemCode;
            });

            // Reindex array
            $cart->list_item = array_values($filteredItems);
            $cart->save();

            $loanRules = self::loanRules($memberData['nomor_induk']);
            $loanLimit = $loanRules[0]; 

            return successResponse([
                'cart_items' => $cart->list_item,
                'total_items' => count($cart->list_item),
                'remaining_slots' => $loanLimit - count($cart->list_item), //notes jadikan parameter juga,
                'loan_limit' => $loanLimit
            ], 'Item berhasil dihapus dari keranjang.');
        } catch (\Exception $e) {
            return errResponse(500, 'Terjadi kesalahan saat menghapus item dari keranjang.');
        }
    }

    public static function getCartItems()
    {
        try {
            $memberData = Session::get('biblio_user');
            checkMemberUserValid($memberData);

            $cart = CartLoan::getMemberIdInCart($memberData['user_id']);
            $rawItems = $cart ? ($cart->list_item ?? []) : [];

            // Hanya ambil buku reguler (Abaikan Modul / coll_type_id == 13)
            $filteredItems = array_filter($rawItems, function($item) {
                return isset($item['coll_type_id']) && $item['coll_type_id'] != 13;
            });

            $items = array_values($filteredItems);

            // Dapatkan limit dari rules
            $loanRules = self::loanRules($memberData['nomor_induk']);
            $loanLimit = $loanRules[0];

            // HITUNG BUKU REGULER YANG SEDANG DIPINJAM & BELUM DIKEMBALIKAN
            $activeNormalLoanCount = Loan::join('item', 'loan.item_code', '=', 'item.item_code')
                ->where('loan.member_id', $memberData['nomor_induk'])
                ->where('loan.is_return', 0)
                ->where('item.coll_type_id', '!=', 13)
                ->count();

            // Total Slot Terpakai = (Sedang dipinjam + Ada di keranjang)
            $totalUsedSlots = $activeNormalLoanCount + count($items);
            
            // Sisa Slot (Pastikan tidak minus menggunakan max)
            $remainingSlots = max(0, $loanLimit - $totalUsedSlots);

            return successResponse([
                'cart_items'      => $items,
                'total_items'     => count($items),
                'active_loans'    => $activeNormalLoanCount, // Info tambahan jika ingin ditampilkan di Frontend
                'remaining_slots' => $remainingSlots,
                'can_add_more'    => $totalUsedSlots < $loanLimit,
                'loan_limit'      => $loanLimit
            ], 'Data keranjang berhasil diambil.');
        } catch (\Exception $e) {
            Log::error('Error getCartItems: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan saat mengambil data keranjang.');
        }
    }

    public static function getCartModulItems()
    {
        try {
            $memberData = Session::get('biblio_user');
            checkMemberUserValid($memberData);

            $cart = CartLoan::getMemberIdInCart($memberData['user_id']);
            $rawItems = $cart ? ($cart->list_item ?? []) : [];

            $filteredItems = array_filter($rawItems, function($item) {
                return isset($item['coll_type_id']) && $item['coll_type_id'] = 13;
            });

            $items = array_values($filteredItems);

            return successResponse([
                'cart_items' => $items,
                'total_items' => count($items)
            ], 'Data keranjang berhasil diambil.');
        } catch (\Exception $e) {
            return errResponse(500, 'Terjadi kesalahan saat mengambil data keranjang.');
        }
    }

    public static function addBookToCartLoan($request)
    {
        try {
            // Periksa session member yang sudah ter-authorize
            $memberData = Session::get('biblio_user');

            checkMemberUserValid($memberData);

            // Validasi session harus punya nomor_induk (sudah ter-authorize)
            checkMemberNomorIndukValid($memberData);

            $loanRules = self::loanRules($memberData['nomor_induk']);
            $loanLimit = $loanRules[0];

            $itemCode = $request->input('item_code');
            if (!$itemCode) {
                return errResponse(400, 'Kode item tidak boleh kosong.');
            }

            // Cari item dengan validasi lengkap
            $itemBook = Item::with('biblio.authors')->where('item_code', $itemCode)->first();
            if (!$itemBook) {
                return errResponse(404, 'Buku dengan kode "' . $itemCode . '" tidak ditemukan.');
            }

            if ($itemBook && $itemBook->coll_type_id == 13) {
                return errResponse(400, 'Buku ini tidak dapat dipinjam karena merupakan Modul Perkuliahan.');
            }

            if (!$itemBook->biblio) {
                return errResponse(404, 'Informasi bibliografi untuk buku ini tidak ditemukan.');
            }

            // Cek ketersediaan buku
            if (!$itemBook->is_available) {
                return errResponse(409, 'Buku ini tidak tersedia untuk dipinjam. Status: ' . $itemBook->status_name);
            }

            if ($itemBook->item_status_id != '0') {
                return errResponse(409, 'Buku ini sedang dipinjam (Status tidak tersedia).');
            }

            // Cari atau buat cart untuk member
            $cart = CartLoan::firstOrCreate(
                ['member_id' => $memberData['user_id']],
                ['list_item' => []]
            );

            $currentItems = $cart->list_item ?? [];

            // Validasi batas maksimal
            if (count($currentItems) >= $loanLimit) {
                return errResponse(400, "Batas maksimal {$loanLimit} buku per peminjaman telah tercapai.");
            }

            $normalCartItemsCount = 0;
            foreach ($currentItems as $cItem) {
                if (isset($cItem['coll_type_id']) && $cItem['coll_type_id'] != 13) {
                    $normalCartItemsCount++;
                }
            }

            // 2. Hitung buku reguler yang sedang dipinjam (aktif)
            $activeNormalLoanCount = Loan::join('item', 'loan.item_code', '=', 'item.item_code')
                ->where('loan.member_id', $memberData['nomor_induk'])
                ->where('loan.is_return', 0)
                ->where('item.coll_type_id', '!=', 13)
                ->count();

            // 3. Validasi batas maksimal (Keranjang + Sedang dipinjam)
            if (($normalCartItemsCount + $activeNormalLoanCount) >= $loanLimit) {
                return errResponse(400, "Batas maksimal peminjaman {$loanLimit} buku telah tercapai (Termasuk buku yang sedang Anda pinjam saat ini).");
            }

            // Cek duplikasi item dalam cart
            foreach ($currentItems as $cartItem) {
                if ($cartItem['item_code'] === $itemCode) {
                    return errResponse(400, 'Buku ini sudah ada dalam keranjang.');
                }
            }

            // Cek apakah user sudah meminjam item yang sama dan belum dikembalikan
            $existingLoan = Loan::existingLoan($itemCode, $memberData['nomor_induk']);

            if ($existingLoan) {
                return errResponse(400, 'Anda sudah meminjam buku ini sebelumnya dan belum mengembalikannya.');
            }

            // Tambahkan item ke cart
            $newItem = [
                'item_id' => $itemBook->item_id,
                'item_code' => $itemBook->item_code,
                'biblio_id' => $itemBook->biblio_id,
                'coll_type_id' => $itemBook->coll_type_id,
                'title' => $itemBook->biblio->title ?? 'N/A',
                'author' => $itemBook->biblio->author ?? 'N/A',
                'added_at' => now()->toISOString()
            ];

            $currentItems[] = $newItem;
            $cart->list_item = $currentItems;
            $cart->save();

            return successResponse([
                'cart_items' => $currentItems,
                'total_items' => count($currentItems),
                'remaining_slots' => $loanLimit - count($currentItems),
                'loan_limit' => $loanLimit
            ], 'Buku berhasil ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            Log::error('Error adding book to cart: ' . $e->getMessage(), [
                'item_code' => $itemCode ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return errResponse(500, 'Terjadi kesalahan saat menambahkan buku ke keranjang.');
        }
    }

    public static function addModulToCartLoan($request)
    {
        try {
            // Periksa session member yang sudah ter-authorize
            $memberData = Session::get('biblio_user');

            checkMemberUserValid($memberData);

            // Validasi session harus punya nomor_induk (sudah ter-authorize)
            checkMemberNomorIndukValid($memberData);

            $itemCode = $request->input('item_code');
            if (!$itemCode) {
                return errResponse(400, 'Kode item tidak boleh kosong.');
            }

            // Cari item dengan validasi lengkap
            $itemBook = Item::with('biblio.authors')->where('item_code', $itemCode)->first();
            if (!$itemBook) {
                return errResponse(404, 'Modul dengan kode "' . $itemCode . '" tidak ditemukan.');
            }

            if ($itemBook && $itemBook->coll_type_id != 13) {
                return errResponse(400, 'Item ini bukan Modul Perkuliahan dan tidak dapat dipinjam melalui modul ini.');
            }

            if (!$itemBook->biblio) {
                return errResponse(404, 'Informasi bibliografi untuk modul ini tidak ditemukan.');
            }

            // Cek ketersediaan modul
            if (!$itemBook->is_available) {
                return errResponse(409, 'Modul ini tidak tersedia untuk dipinjam. Status: ' . $itemBook->status_name);
            }  
             if ($itemBook->item_status_id != '0') {
                return errResponse(409, 'Modul ini sedang dipinjam (Status tidak tersedia).');
            } 

            $cart = CartLoan::firstOrCreate(
                ['member_id' => $memberData['user_id']],
                ['list_item' => []]
            );

            $currentItems = $cart->list_item ?? [];

            foreach ($currentItems as $cartItem) {
                if ($cartItem['item_code'] === $itemCode) {
                    return errResponse(400, 'Buku ini sudah ada dalam keranjang.');
                }
            }

            // Cek apakah user sudah meminjam item yang sama dan belum dikembalikan
            $existingLoan = Loan::existingLoan($itemCode, $memberData['nomor_induk']);

            if ($existingLoan) {
                return errResponse(400, 'Anda sudah meminjam buku ini sebelumnya dan belum mengembalikannya.');
            }
            
            // Tambahkan item ke cart
            $newItem = [
                'item_id' => $itemBook->item_id,
                'item_code' => $itemBook->item_code,
                'biblio_id' => $itemBook->biblio_id,
                'coll_type_id' => $itemBook->coll_type_id,
                'title' => $itemBook->biblio->title ?? 'N/A',
                'author' => $itemBook->biblio->author ?? 'N/A',
                'added_at' => now()->toISOString()
            ];

            $currentItems[] = $newItem;
            $cart->list_item = $currentItems;
            $cart->save();

            return successResponse([
                'cart_items' => $currentItems,
                'total_items' => count($currentItems),
            ], 'Buku berhasil ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            Log::error('Error adding book to cart: ' . $e->getMessage(), [
                'item_code' => $itemCode ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return errResponse(500, 'Terjadi kesalahan saat menambahkan buku ke keranjang.');
        }
    }
}
