@extends('layouts.admin')

@section('title', 'Kelola Artikel')
@section('header-subtitle', 'Panel Editorial')
@section('header-title', 'Kelola Artikel')

@section('content')
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Artikel</h2>
                <p class="text-sm text-gray-500">Pantau dan perbarui artikel yang sudah terbit atau masih draft.</p>
            </div>
            <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                Tulis Artikel Baru
            </a>
        </div>

        <div class="rounded-3xl bg-white shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-widest text-gray-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Judul</th>
                            <th class="px-5 py-3 text-left">Penulis</th>
                            <th class="px-5 py-3 text-left">Kategori</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($articles as $article)
                            <tr>
                                <td class="px-5 py-4 text-gray-900">{{ Str::limit($article->title, 60) }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $article->author->name ?? 'N/A' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $article->category->name ?? 'Tidak ada' }}</td>
                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex items-center gap-3 text-sm font-semibold">
                                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-emerald-600 hover:text-emerald-800">Edit</a>
                                        <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-gray-500">Tidak ada artikel ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
@endsection
