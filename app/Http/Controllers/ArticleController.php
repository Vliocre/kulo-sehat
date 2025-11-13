<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Menampilkan halaman daftar semua artikel publik.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $articlesQuery = Article::where('status', 'published')
            ->with(['author', 'category'])
            ->latest();

        if ($search) {
            $articlesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $articles = $articlesQuery->paginate(9)->withQueryString();

        return view('articles.index', compact('articles', 'search'));
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
