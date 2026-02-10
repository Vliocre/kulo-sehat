<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Total artikel milik dokter
        $totalArticles = Article::where('user_id', $user->id)->count();

        // Artikel terbaru milik dokter (untuk ringkasan dashboard)
        $latestDoctorArticles = Article::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Artikel publik terbaru (status published)
        $latestPublicArticles = Article::with('category')
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        return view('doctor.dashboard', compact(
            'user',
            'totalArticles',
            'latestDoctorArticles',
            'latestPublicArticles'
        ));
    }
}
