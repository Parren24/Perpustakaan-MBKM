<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

            $allowedEmails = [
                'delza@pcr.ac.id',
                'wahyudi@pcr.ac.id',
                'fajar@pcr.ac.id',
                'brilian21ti@mahasiswa.pcr.ac.id',
            ];

            if (!in_array($googleUser->getEmail(), $allowedEmails)) {
                return redirect()->route('login')->with('error', 'Email tidak diizinkan untuk login.');
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(uniqid()), 
                ]);
            }

            Auth::login($user, true);

            return redirect()->intended('/app/dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed!');
        }
    }
}
