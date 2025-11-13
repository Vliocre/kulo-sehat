<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tulis Artikel Dokter - KuloSehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        .form-card {
            border-radius: 32px;
            box-shadow: 0 25px 45px rgba(15, 118, 110, 0.12);
            background: rgba(255, 255, 255, 0.98);
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-emerald-100 via-white to-lime-100">

    <x-public-navbar />

    <main class="min-h-screen pb-16">
        <section class="relative pt-28 sm:pt-32 md:pt-36 pb-10 overflow-hidden">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="form-card px-6 py-10 sm:px-10 md:px-14">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-gray-400 font-semibold">Panel Dokter</p>
                            <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Tulis Artikel Baru</h1>
                            <p class="text-sm text-gray-500 mt-1">Bagikan wawasan medis Anda untuk membantu pasien lainnya.</p>
                        </div>
                        <a href="{{ route('doctor.articles.index') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:border-green-300 hover:text-green-600 transition">
                            Kelola Artikel Saya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5l6 6-6 6M4.5 12h15"/></svg>
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('doctor.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label for="title" class="text-sm font-semibold text-gray-700">Judul Artikel</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>
                        </div>

                        <div>
                            <label for="category_id" class="text-sm font-semibold text-gray-700">Kategori</label>
                            <select name="category_id" id="category_id" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>
                                <option value="">Pilih kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="image" class="text-sm font-semibold text-gray-700">Gambar Utama</label>
                            <input type="file" name="image" id="image" class="mt-2 w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>

                        <div>
                            <label for="content" class="text-sm font-semibold text-gray-700">Isi Artikel</label>
                            <textarea name="content" id="content" rows="10" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>{{ old('content') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('doctor.articles.index') }}" class="rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-600 hover:border-gray-300">Batal</a>
                            <button type="submit" class="rounded-full bg-green-500 px-6 py-2 text-sm font-semibold text-white shadow-lg shadow-green-500/30 hover:bg-green-600">Publikasikan Artikel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
