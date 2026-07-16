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
            Log::error('LoanController completeLoan error: ' . $e->getMessage());

            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function returnLoanItem(Request $request)
    {
        try {
            $content = SafeDataService::safeExecute(
                function () use ($request) {
                    $memberData = \Illuminate\Support\Facades\Session::get('biblio_user');
                    return LoanService::returnLoanItem($request->loan_id, $memberData);
                }
            );
            return $content;
        } catch (\Exception $e) {
            Log::error('LoanController returnLoanItem error: ' . $e->getMessage());

            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function getLoan(): JsonResponse
    {
        try {
            $content = SafeDataService::safeExecute(
                fn() => LoanService::getLoan()
            );
            return $content;
        } catch (\Exception $e) {
            Log::error('LoanController getLoan error: ' . $e->getMessage());

            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function LoanPenalty(): JsonResponse
    {
        try {
            $content = SafeDataService::safeExecute(
                fn() => LoanService::LoanPenalty()
            );
            return $content;
        } catch (\Exception $e) {
            Log::error('LoanController LoanPenalty error: ' . $e->getMessage());

            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}
