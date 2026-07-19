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
use App\Exceptions\KiosSessionExpiredException;

class CartLoanService{
    // public static function loanRules($memberId)
    // {
    //     $loanRules = LoanRules::getLoanRules($memberId);

    //     $loanLimit = $loanRules->loan_limit ?? 0;
    //     $loanPeriode = $loanRules->loan_periode ?? 0;
    //     $reborrowLimit = $loanRules->reborrow_limit ?? 0;
    //     $fineEachDay = $loanRules->fine_each_day ?? 0;

    //     return [$loanLimit, $loanPeriode, $reborrowLimit, $fineEachDay];
    // }

    public static function getActiveRule($memberId, $collTypeId)
    {
        $rule = LoanRules::getActiveRule($memberId, $collTypeId);

        return $rule;
    }

   /**
     * Clear semua items dari cart
     */
    public static function clearCart()
    {
        try {
        
            $memberData = Session::get('biblio_user');

            checkMemberUserValid($memberData);

            $cart = CartLoan::getMemberIdInCart($memberData['member_id']);
            if ($cart) {
                $cart->delete();
            }
            return successMessage('Keranjang berhasil dikosongkan.');
        }catch (KiosSessionExpiredException $e) {
            return $e->toResponse();
        } 
        
        catch (\Exception $e) {
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

            $cart = CartLoan::getMemberIdInCart($memberData['member_id']);
            if (!$cart) {
                return errResponse(404, 'Keranjang tidak ditemukan.');
            }

            $currentItems = $cart->list_item ?? [];
            $filteredItems = array_filter($currentItems, function ($item) use ($itemCode) {
                return $item['item_code'] !== $itemCode;
            });

            $cart->list_item = array_values($filteredItems);
            $cart->save();

            return successResponse([
                'cart_items' => $cart->list_item,
                'total_items' => count($cart->list_item),
            ], 'Item berhasil dihapus dari keranjang.');
        }catch (KiosSessionExpiredException $e) {
            return $e->toResponse();
        } 
        
        catch (\Exception $e) {
            return errResponse(500, 'Terjadi kesalahan saat menghapus item dari keranjang.');
        }
    }

    public static function getCartItems()
    {
        try {
            $memberData = Session::get('biblio_user');
            checkMemberUserValid($memberData);

            $cart = CartLoan::getMemberIdInCart($memberData['member_id']);
            $items = $cart ? ($cart->list_item ?? []) : [];

            return successResponse([
                'cart_items'  => $items,
                'total_items' => count($items),
            ], 'Data keranjang berhasil diambil.');
        }catch (KiosSessionExpiredException $e) {
            return $e->toResponse();
        } 
        
        catch (\Exception $e) {
            Log::error('Error getCartItems: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan saat mengambil data keranjang.');
        }
    }

    public static function addBookToCartLoan($request)
    {
        try {
            $memberData = Session::get('biblio_user');
            checkMemberUserValid($memberData);
            checkMemberNomorIndukValid($memberData);

            $itemCode = $request->input('item_code');
            $itemBook = Item::with('biblio.authors')->where('item_code', $itemCode)->first();

            
            if (!$itemBook->is_available || $itemBook->item_status_id != '0') {
                return errResponse(409, 'Buku ini sedang dipinjam atau tidak tersedia.');
            }

            if (!$itemCode) {
                return errResponse(400, 'Kode item tidak boleh kosong.');
            }
            
            if (!$itemBook) {
                return errResponse(404, 'Buku dengan kode "' . $itemCode . '" tidak ditemukan.');
            }

            if (!$itemBook->biblio) {
                return errResponse(404, 'Informasi bibliografi untuk buku ini tidak ditemukan.');
            }

            // Ambil rule berdasarkan tipe koleksi item
            $rule = self::getActiveRule($memberData['member_id'], $itemBook->coll_type_id);
            if (!$rule) {
                return errResponse(400, 'Aturan peminjaman untuk jenis koleksi ini belum diatur.');
            }

            $loanLimit = $rule->loan_limit ?? 0;
            $isGlobalRule = ($rule->coll_type_id == 0);

            $cart = CartLoan::firstOrCreate(
                ['member_id' => $memberData['member_id']],
                ['list_item' => []]
            );

            $currentItems = collect($cart->list_item ?? []);

            if ($currentItems->contains('item_code', $itemCode)) {
                return errResponse(400, 'Buku ini sudah ada dalam keranjang.');
            }

            // 2. HITUNG ITEM DI KERANJANG BERDASARKAN JENIS ATURAN
            $cartItemsCount = $isGlobalRule
                ? $currentItems->filter(function ($cItem) use ($memberData) {
                    $itemRule = self::getActiveRule($memberData['member_id'], $cItem['coll_type_id']);
                    return $itemRule && $itemRule->coll_type_id == 0;
                })->count()
                : $currentItems->filter(fn ($cItem) =>
                    isset($cItem['coll_type_id']) && $cItem['coll_type_id'] == $itemBook->coll_type_id
                )->count();

            // 3. HITUNG BUKU YANG SEDANG DIPINJAM (AKTIF) BERDASARKAN JENIS ATURAN
            $activeLoanCount = 0;

            if ($isGlobalRule) {
                $activeLoanCount = Loan::join('item', 'loan.item_code', '=', 'item.item_code')
                    ->where('loan.member_id', $memberData['member_id'])
                    ->where('loan.is_return', 0)
                    ->pluck('item.coll_type_id')
                    ->filter(function ($collTypeId) use ($memberData) {
                        $aRule = self::getActiveRule($memberData['member_id'], $collTypeId);
                        return $aRule && $aRule->coll_type_id == 0;
                    })
                    ->count();
            } else {
                $activeLoanCount = Loan::join('item', 'loan.item_code', '=', 'item.item_code')
                    ->where('loan.member_id', $memberData['member_id'])
                    ->where('loan.is_return', 0)
                    ->where('item.coll_type_id', $itemBook->coll_type_id)
                    ->count();
            }

            // 4. VALIDASI LIMIT
            if (($cartItemsCount + $activeLoanCount) >= $loanLimit) {
                $kategori = $isGlobalRule ? "buku reguler (global)" : "jenis koleksi ini";
                return errResponse(400, "Batas maksimal peminjaman {$kategori} ({$loanLimit} buku) telah tercapai.");
            }

            // Cek apakah sedang meminjam buku fisik yang sama
            $existingLoan = Loan::existingLoan($itemCode, $memberData['member_id']);
            if ($existingLoan) {
                return errResponse(400, 'Anda sudah meminjam buku ini sebelumnya dan belum mengembalikannya.');
            }

            // 5. TAMBAHKAN KE KERANJANG
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
                'total_items' => count($currentItems)
            ], 'Item berhasil ditambahkan ke keranjang.');
        } catch (KiosSessionExpiredException $e) {
            return $e->toResponse();
        }catch (\Exception $e) {
            Log::error('Error adding item to cart: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan saat menambahkan item ke keranjang.');
        }
    }

}
