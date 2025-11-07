<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\ItemService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    /**
     * Menampilkan dashboard peminjaman user
     */
    public function dashboard()
    {
        $dashboardData = ItemService::getUserDashboard();
        
        return view('frontend.loan.dashboard', compact('dashboardData'));
    }

    /**
     * API endpoint untuk mengecek status peminjaman user
     */
    public function checkLoanStatus(): JsonResponse
    {
        $loanInfo = ItemService::getLoanInfo();
        
        return response()->json([
            'status' => 'success',
            'data' => $loanInfo
        ]);
    }

    /**
     * API endpoint untuk mengecek apakah user masih bisa meminjam
     */
    public function canBorrow(): JsonResponse
    {
        $canBorrow = ItemService::canBorrow();
        $activeLoanCount = ItemService::getActiveLoanCount();
        
        return response()->json([
            'status' => 'success',
            'can_borrow' => $canBorrow,
            'active_loans' => $activeLoanCount,
            'max_loans' => 2,
            'message' => $canBorrow 
                ? "Anda masih bisa meminjam " . (2 - $activeLoanCount) . " item lagi."
                : "Anda telah mencapai batas maksimal peminjaman (2 item). Mohon kembalikan item terlebih dahulu."
        ]);
    }

    /**
     * Menampilkan halaman riwayat peminjaman
     */
    public function history()
    {
        $activeLoans = ItemService::getActiveLoans();
        $loanInfo = ItemService::getLoanInfo();
        
        return view('frontend.loan.history', compact('activeLoans', 'loanInfo'));
    }
}