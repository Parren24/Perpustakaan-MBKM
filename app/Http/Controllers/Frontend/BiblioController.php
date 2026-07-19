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
use App\Models\CartLoan;

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
            'session_expires_at' => now()->addMinutes(config('services.kios.session_minutes'))->toISOString()
        ]);

        Cache::forget('kios_' . $sessionId); // Hapus agar tidak dipakai ulang

        return response()->json([
            'status' => true,
            'data' => [
                'member_name' => $kiosData['member_name'],
                'member_id' => $kiosData['member_id'],
                'session_expires_at' => Session::get('biblio_user.session_expires_at'),
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

    public function checkKiosSessionStatus(Request $request)
    {
        $memberData = Session::get('biblio_user');

        if (!$memberData || !isset($memberData['session_expires_at'])) {
            return response()->json([
                'status'  => false,
                'message' => 'Sesi tidak ditemukan.',
                'expired' => true,
            ], 401);
        }

        if (now()->isAfter($memberData['session_expires_at'])) {
            Session::forget('biblio_user');
            return response()->json([
                'status'  => false,
                'message' => 'Sesi telah kedaluwarsa.',
                'expired' => true,
            ], 401);
        }

        // Cuma dikembalikan apa adanya, TIDAK diubah/diperpanjang
        return response()->json([
            'status'             => true,
            'session_expires_at' => $memberData['session_expires_at'],
        ]);
    }

    public function closeKiosSession(Request $request)
    {
        $memberData = Session::get('biblio_user');

        if ($memberData && isset($memberData['member_id'])) {
            CartLoan::getMemberIdInCart($memberData['member_id'])?->delete();
        }

        Session::forget('biblio_user');

        return response()->json(['status' => true]);
    }

    public function showUnlockPin()
    {
        return view('contents.frontend.pages.biblio.kios-unlock');
    }

    public function verifyDevicePin(Request $request)
    {
        $request->validate(['pin' => 'required|string']);

        if ($request->input('pin') !== config('services.kios.pin')) {
            return response()->json(['status' => false, 'message' => 'PIN salah.'], 422);
        }

        $minutesUntilMidnight = now()->diffInMinutes(now()->endOfDay());

        $cookie = cookie(
            'kios_device_unlocked',
            now()->toDateString(),      // value = tanggal hari ini, misal "2026-07-18"
            $minutesUntilMidnight,      // expire otomatis pas ganti hari
            '/', null, false, true      // httpOnly = true, tidak bisa dibaca via JS (lebih aman)
        );

        return response()->json(['status' => true, 'redirect' => route('frontend.biblio.index')])
            ->withCookie($cookie);
    }
}
