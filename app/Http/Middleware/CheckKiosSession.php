<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckKiosSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $memberData = Session::get('biblio_user');

        // Belum pernah claim session sama sekali
        if (!$memberData || !isset($memberData['member_id'])) {
            return $this->reject($request, 'Sesi member tidak valid. Silakan scan QR code di kiosk terlebih dahulu.');
        }

        // Sudah lewat 10 menit (atau expired_at tidak ada karena data korup)
        if (!isset($memberData['session_expires_at']) || now()->isAfter($memberData['session_expires_at'])) {
            Session::forget('biblio_user');
            return $this->reject($request, 'Sesi telah kedaluwarsa. Silakan scan QR code lagi.', true);
        }

        return $next($request);
    }

    private function reject(Request $request, string $message, bool $expired = false): Response
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => false,
                'message' => $message,
                'expired' => $expired,
            ], 401);
        }

        return redirect()->route('frontend.biblio.index')->with('error', $message);
    }
}