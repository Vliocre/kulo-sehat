<?php

// Route::get('/test', function () {
//     return response()->json(['message' => 'API OK']);
// });

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\KeluhanController;
use App\Http\Controllers\Api\TopicGuideController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'google']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
    
    // Articles
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/category/{categoryId}', [ArticleController::class, 'byCategory']);
    Route::get('/articles/search', [ArticleController::class, 'search']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

    // Topic guides
    Route::get('/topic-categories', [TopicGuideController::class, 'categories']);
    Route::get('/topics', [TopicGuideController::class, 'index']);
    Route::get('/topics/{categorySlug}', [TopicGuideController::class, 'byCategory']);
    Route::get('/topics/{categorySlug}/{topicSlug}', [TopicGuideController::class, 'show']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/user-dashboard', [DashboardController::class, 'userDashboard']);
    
    // History
    Route::get('/history', [HistoryController::class, 'index']);
    Route::get('/history/keluhan/{id}', [HistoryController::class, 'showKeluhan']);
    
    // Keluhan (Complaints)
    Route::get('/keluhan', [KeluhanController::class, 'index']);
    Route::post('/keluhan', [KeluhanController::class, 'store']);
    Route::get('/keluhan/{id}', [KeluhanController::class, 'show']);
    Route::put('/keluhan/{id}', [KeluhanController::class, 'update']);
    Route::delete('/keluhan/{id}', [KeluhanController::class, 'destroy']);
});
