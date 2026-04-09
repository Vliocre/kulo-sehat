<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Artikel - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .page-bg {
            background-color: #f5faf7;
            background-image:
                radial-gradient(18% 24% at 15% 18%, rgba(52, 211, 153, 0.08), transparent 50%),
                radial-gradient(22% 26% at 82% 10%, rgba(34, 197, 94, 0.07), transparent 48%),
                linear-gradient(135deg, #f9fdfb 0%, #edf6f1 45%, #e9f3ef 100%);
        }
    </style>
</head>
<body class="page-bg font-sans text-gray-900 antialiased min-h-screen">

    <x-public-navbar />

    <main class="pt-32 pb-20">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-gray-400 font-semibold">Kumpulan Artikel</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Artikel Berdasarkan Kategori BMI</h1>
                    <p class="mt-3 max-w-3xl text-sm text-gray-600">
                        Pilih artikel sesuai hasil BMI Anda: kurus, ideal, gemuk, atau obesitas.
                    </p>
                </div>
            </div>

            @if ($fromCalculator && $selectedCategory)
                <div class="mt-6 rounded-[28px] border border-emerald-100 bg-gradient-to-r from-emerald-50 to-lime-50 px-6 py-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Rekomendasi Dari Kalkulator BMI</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Mulai dari artikel kategori {{ $selectedCategory->name }}</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Hasil BMI Anda mengarah ke kategori ini. Baca artikel yang paling relevan untuk kondisi Anda sekarang.
                    </p>
                </div>
            @endif

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('articles.public.index', array_filter(['search' => $search], fn ($value) => !is_null($value) && $value !== '')) }}"
                   class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition {{ $selectedCategory ? 'bg-white text-gray-700 ring-1 ring-gray-200 hover:ring-emerald-200' : 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' }}">
                    Semua
                </a>
                @foreach ($bmiCategories as $category)
                    <a href="{{ route('articles.public.index', array_filter(['category' => $category->slug, 'search' => $search], fn ($value) => !is_null($value) && $value !== '')) }}"
                       class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition {{ optional($selectedCategory)->id === $category->id ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'bg-white text-gray-700 ring-1 ring-gray-200 hover:text-emerald-700 hover:ring-emerald-200' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            @if (!empty($search))
                <div class="mt-4 text-sm text-gray-600">
                    Menampilkan hasil untuk: <span class="font-semibold text-gray-900">"{{ $search }}"</span>
                </div>
            @endif

            @if ($selectedCategory)
                <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                    <span>Filter kategori:</span>
                    <span class="font-semibold text-gray-900">{{ $selectedCategory->name }}</span>
                    <a href="{{ route('articles.public.index', array_filter(['search' => $search], fn ($value) => !is_null($value) && $value !== '')) }}" class="text-green-600 hover:text-green-700 text-xs font-semibold uppercase tracking-wide">Hapus Filter</a>
                </div>
            @endif

            <div class="mt-10 grid gap-8 md:grid-cols-2">
                @forelse ($articles as $article)
                    <article class="bg-white rounded-[28px] shadow-[0_20px_40px_rgba(15,118,110,0.1)] overflow-hidden flex flex-col">
                        @if ($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="h-56 w-full object-cover">
                        @endif
                        <div class="p-6 flex flex-col flex-1">
                            <p class="text-xs uppercase tracking-widest text-green-600 font-semibold">
                                {{ optional($article->category)->name ?? 'Kesehatan' }}
                            </p>
                            <h2 class="mt-2 text-xl font-semibold text-gray-900 leading-snug">
                                <a href="{{ route('articles.public.show', $article->slug) }}" class="hover:text-green-600 transition">
                                    {{ $article->title }}
                                </a>
                            </h2>
                            <p class="mt-3 text-sm text-gray-600 line-clamp-3">
                                {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 150) }}
                            </p>
                            @if ($article->category)
                                <div class="mt-4">
                                    <a href="{{ route('articles.public.index', ['category' => $article->category->slug]) }}"
                                       class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                        Kategori {{ $article->category->name }}
                                    </a>
                                </div>
                            @endif
                            <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $article->created_at->isoFormat('D MMM YYYY') }}</span>
                                <a href="{{ route('articles.public.show', $article->slug) }}" class="text-green-600 font-semibold hover:text-green-700">
                                    Baca Selengkapnya ->
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-20 text-gray-500">
                        Belum ada artikel yang tersedia untuk kategori ini.
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $articles->appends(request()->only('search', 'category', 'from'))->links() }}
            </div>
        </div>
    </main>

</body>
</html>
