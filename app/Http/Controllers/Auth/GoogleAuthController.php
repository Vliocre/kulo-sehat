<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Login Google gagal. Silakan coba lagi.']);
        }

        if (! $googleUser->getEmail()) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Akun Google tidak mengirimkan alamat email.']);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna Google',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(40)),
                'role' => 'pengguna',
                'doctor_verification_status' => 'not_required',
            ]);

            event(new Registered($user));
        }

        if ($user->role === 'dokter' && ! $user->isApprovedDoctor()) {
            return redirect()
                ->route('login')
                ->withErrors(['role' => 'Akun dokter Anda belum disetujui admin.']);
        }

        Auth::login($user, true);

        return redirect()->intended($this->defaultRedirectFor($user));
    }

    private function defaultRedirectFor(User $user): string
    {
        if ($user->isAdmin()) {
            return route('admin.dashboard', absolute: false);
        }

        if ($user->isApprovedDoctor()) {
            return route('doctor.dashboard', absolute: false);
        }

        return route('dashboard', absolute: false);
    }
}
