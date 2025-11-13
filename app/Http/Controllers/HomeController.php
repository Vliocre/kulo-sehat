<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article; // Import model Article

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 3 artikel terbaru yang statusnya 'published'
        $latestArticles = Article::where('status', 'published')
                                ->with('category') // Mengambil data kategori untuk efisiensi
                                ->latest()         // Urutkan dari yang paling baru
                                ->take(3)          // Ambil hanya 3
                                ->get();

        // Kirim variabel $latestArticles ke dalam view
        return view('welcome', compact('latestArticles'));
    }
}
