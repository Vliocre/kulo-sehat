<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Artikel Dokter - KuloSehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        .hero-card {
            border-radius: 32px;
            box-shadow: 0 25px 45px rgba(15, 118, 110, 0.12);
            background: rgba(255, 255, 255, 0.98);
        }
        .table-card {
            border-radius: 32px;
            box-shadow: 0 25px 45px rgba(15, 118, 110, 0.08);
            background: rgba(255,255,255,0.98);
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-emerald-100 via-white to-lime-100">

    <x-public-navbar />

    <main class="min-h-screen pb-16">
        <section class="relative pt-28 sm:pt-32 md:pt-36 pb-10 overflow-hidden">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="hero-card px-6 py-10 sm:px-10 md:px-14">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-gray-400 font-semibold">Panel Dokter</p>
                            <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Kelola Artikel Saya</h1>
                            <p class="text-sm text-gray-500 mt-1">Sunting, hapus, atau terbitkan artikel untuk edukasi pasien.</p>
                        </div>
                        <a href="{{ route('doctor.articles.create') }}" class="inline-flex items-center gap-2 rounded-full bg-green-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-green-500/30 hover:bg-green-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                            Tambah Artikel Baru
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-card mt-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-xs uppercase tracking-widest text-gray-500">
                                <tr>
                                    <th class="px-6 py-4 text-left">Judul</th>
                                    <th class="px-6 py-4 text-left">Kategori</th>
                                    <th class="px-6 py-4 text-left">Tanggal Publikasi</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white/80">
                                @forelse ($articles as $article)
                                    <tr>
                                        <td class="px-6 py-4">{{ $article->title }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $article->category->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $article->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-center text-sm font-semibold space-x-3">
                                            <a href="{{ route('doctor.articles.edit', $article) }}" class="text-emerald-600 hover:text-emerald-800">Edit</a>
                                            <form action="{{ route('doctor.articles.destroy', $article) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus artikel ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Anda belum menulis artikel.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
