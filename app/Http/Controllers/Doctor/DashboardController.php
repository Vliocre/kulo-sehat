<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mengambil data user yg login
use App\Models\Article; // Untuk menghitung artikel

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Ambil nama lengkap user dari database
        $userName = Auth::user()->name;

        // Hitung jumlah artikel yang dibuat oleh user ini
        $totalArticles = Article::where('user_id', $userId)->count();

        // Ambil artikel terbaru (publik) untuk ditampilkan seperti dashboard pengguna
        $latestArticles = Article::where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        // Kirim semua data yang kita dapat ke view
        return view('doctor.dashboard', compact('userName', 'totalArticles', 'latestArticles'));
    }
}
