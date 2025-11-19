<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Biblio\Biblio;
use App\Models\Biblio\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\BiblioService;
use App\Services\Frontend\CartLoanService;

class CartLoanController extends Controller
{
    //clear cart
    //get cart items
    //remove item from cart
    public function clearCart()
    {
        try {
            $content = SafeDataService::safeExecute(
                fn() => CartLoanService::clearCart()
            );
            return $content;
        } catch (\Exception $e) {
            Log::error('CartLoanController clearCart error: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            $content = SafeDataService::safeExecute(
                fn() => CartLoanService::removeFromCart($request)
            );
            return $content;
        } catch (\Exception $e) {
            Log::error('CartLoanController removeFromCart error: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Get cart items (Mobile)
     */
    public function getCartItems()
    {
        try {
            $content = SafeDataService::safeExecute(
                fn() => CartLoanService::getCartItems()
            );
            return $content;
        } catch (\Exception $e) {
            Log::error('CartLoanController getCartItems error: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function addBookToCartLoan(Request $request) 
    {
        try {
            $content = SafeDataService::safeExecute(
                fn() => CartLoanService::addBookToCartLoan($request)
            );
            return $content;
        } catch (\Exception $e) {
            Log::error('CartLoanController addBookToCartLoan error: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}
