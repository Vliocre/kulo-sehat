<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user dan generate token
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('mobile-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'pengguna'
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user mobile dengan Google ID token dan generate token Sanctum.
     */
    public function google(Request $request)
    {
        try {
            $request->validate([
                'id_token' => 'required|string',
            ]);

            $googleClientId = config('services.google.client_id');

            if (! $googleClientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Google Client ID belum dikonfigurasi di server'
                ], 500);
            }

            $googleResponse = Http::acceptJson()->get('https://oauth2.googleapis.com/tokeninfo', [
                'id_token' => $request->input('id_token'),
            ]);

            if (! $googleResponse->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Google tidak valid'
                ], 401);
            }

            $googleUser = $googleResponse->json();

            if (($googleUser['aud'] ?? null) !== $googleClientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Google tidak cocok dengan Client ID aplikasi'
                ], 401);
            }

            if (($googleUser['email_verified'] ?? 'false') !== 'true') {
                return response()->json([
                    'success' => false,
                    'message' => 'Email Google belum terverifikasi'
                ], 401);
            }

            if (empty($googleUser['email']) || empty($googleUser['sub'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data akun Google tidak lengkap'
                ], 401);
            }

            $user = User::where('google_id', $googleUser['sub'])
                ->orWhere('email', $googleUser['email'])
                ->first();

            if ($user) {
                $user->forceFill([
                    'google_id' => $user->google_id ?: $googleUser['sub'],
                    'email_verified_at' => $user->email_verified_at ?: now(),
                ])->save();
            } else {
                $user = User::create([
                    'name' => $googleUser['name'] ?? $googleUser['email'],
                    'email' => $googleUser['email'],
                    'google_id' => $googleUser['sub'],
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(40)),
                    'role' => 'pengguna',
                    'doctor_verification_status' => 'not_required',
                ]);

                event(new Registered($user));
            }

            if ($user->role === 'dokter' && ! $user->isApprovedDoctor()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun dokter Anda belum disetujui admin'
                ], 403);
            }

            $token = $user->createToken('mobile-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login Google berhasil',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'pengguna'
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register user baru
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pengguna'
            ]);

            $token = $user->createToken('mobile-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user (hapus token)
     */
    public function logout(Request $request)
    {
        try {
            /** @var User|null $user */
            $user = $request->user();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $token = $user->currentAccessToken();
            if ($token) {
                $token->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }
}
