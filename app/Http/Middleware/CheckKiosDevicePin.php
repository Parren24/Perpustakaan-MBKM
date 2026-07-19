<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKiosDevicePin
{
    public function handle(Request $request, Closure $next): Response
    {
        $cookieValue = $request->cookie('kios_device_unlocked');

        // dicek 2 lapis: cookie ada, DAN isinya = tanggal hari ini
        // (kalau tanggal berganti, walau cookie belum expired, tetap dianggap terkunci)
        if ($cookieValue !== now()->toDateString()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status'      => false,
                    'message'     => 'Device belum diaktivasi. Masukkan PIN kios.',
                    'pin_required'=> true,
                ], 403);
            }

            return redirect()->route('frontend.biblio.kios.unlock');
        }

        return $next($request);
    }
}