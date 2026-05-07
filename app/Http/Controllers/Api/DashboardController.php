<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Keluhan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Get dashboard data for mobile
     */
    public function index(Request $request)
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

            $popularArticlesQuery = Article::with('category')
                ->where('status', 'published');

            if (Schema::hasColumn('articles', 'views')) {
                $popularArticlesQuery->orderBy('views', 'desc');
            } else {
                $popularArticlesQuery->orderBy('created_at', 'desc');
            }
            
            $data = [
                'statistics' => [
                    'total_articles' => Article::where('status', 'published')->count(),
                    'total_categories' => Category::count(),
                    'total_users' => User::count(),
                ],
                'latest_articles' => Article::with('category')
                    ->where('status', 'published')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
                'popular_articles' => $popularArticlesQuery
                    ->limit(5)
                    ->get(),
                'categories' => Category::withCount([
                    'articles' => fn ($query) => $query->where('status', 'published'),
                ])->limit(6)->get(),
                'user_dashboard' => [
                    'total_keluhan' => Keluhan::where('user_id', $user->id)->count(),
                    'total_read_articles' => Schema::hasTable('article_views')
                        ? DB::table('article_views')->where('user_id', $user->id)->count()
                        : 0
                ]
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dashboard: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get user dashboard (keluhan history)
     */
    public function userDashboard(Request $request)
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
            
            $keluhan = Keluhan::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $keluhan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
}
