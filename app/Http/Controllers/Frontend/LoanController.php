<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\ItemService;
use App\Services\Frontend\LoanService;
use App\Exceptions\KiosSessionExpiredException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Services\Frontend\SafeDataService;
use Illuminate\Support\Facades\Session;

class LoanController extends Controller
{
    public function completeLoan(Request $request)
    {
        try {
            return LoanService::storeLoanTransaction($request);
        } catch (KiosSessionExpiredException $e) {
            return $e->toResponse();
        } catch (\Exception $e) {
            Log::error('LoanController completeLoan error: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function returnLoanItem(Request $request)
    {
        try {
            $memberData = Session::get('biblio_user');
            return LoanService::returnLoanItem($request->loan_id, $memberData);
        } catch (KiosSessionExpiredException $e) {
            return $e->toResponse();
        } catch (\Exception $e) {
            Log::error('LoanController returnLoanItem error: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function getLoan(): JsonResponse
    {
        try {
            return LoanService::getLoan();
        } catch (KiosSessionExpiredException $e) {
            return $e->toResponse();
        } catch (\Exception $e) {
            Log::error('LoanController getLoan error: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function LoanPenalty(): JsonResponse
    {
        try {
            return LoanService::LoanPenalty();
        } catch (KiosSessionExpiredException $e) {
            return $e->toResponse();
        } catch (\Exception $e) {
            Log::error('LoanController LoanPenalty error: ' . $e->getMessage());
            return errResponse(500, 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}
