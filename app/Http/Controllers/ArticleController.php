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
        $bmiCategories = Category::forBmi()
            ->get()
            ->sortBy(fn ($category) => array_search($category->slug, Category::bmiSlugs(), true))
            ->values();

        $articlesQuery = Article::where('status', 'published')
            ->with(['author', 'category'])
            ->whereHas('category', fn ($query) => $query->forBmi())
            ->latest();

        if ($categorySlug) {
            $selectedCategory = $bmiCategories->firstWhere('slug', $categorySlug);

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
        $fromCalculator = $request->query('from') === 'kalkulator';

        return view('articles.index', compact('articles', 'search', 'selectedCategory', 'bmiCategories', 'fromCalculator'));
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
            ->when($article->category_id, function ($query) use ($article) {
                $query->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$article->category_id]);
            })
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('articles.show', compact('article', 'recommendedArticles'));
    }
}
