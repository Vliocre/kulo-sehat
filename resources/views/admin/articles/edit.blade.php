@extends('layouts.admin')

@section('title', 'Edit Artikel')
@section('header-subtitle', 'Panel Editorial')
@section('header-title', 'Edit Artikel')

@section('content')
    <div class="max-w-4xl mx-auto rounded-3xl bg-white shadow-lg border border-gray-100 p-8 space-y-6">
        <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="text-sm font-semibold text-gray-700">Judul Artikel</label>
                <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="category_id" class="text-sm font-semibold text-gray-700">Kategori</label>
                <select name="category_id" id="category_id" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="content" class="text-sm font-semibold text-gray-700">Konten</label>
                <textarea name="content" id="content" rows="10" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>{{ old('content', $article->content) }}</textarea>
                @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Gambar Saat Ini</label>
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="Gambar saat ini" class="w-48 h-auto object-cover rounded-2xl my-3">
                @else
                    <p class="text-sm text-gray-500">Tidak ada gambar.</p>
                @endif
                <label for="image" class="text-sm font-semibold text-gray-700 mt-2 block">Ubah Gambar</label>
                <input type="file" name="image" id="image" class="mt-2 w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100" />
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.articles.index') }}" class="rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-600 hover:border-gray-300">Batal</a>
                <button type="submit" class="rounded-full bg-emerald-500 px-6 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-600">Perbarui Artikel</button>
            </div>
        </form>
    </div>
@endsection
