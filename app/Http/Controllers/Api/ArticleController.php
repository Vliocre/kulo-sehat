<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
class ArticleController extends Controller
{
    /**
     * Get all articles
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $category = $request->get('category');
            
            $query = Article::with('category')->where('status', 'published');
            
            if ($category) {
                $query->where('category_id', $category);
            }
            
            $articles = $query->orderBy('created_at', 'desc')->paginate($limit);
            
            return response()->json([
                'success' => true,
                'data' => $articles->items(),
                'meta' => [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil artikel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single article
     */
    public function show($id)
    {
        try {
            /** @var Article $article */
            $article = Article::with('category')
                ->where('status', 'published')
                ->findOrFail($id);
            
            if (Schema::hasColumn('articles', 'views')) {
                $article->increment('views');
            }
            
            return response()->json([
                'success' => true,
                'data' => $article
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get articles by category
     */
    public function byCategory($categoryId)
    {
        try {
            $articles = Article::with('category')
                ->where('category_id', $categoryId)
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $articles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil artikel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search articles
     */
    public function search(Request $request)
    {
        try {
            $keyword = $request->get('q');
            
            $articles = Article::with('category')
                ->where('status', 'published')
                ->where(function ($query) use ($keyword) {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('content', 'like', "%{$keyword}%");
                })
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $articles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari artikel: ' . $e->getMessage()
            ], 500);
        }
    }
}
