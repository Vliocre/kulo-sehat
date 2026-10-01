<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Models\Keluhan;

// Import Controller
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryLandingController;
use App\Http\Controllers\TopicLandingController;
use App\Http\Controllers\TopicsExplorerController;
use App\Http\Controllers\AuthorProfileController;
use App\Models\TopicGuide;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\ArticleController as DoctorArticleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\TopicGuideController as AdminTopicGuideController;
use App\Http\Controllers\KalkulatorController;

// RUTE PUBLIK
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kalkulator', [KalkulatorController::class, 'index'])->name('kalkulator.public.index');
Route::post('/kalkulator', [KalkulatorController::class, 'calculate'])->name('kalkulator');
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

    if ($user->requiresDoctorApproval() && ! $user->canAccessDoctorArea()) {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors(['role' => 'Akun dokter Anda belum disetujui admin.']);
    }

    if ($user->isApprovedDoctor()) {
        return redirect()->route('doctor.dashboard');
    }

    $latestArticles = Article::where('status', 'published')
                                ->latest()
                                ->take(3)
                                ->get();
    $activePremiumConsultation = Keluhan::with('doctor:id,name,specialty')
        ->where('user_id', $user->id)
        ->where('jenis', 'premium')
        ->whereIn('premium_status', ['requested', 'active'])
        ->latest()
        ->first();
    // Ambil kategori topik yang tersedia di database
    $allSlugs = TopicGuide::distinct()->pluck('category_slug')->all();
    $categoriesMap = [
        'bayi' => 'Bayi',
        'remaja' => 'Remaja',
        'dewasa' => 'Dewasa',
        'lansia' => 'Lansia',
    ];
    $topicCategories = [];
    foreach ($allSlugs as $s) {
        $topicCategories[$s] = $categoriesMap[$s] ?? ucfirst($s);
    }

    // Mengirim variabel ke view
    return view('dashboard', compact(
        'latestArticles',
        'topicCategories',
        'activePremiumConsultation'
    ));
    // === AKHIR PERUBAHAN ===

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/artikel-tersimpan', [ProfileController::class, 'savedArticles'])->name('profile.saved-articles');
    Route::get('/profile/gejala-penyakit', [ProfileController::class, 'symptomCategories'])->name('profile.symptoms');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/artikel/{article:slug}/simpan', [ArticleController::class, 'bookmark'])->name('articles.bookmark');
    Route::delete('/artikel/{article:slug}/simpan', [ArticleController::class, 'unbookmark'])->name('articles.unbookmark');
    Route::post('/kategori/{category}/{topic}/simpan', [ProfileController::class, 'bookmarkTopic'])->name('topics.bookmark');
    Route::delete('/kategori/{category}/{topic}/simpan', [ProfileController::class, 'unbookmarkTopic'])->name('topics.unbookmark');
});


// RUTE KHUSUS DOKTER
Route::middleware(['auth', 'verified', 'role:dokter'])
    ->prefix('doctor')
    ->as('doctor.')
    ->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::resource('articles', DoctorArticleController::class);
});
Route::middleware(['auth', 'verified', 'role:dokter'])->group(function () {
    Route::get('/dokter/dashboard', function () {
        return redirect()->route('doctor.dashboard');
    })->name('dokter.dashboard');
});



// RUTE KHUSUS ADMIN
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('users/{user}/approve-doctor', [UserManagementController::class, 'approveDoctor'])->name('users.approve-doctor');
        Route::post('users/{user}/reject-doctor', [UserManagementController::class, 'rejectDoctor'])->name('users.reject-doctor');
        Route::resource('users', UserManagementController::class);
        Route::resource('articles', AdminArticleController::class);
        Route::post('topic-guides/import-defaults', [AdminTopicGuideController::class, 'importDefaults'])->name('topic-guides.import-defaults');
        Route::resource('topic-guides', AdminTopicGuideController::class)->except(['show']);
});


// Memuat route bawaan Breeze
require __DIR__.'/auth.php';

// keluhan
use App\Http\Controllers\KeluhanController;

Route::middleware(['auth', 'verified', 'role:pengguna'])->group(function(){

    // USER
    Route::get('/keluhan', 
        [KeluhanController::class,'indexUser'])
        ->name('keluhan.index');

    Route::get('/pilih-dokter',
        [KeluhanController::class, 'indexDoctors'])
        ->name('doctors.index');

    Route::get('/daftar-premium',
        [KeluhanController::class, 'premiumRegistration'])
        ->name('premium.register');

    Route::post('/daftar-premium',
        [KeluhanController::class, 'activatePremium'])
        ->name('premium.activate');

    Route::post('/keluhan', 
        [KeluhanController::class,'store'])
        ->name('keluhan.store');

    Route::delete('/keluhan/{keluhan}',
        [KeluhanController::class, 'destroy'])
        ->name('keluhan.destroy');

    Route::post('/keluhan-premium/dokter/{doctor}',
        [KeluhanController::class, 'storePremium'])
        ->name('keluhan.premium.store');
});

// DOKTER
Route::middleware(['auth', 'verified', 'role:dokter'])->group(function () {
    Route::get('/dokter/keluhan',
        [KeluhanController::class, 'indexDokter'])
        ->name('dokter.keluhan');

    Route::post('/dokter/keluhan/{keluhan}',
        [KeluhanController::class, 'jawab'])
        ->name('dokter.keluhan.jawab');

    Route::patch('/dokter/keluhan-premium/{keluhan}/terima',
        [KeluhanController::class, 'acceptPremium'])
        ->name('dokter.keluhan-premium.accept');

    Route::patch('/dokter/keluhan-premium/{keluhan}/tolak',
        [KeluhanController::class, 'rejectPremium'])
        ->name('dokter.keluhan-premium.reject');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/keluhan-premium/{keluhan}/pesan',
        [KeluhanController::class, 'sendPremiumMessage'])
        ->name('keluhan.premium.message');

    Route::get('/keluhan-premium/{keluhan}/lampiran/{message}',
        [KeluhanController::class, 'premiumAttachment'])
        ->name('keluhan.premium.attachment');

    Route::patch('/keluhan-premium/{keluhan}/selesai',
        [KeluhanController::class, 'closePremium'])
        ->name('keluhan.premium.close');
});

// ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/keluhan',
        [KeluhanController::class, 'indexAdmin'])
        ->name('admin.keluhan');

    Route::get('/admin/keluhan/{keluhan}',
        [KeluhanController::class, 'showAdmin'])
        ->name('admin.keluhan.show');

    Route::get('/admin/keluhan/{keluhan}/edit',
        [KeluhanController::class, 'editAdmin'])
        ->name('admin.keluhan.edit');

    Route::put('/admin/keluhan/{keluhan}',
        [KeluhanController::class, 'updateAdmin'])
        ->name('admin.keluhan.update');

    Route::delete('/admin/keluhan/{keluhan}',
        [KeluhanController::class, 'destroyAdmin'])
        ->name('admin.keluhan.destroy');
});
