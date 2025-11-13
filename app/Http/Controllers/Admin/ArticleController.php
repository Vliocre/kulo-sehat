<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mengambil ID admin yang membuat
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Untuk membuat slug

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar SEMUA artikel.
     */
    public function index()
    {
        $articles = Article::with('author', 'category')->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * === METHOD BARU 1 ===
     * Menampilkan form untuk membuat artikel baru.
     */
    public function create()
    {
        $categories = Category::all(); // Ambil semua kategori untuk dropdown
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * === METHOD BARU 2 ===
     * Menyimpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'title' => 'required|string|max:255|unique:articles',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        // 2. Handle upload gambar
        $imagePath = $request->file('image')->store('articles', 'public');

        // 3. Simpan ke database
        Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title), // Buat slug otomatis
            'content' => $request->content,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(), // ID dari admin yang sedang login
            'status' => 'published', // Langsung publish
        ]);

        // 4. Redirect dengan pesan sukses
        return redirect()->route('admin.articles.index')->with('success', 'Artikel baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit artikel.
     */
    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Memperbarui artikel di database.
     */
    public function update(Request $request, Article $article)
    {
        // ... (kode update biarkan seperti sebelumnya) ...
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
        $data = $request->only(['title', 'category_id', 'content']);
        if ($request->hasFile('image')) {
            if ($article->image) { Storage::disk('public')->delete($article->image); }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }
        $article->update($data);
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Menghapus artikel dari database.
     */
    public function destroy(Article $article)
    {
        // ... (kode destroy biarkan seperti sebelumnya) ...
        if ($article->image) { Storage::disk('public')->delete($article->image); }
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
