<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;

// Import Controller
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryLandingController;
use App\Http\Controllers\TopicLandingController;
use App\Http\Controllers\TopicsExplorerController;
use App\Http\Controllers\AuthorProfileController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\ArticleController as DoctorArticleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\TopicGuideController as AdminTopicGuideController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// RUTE PUBLIK
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/artikel', [ArticleController::class, 'index'])->middleware('auth')->name('articles.public.index');
Route::get('/artikel/{article:slug}', [ArticleController::class, 'show'])->middleware('auth')->name('articles.public.show');
Route::get('/kategori/{slug}', [CategoryLandingController::class, 'show'])->middleware('auth')->name('categories.show');
Route::get('/kategori/{category}/{topic}', [TopicLandingController::class, 'show'])->middleware('auth')->name('topics.show');
Route::get('/panduan-topik', [TopicsExplorerController::class, 'index'])->middleware('auth')->name('topics.all');
Route::get('/penulis/{user}', [AuthorProfileController::class, 'show'])->name('authors.show');


// RUTE OTENTIKASI
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isDokter()) {
        return redirect()->route('doctor.dashboard');
    }

    // === PERUBAHAN DI SINI ===
    // Mengambil 3 artikel terbaru untuk dashboard pengguna, sama seperti beranda
    $latestArticles = Article::where('status', 'published')
                                ->latest()
                                ->take(3)
                                ->get();

    // Mengirim variabel $latestArticles (bukan $articles)
    return view('dashboard', compact('latestArticles'));
    // === AKHIR PERUBAHAN ===

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// RUTE KHUSUS DOKTER
Route::middleware(['auth', 'verified', 'role:dokter'])
    ->prefix('doctor')
    ->as('doctor.')
    ->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::resource('articles', DoctorArticleController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/dokter/dashboard', function () {
        return view('doctor.dashboard');
    })->name('dokter.dashboard');
}); 



// RUTE KHUSUS ADMIN
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserManagementController::class);
        Route::resource('articles', AdminArticleController::class);
        Route::post('topic-guides/import-defaults', [AdminTopicGuideController::class, 'importDefaults'])->name('topic-guides.import-defaults');
        Route::resource('topic-guides', AdminTopicGuideController::class)->except(['show']);
});


// Memuat route bawaan Breeze
require __DIR__.'/auth.php';
