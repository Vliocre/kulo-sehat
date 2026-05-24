<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private const DOCTOR_REGISTRATION_SESSION_KEY = 'doctor_registration';

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'in:dokter,pengguna'],
        ]);

        $role = $validated['role'] ?? 'pengguna';

        if ($role === 'dokter') {
            $request->session()->put(self::DOCTOR_REGISTRATION_SESSION_KEY, [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Crypt::encryptString($validated['password']),
            ]);

            return redirect()->route('register.doctor.verification');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'doctor_verification_status' => 'not_required',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function createDoctorVerification(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has(self::DOCTOR_REGISTRATION_SESSION_KEY)) {
            return redirect()
                ->route('register')
                ->withErrors(['role' => 'Mulai pendaftaran dokter dari formulir daftar terlebih dahulu.']);
        }

        return view('auth.doctor-verification', [
            'registration' => $request->session()->get(self::DOCTOR_REGISTRATION_SESSION_KEY),
        ]);
    }

    public function storeDoctorVerification(Request $request): RedirectResponse
    {
        $registration = $request->session()->get(self::DOCTOR_REGISTRATION_SESSION_KEY);

        if (! $registration) {
            return redirect()
                ->route('register')
                ->withErrors(['role' => 'Sesi pendaftaran dokter sudah habis. Silakan ulangi dari awal.']);
        }

        $validated = $request->validate([
            'doctor_idi_number' => ['required', 'string', 'max:100'],
            'doctor_str_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'doctor_sip_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
        ]);

        $user = User::create([
            'name' => $registration['name'],
            'email' => $registration['email'],
            'password' => Crypt::decryptString($registration['password']),
            'role' => 'dokter',
            'doctor_idi_number' => $validated['doctor_idi_number'],
            'doctor_str_file' => $request->file('doctor_str_file')->store('doctor-verifications/str', 'public'),
            'doctor_sip_file' => $request->file('doctor_sip_file')->store('doctor-verifications/sip', 'public'),
            'doctor_verification_status' => 'pending',
        ]);

        event(new Registered($user));

        $request->session()->forget(self::DOCTOR_REGISTRATION_SESSION_KEY);

        return redirect()
            ->route('home')
            ->with('status', 'Pendaftaran dokter berhasil dikirim. Tunggu persetujuan admin sebelum login ke dashboard dokter.');
    }
}
