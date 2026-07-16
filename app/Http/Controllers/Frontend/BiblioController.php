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

class BiblioController extends Controller
{
    public function index()
    {
        $content = SafeDataService::safeExecute(
            fn() => BiblioService::getContent(),
            SafeDataService::getPeminjamanFallbacks()->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => BiblioService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.biblio.index', compact('content', 'pageConfig'));
    }

    public function returnLoanPage()
    {

        $content = SafeDataService::safeExecute(
            fn() => BiblioService::getContentReturn(),
            SafeDataService::getPeminjamanFallbacks()->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => BiblioService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.biblio.return-loan', compact('content', 'pageConfig'));
    }

    public function modulLoanPage()
    {
        $content = SafeDataService::safeExecute(
            fn() => BiblioService::getContentModul(),
            SafeDataService::getPeminjamanFallbacks()->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => BiblioService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.biblio.modul.modul-loan', compact('content', 'pageConfig'));
    }

    public function getItemInformation(Request $request, $item_code)
    {
        $content = SafeDataService::safeExecute(
            fn() => BiblioService::getBiblioInformation($item_code)
        );
        return $content;
    }
    /**
     * Authorize session with QR token (Kiosk -> Mobile)
     */
    public function authorizeSession(Request $request)
    {
        try {
            Log::info('BiblioController: authorizeSession called');
            // Bypass SafeDataService untuk debugging
            return BiblioService::authorizeSession($request);
        } catch (\Exception $e) {
            Log::error('BiblioController authorizeSession error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return errResponse(500, 'Terjadi kesalahan sistem.' . $e->getMessage());
        }
    }

    /**
     * Show mobile confirmation page for QR token
     */
    public function showConfirmation($token)
    {
        // Validasi token exists in cache
        $cacheKey = 'user_token_' . $token;
        $sessionData = Cache::get($cacheKey);

        if (!$sessionData) {
            return view('contents.frontend.pages.biblio.error', [
                'title' => 'Token Tidak Valid',
                'message' => 'Token tidak valid atau telah kedaluwarsa. Silakan scan ulang QR code di kiosk.'
            ]);
        }

        $pageConfig = SafeDataService::safeExecute(
            fn() => BiblioService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.biblio.confirmation', [
            'token' => $token,
            'sessionData' => $sessionData,
            'pageConfig' => $pageConfig
        ]);
    }

    /**
     * Show mobile cart page
     */
    public function getKiosQrAjax()
    {
        $data = BiblioService::generateKiosToken();
        return response()->json([
            'status' => true,
            'qrCode' => (string) $data['qrCode'], // SVG format
            'sessionId' => $data['sessionId']
        ]);
    }

    public function checkKiosStatus($sessionId)
    {
        $data = Cache::get('kios_' . $sessionId);
        if (!$data) {
            return response()->json(['status' => 'expired']);
        }
        return response()->json(['status' => $data['status']]);
    }

    public function claimKiosSession(Request $request)
    {
        $sessionId = $request->input('session_id');
        $kiosData = Cache::get('kios_' . $sessionId);

        if (!$kiosData || $kiosData['status'] !== 'scanned') {
            return response()->json(['status' => false, 'message' => 'Sesi tidak valid.']);
        }

        // Buat sesi lokal untuk Kios agar bisa checkout
        Session::put('biblio_user', [
            'member_id'          => $kiosData['member_id'],
            'member_name'        => $kiosData['member_name'],
            'authorized_at'      => now()->toISOString(),
            'session_expires_at' => now()->addMinutes(10)->toISOString()
        ]);

        Cache::forget('kios_' . $sessionId); // Hapus agar tidak dipakai ulang

        return response()->json([
            'status' => true,
            'data' => [
                'member_name' => $kiosData['member_name'],
                'member_id' => $kiosData['member_id']
            ]
        ]);
    }

    /**
     * Show scan book page
     */
    public function showScanBook()
    {
        $memberData = Session::get('biblio_user');
        if (!$memberData) {
            return redirect()->route('frontend.biblio.index')
                ->with('error', 'Sesi tidak valid. Silakan scan QR code lagi.');
        }

        $pageConfig = SafeDataService::safeExecute(
            fn() => BiblioService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.biblio.scan-book', [
            'memberData' => $memberData,
            'pageConfig' => $pageConfig
        ]);
    }
}
