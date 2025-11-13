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
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50 min-h-screen">

    <x-public-navbar />

    <main class="pt-32 pb-20">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-gray-400 font-semibold">Kumpulan Artikel</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Temukan Topik Kesehatan Favorit Anda</h1>
                </div>
            </div>

            @if (!empty($search))
                <div class="mt-4 text-sm text-gray-600">
                    Menampilkan hasil untuk: <span class="font-semibold text-gray-900">"{{ $search }}"</span>
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
                            <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $article->created_at->isoFormat('D MMM YYYY') }}</span>
                                <a href="{{ route('articles.public.show', $article->slug) }}" class="text-green-600 font-semibold hover:text-green-700">
                                    Baca Selengkapnya →
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-20 text-gray-500">
                        Belum ada artikel yang tersedia.
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $articles->appends(['search' => $search])->links() }}
            </div>
        </div>
    </main>

</body>
</html>
