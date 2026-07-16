<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Biblio\Member;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the Google callback.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback($provider)
    {
        try {
            $googleUser = Socialite::driver($provider)->user();

            // $allowedEmails = [
            //     'delza@pcr.ac.id',
            //     'wahyudi@pcr.ac.id',
            //     'fajar@pcr.ac.id',
            //     'brilian21ti@mahasiswa.pcr.ac.id',
            //     'varrent22si@mahasiswa.pcr.ac.id',
            //     'varrentedbert@gmail.com'
            // ];

            // if (!in_array($googleUser->getEmail(), $allowedEmails)) {
            //     return redirect()->route('login')->with('error', 'Email tidak diizinkan untuk login.');
            // }

            $user = Member::where('member_email', $googleUser->getEmail())->first();

            // if (!$user) {
            //     $user = User::create([
            //         'name' => $googleUser->getName(),
            //         'email' => $googleUser->getEmail(),
            //         'password' => bcrypt(uniqid()),
            //     ]);
            // }
            if (!$user) {
                return redirect()->route('login')->with('error', 'Akun tidak ditemukan. Silakan hubungi administrator.');
            }

            // $user->update([
            //     'name' => $googleUser->getName(),
            //     'member_email' => $googleUser->getEmail(),
            // ]);

            Auth::login($user, true);

            // Check if user has member role and redirect to token page
            if ($user->hasRole(['member', 'mahasiswa'])) {
                return redirect()->route('app.user.show', ['param1' => 'token']);
            }

            return redirect()->intended('/app/dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed!');
        }
    }
}
