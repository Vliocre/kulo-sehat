@extends('layouts.admin')

@section('title', 'Konten Topik')
@section('header-subtitle', 'Kelola teks gejala, perawatan, pencegahan')
@section('header-title', 'Konten Topik')

@section('content')
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Panduan Topik</h2>
            <p class="text-sm text-gray-500">Hanya admin yang bisa menambah/mengubah konten halaman topik (mis. Bayi → Flu).</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.topic-guides.create') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                Tambah Panduan
            </a>
        </div>
    </div>

    <div class="rounded-3xl bg-white shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-widest text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left">Kategori</th>
                        <th class="px-5 py-3 text-left">Topik</th>
                        <th class="px-5 py-3 text-left">Judul</th>
                        <th class="px-5 py-3 text-left">Diperbarui</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($guides as $guide)
                        <tr>
                            <td class="px-5 py-4 text-gray-900">{{ $categories[$guide->category_slug] ?? $guide->category_slug }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $guide->topic_slug }}</td>
                            <td class="px-5 py-4 text-gray-800">{{ Str::limit($guide->title, 60) }}</td>
                            <td class="px-5 py-4 text-gray-500">{{ $guide->updated_at?->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-3 text-sm font-semibold">
                                    <a href="{{ route('admin.topic-guides.edit', $guide) }}" class="text-emerald-600 hover:text-emerald-800">Edit</a>
                                    <form action="{{ route('admin.topic-guides.destroy', $guide) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus panduan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-gray-500">Belum ada panduan topik. Tambahkan untuk mengganti konten statis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $guides->links() }}
        </div>
    </div>
@endsection
