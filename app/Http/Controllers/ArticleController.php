<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Menampilkan halaman daftar semua artikel publik.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categorySlug = $request->query('category');
        $selectedCategory = null;

        $articlesQuery = Article::where('status', 'published')
            ->with(['author', 'category'])
            ->latest();

        if ($categorySlug) {
            $selectedCategory = Category::where('slug', $categorySlug)->first();

            if ($selectedCategory) {
                $articlesQuery->where('category_id', $selectedCategory->id);
            }
        }

        if ($search) {
            $articlesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $articles = $articlesQuery->paginate(9)->withQueryString();

        return view('articles.index', compact('articles', 'search', 'selectedCategory'));
    }

    /**
     * Menampilkan satu artikel secara detail.
     * Laravel akan otomatis mencari artikel berdasarkan slug.
     */
    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);

        $article->load(['author', 'category']);

        $recommendedArticles = Article::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->latest()
            ->take(5)
            ->get();

        return view('articles.show', compact('article', 'recommendedArticles'));
    }
}
