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
            if (!$memberData || !isset($memberData['user_id'])) {
                return errResponse(401, 'Sesi member tidak valid atau telah kedaluwarsa.');
            }

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
            if (!$memberData || !isset($memberData['user_id'])) {
                return errResponse(401, 'Sesi member tidak valid atau telah kedaluwarsa.');
            }

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
            if (!$memberData || !isset($memberData['user_id'])) {
                return errResponse(401, 'Sesi member tidak valid atau telah kedaluwarsa.');
            }

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
}
