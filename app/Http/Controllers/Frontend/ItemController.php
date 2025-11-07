<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Biblio\Item;
use Illuminate\Http\Request;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ItemService;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function __construct()
    {
        // Hanya route borrowItem dan initiateUserToken yang perlu auth, index bisa diakses tanpa login
        $this->middleware('auth')->only(['borrowItem', 'initiateUserToken']);
    }

    public function index()
    {
       $content = SafeDataService::safeExecute(
            fn() => ItemService::getContent(),
            SafeDataService::getItemFallbacks()->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ItemService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.item.index', compact('content', 'pageConfig'));
    }

    // public function tokenConfirmation(Request $request)
    // {
    //     $content = SafeDataService::safeExecute(
    //         fn() => ItemService::tokenConfirmation($request)
    //     );
    //     return $content;
    // }

    public function borrowItem(Request $request)
    {
        try {
            $content = SafeDataService::safeExecute(
                fn() => ItemService::borrowItem($request)
            );
            return $content;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('ItemController borrowItem error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }

    public function initiateUserToken(Request $request)
    {
        try {
            $content = SafeDataService::safeExecute(
                fn() => ItemService::initiateUserToken()
            );
            return $content;
        } catch (\Exception $e) {
            Log::error('ItemController initiateUserToken error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'error' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
