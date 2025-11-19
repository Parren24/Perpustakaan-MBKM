<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\ItemService;
use App\Services\Frontend\LoanService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Services\Frontend\SafeDataService;

class LoanController extends Controller
{
    public function completeLoan(Request $request)
    {
        try {
            $content = SafeDataService::safeExecute(
                fn() => LoanService::storeLoanTransaction($request)
            );
            return $content;
        } catch (\Exception $e) {
            Log::error('BiblioController completeLoan error: ' . $e->getMessage());
           
            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}
