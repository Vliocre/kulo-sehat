<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-green-100 via-white to-lime-100 min-h-screen">

    <x-public-navbar />

    <main class="pt-32 pb-20">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 grid lg:grid-cols-[minmax(0,2fr)_320px] gap-12">
            <article class="bg-white rounded-[32px] shadow-[0_35px_70px_rgba(15,118,110,0.15)] p-8 md:p-12">
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                    @if ($article->category)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                            {{ $article->category->name }}
                        </span>
                    @endif
                    <span>Penulis: <span class="font-semibold text-gray-800">{{ optional($article->author)->name ?? 'KuloSehat Editorial' }}</span></span>
                    <span>{{ $article->created_at->isoFormat('dddd, D MMMM YYYY, HH:mm') }} WIB</span>
                </div>

                <h1 class="mt-6 text-3xl md:text-4xl font-bold leading-tight text-gray-900">
                    {{ $article->title }}
                </h1>

                @if ($article->image)
                    <div class="mt-8 overflow-hidden rounded-3xl">
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-[360px] md:h-[420px] object-cover">
                    </div>
                @endif

                <div class="mt-10 prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-green-600">
                    {!! $article->content !!}
                </div>

                @if ($recommendedArticles->isNotEmpty())
                    <div class="mt-12 border-t border-gray-100 pt-8">
                        <p class="text-sm uppercase tracking-[0.35em] text-gray-400 font-semibold">Baca Juga</p>
                        <div class="mt-4 space-y-3">
                            @foreach ($recommendedArticles->take(1) as $related)
                                <a href="{{ route('articles.public.show', $related->slug) }}" class="inline-flex items-center gap-2 text-green-600 font-semibold hover:text-green-700 transition">
                                    {{ $related->title }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 4.5l6 6-6 6M4.5 12h15"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <aside class="space-y-6">
                <div class="bg-white rounded-[32px] shadow-[0_25px_50px_rgba(15,118,110,0.12)] p-6">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Disarankan untuk Anda</p>
                    <div class="mt-4 space-y-4">
                        @forelse ($recommendedArticles as $index => $recommended)
                            <a href="{{ route('articles.public.show', $recommended->slug) }}" class="flex gap-4 p-3 rounded-2xl hover:bg-green-50 transition">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600 font-semibold text-sm">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 leading-snug">
                                        {{ $recommended->title }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $recommended->created_at->isoFormat('D MMM YYYY') }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada rekomendasi artikel lain.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </main>

</body>
</html>
