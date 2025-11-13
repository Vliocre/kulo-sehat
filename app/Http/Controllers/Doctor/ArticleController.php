<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        // Tampilkan hanya artikel milik dokter yang sedang login
        $articles = Article::where('user_id', Auth::id())->latest()->paginate(10);
        return view('doctor.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('doctor.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'content' => 'required|string',
        ]);

        // 2. Simpan Gambar
        $imagePath = $request->file('image')->store('articles', 'public');

        // 3. Buat Artikel
        Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(), // ID dari dokter yang login
            'status' => 'published',
        ]);

        return redirect()->route('doctor.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        $this->ensureOwnedByDoctor($article);
        $categories = Category::all();

        return view('doctor.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $this->ensureOwnedByDoctor($article);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'content' => 'required|string',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('doctor.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $this->ensureOwnedByDoctor($article);

        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('doctor.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    protected function ensureOwnedByDoctor(Article $article): void
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
