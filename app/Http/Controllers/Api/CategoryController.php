<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function index()
    {
        try {
            $categories = Category::withCount('articles')->get();
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single category with its articles
     */
    public function show($id)
    {
        try {
            $category = Category::with([
                'articles' => fn ($query) => $query
                    ->where('status', 'published')
                    ->orderBy('created_at', 'desc'),
            ])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
