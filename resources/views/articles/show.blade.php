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
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-emerald-50 via-white to-lime-50 min-h-screen">

    <x-public-navbar />

    <main class="pt-28 pb-20">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 grid lg:grid-cols-[minmax(0,2fr)_320px] gap-10 lg:gap-12 items-start">
            <article class="bg-white/95 rounded-[32px] shadow-[0_30px_70px_rgba(16,185,129,0.15)] ring-1 ring-emerald-50 p-8 md:p-12 space-y-8">
                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                    @if ($article->category)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full ring-1 ring-emerald-100">
                            {{ $article->category->name }}
                        </span>
                    @endif
                    <span class="flex items-center gap-1">
                        <span class="text-gray-500">Penulis:</span>
                        @if ($article->author)
                            <a href="{{ route('authors.show', $article->author->id) }}" class="font-semibold text-emerald-700 hover:text-emerald-800">
                                {{ $article->author->name }}
                            </a>
                        @else
                            <span class="font-semibold text-gray-800">KuloSehat Editorial</span>
                        @endif
                    </span>
                    <span class="text-gray-500">
                        {{ $article->created_at->isoFormat('dddd, D MMMM YYYY, HH:mm') }} WIB
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl font-bold leading-tight text-slate-900">
                    {{ $article->title }}
                </h1>

                @if ($article->image)
                    <div class="overflow-hidden rounded-[26px] shadow-[0_22px_60px_rgba(15,118,110,0.16)] ring-1 ring-emerald-50">
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-[360px] md:h-[460px] object-cover">
                    </div>
                @endif

                <div class="prose prose-lg max-w-none prose-headings:text-slate-900 prose-p:text-slate-700 prose-a:text-emerald-700 prose-strong:text-slate-900 leading-relaxed">
                    {!! $article->content !!}
                </div>

                @if ($recommendedArticles->isNotEmpty())
                    <div class="pt-6 border-t border-emerald-50">
                        <p class="text-sm uppercase tracking-[0.25em] text-emerald-700 font-semibold">Baca Juga</p>
                        <div class="mt-3 grid sm:grid-cols-2 gap-3">
                            @foreach ($recommendedArticles->take(4) as $related)
                                <a href="{{ route('articles.public.show', $related->slug) }}" class="flex items-start gap-3 rounded-2xl border border-emerald-50 bg-emerald-50/40 px-3 py-2 hover:border-emerald-200 hover:bg-emerald-50 transition">
                                    <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-white text-emerald-700 font-semibold text-xs shadow-sm">{{ $loop->iteration }}</span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 leading-snug">{{ $related->title }}</p>
                                        <p class="text-xs text-slate-500">{{ $related->created_at->isoFormat('D MMM YYYY') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <aside class="space-y-6">
                <div class="bg-white/95 rounded-[24px] shadow-[0_20px_50px_rgba(16,185,129,0.12)] ring-1 ring-emerald-50 p-6 sticky top-28">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Disarankan untuk Anda</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($recommendedArticles as $index => $recommended)
                            <a href="{{ route('articles.public.show', $recommended->slug) }}" class="flex gap-3 p-3 rounded-2xl hover:bg-emerald-50 transition border border-transparent hover:border-emerald-100">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-semibold text-sm">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 leading-snug">
                                        {{ $recommended->title }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
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

    <footer class="relative mt-16 bg-slate-900 text-slate-100">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(16,185,129,0.08),transparent_35%),radial-gradient(circle_at_80%_10%,rgba(74,222,128,0.08),transparent_35%)]"></div>
        <div class="relative max-w-6xl mx-auto px-6 lg:px-8 pt-14 pb-10 grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="space-y-3">
                <div class="text-2xl font-bold text-white">Kulo<span class="text-emerald-300">Sehat.</span></div>
                <p class="text-sm text-slate-200">Informasi kesehatan ringkas dan terpercaya untuk setiap tahap usia.</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 rounded-full bg-white/10 text-emerald-100 text-xs font-semibold">Bayi</span>
                    <span class="px-3 py-1 rounded-full bg-white/10 text-emerald-100 text-xs font-semibold">Remaja</span>
                    <span class="px-3 py-1 rounded-full bg-white/10 text-emerald-100 text-xs font-semibold">Dewasa</span>
                    <span class="px-3 py-1 rounded-full bg-white/10 text-emerald-100 text-xs font-semibold">Lansia</span>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-bold text-white mb-3 uppercase tracking-wide">Bantuan</h4>
                <ul class="space-y-2 text-sm text-slate-200">
                    <li><a href="#" class="hover:text-emerald-200">Pusat Bantuan</a></li>
                    <li><a href="#" class="hover:text-emerald-200">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-emerald-200">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-bold text-white mb-3 uppercase tracking-wide">KuloSehat</h4>
                <ul class="space-y-2 text-sm text-slate-200">
                    <li><a href="#" class="hover:text-emerald-200">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-emerald-200">Artikel Terbaru</a></li>
                    <li><a href="#" class="hover:text-emerald-200">Tim Medis</a></li>
                    <li><a href="#" class="hover:text-emerald-200">Kontak</a></li>
                </ul>
            </div>

            <div class="space-y-2 text-sm text-slate-200">
                <h4 class="text-sm font-bold text-white mb-3 uppercase tracking-wide">Kontak</h4>
                <p>help@kulosehat.com</p>
                <p>021-5095-9900</p>
                <div class="flex gap-3 pt-2">
                    <span class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center text-emerald-200 text-xs font-semibold">IG</span>
                    <span class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center text-emerald-200 text-xs font-semibold">YT</span>
                    <span class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center text-emerald-200 text-xs font-semibold">FB</span>
                </div>
            </div>
        </div>
        <div class="relative border-t border-white/10">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 py-4 text-xs text-slate-300 flex flex-col sm:flex-row justify-between gap-2">
                <span>Ac {{ date('Y') }} KuloSehat. All rights reserved.</span>
                <span class="text-emerald-200 font-semibold">Keamanan & Privasi</span>
            </div>
        </div>
    </footer>

</body>
</html>
