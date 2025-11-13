<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Penting untuk memeriksa otentikasi
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles  // Ini akan menangkap semua role yang diizinkan (e.g., 'admin', 'dokter')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah pengguna sudah login. Jika belum, tendang.
        //    Meskipun sudah ada middleware 'auth', ini adalah lapisan keamanan tambahan.
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil data pengguna yang sedang login.
        $user = Auth::user();

        // 3. Loop melalui semua role yang diizinkan yang dikirim dari route.
        foreach ($roles as $role) {
            // 4. Periksa apakah role pengguna SAMA DENGAN salah satu role yang diizinkan.
            if ($user->role == $role) {
                // 5. Jika cocok, IZINKAN pengguna untuk melanjutkan.
                return $next($request);
            }
        }

        // 6. Jika setelah semua pengecekan tidak ada yang cocok,
        //    artinya pengguna tidak punya izin. TOLAK aksesnya.
        abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
    }
}
