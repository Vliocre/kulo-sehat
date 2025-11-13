<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalDoctors = User::where('role', 'dokter')->count();
        $totalArticles = Article::count();
        $totalCategories = Category::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalDoctors',
            'totalArticles',
            'totalCategories'
        ));
    }
}
