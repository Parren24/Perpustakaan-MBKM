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

class CartLoanService{
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

            return successResponse([
                'cart_items' => $cart->list_item,
                'total_items' => count($cart->list_item),
                'remaining_slots' => 2 - count($cart->list_item) //notes jadikan parameter juga
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
            $items = $cart ? ($cart->list_item ?? []) : [];

            return successResponse([
                'cart_items' => $items,
                'total_items' => count($items),
                'remaining_slots' => 2 - count($items),
                'can_add_more' => count($items) < 2
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

            $itemCode = $request->input('item_code');
            if (!$itemCode) {
                return errResponse(400, 'Kode item tidak boleh kosong.');
            }

            // Cari item dengan validasi lengkap
            $itemBook = Item::with('biblio.authors')->where('item_code', $itemCode)->first();
            if (!$itemBook) {
                return errResponse(404, 'Buku dengan kode "' . $itemCode . '" tidak ditemukan.');
            }

            // Cek ketersediaan buku
            if (!$itemBook->is_available) {
                return errResponse(409, 'Buku ini tidak tersedia untuk dipinjam. Status: ' . $itemBook->status_name);
            }

            // Cari atau buat cart untuk member
            $cart = CartLoan::firstOrCreate(
                ['member_id' => $memberData['user_id']],
                ['list_item' => []]
            );

            $currentItems = $cart->list_item ?? [];

            // Validasi batas maksimal
            if (count($currentItems) >= 2) {
                return errResponse(400, 'Batas maksimal 2 buku per peminjaman telah tercapai.');
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
                'remaining_slots' => 2 - count($currentItems)
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
