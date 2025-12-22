<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panduan Topik - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-white via-emerald-50 to-lime-50">
    <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_15%_20%,rgba(16,185,129,0.08),transparent_35%),radial-gradient(circle_at_85%_10%,rgba(52,211,153,0.08),transparent_30%),radial-gradient(circle_at_50%_80%,rgba(16,185,129,0.06),transparent_32%)]"></div>

    <x-public-navbar />

    <main class="pt-24 pb-16">
        <section class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="rounded-[32px] bg-white shadow-[0_26px_70px_rgba(15,23,42,0.12)] ring-1 ring-emerald-50 p-8 lg:p-10 relative overflow-hidden">
                <div class="absolute -left-10 -top-16 h-32 w-32 rounded-full bg-emerald-200/30 blur-3xl"></div>
                <div class="absolute -right-6 -bottom-12 h-28 w-28 rounded-full bg-emerald-100/50 blur-2xl"></div>
                <div class="relative space-y-3">
                    <p class="text-xs uppercase tracking-[0.25em] text-emerald-700 font-semibold">Panduan Topik</p>
                    <h1 class="text-3xl lg:text-4xl font-bold text-slate-900 leading-tight">Semua Topik Penyakit</h1>
                    <p class="text-slate-600 max-w-3xl">Pilih topik dari kategori mana pun. Setiap kartu membawa Anda ke panduan gejala, langkah perawatan singkat, dan pencegahan.</p>
                    <div class="flex flex-wrap gap-3 text-xs uppercase tracking-wide text-slate-500">
                        @foreach ($categories as $slug => $name)
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold ring-1 ring-emerald-100">{{ $name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-6xl mx-auto px-6 lg:px-8 mt-10 lg:mt-14 space-y-10">
            @foreach ($categories as $slug => $name)
                @php
                    $items = $topicsByCategory[$slug] ?? [];
                @endphp
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">{{ $name }}</p>
                            <h2 class="text-2xl font-bold text-slate-900">Topik {{ $name }}</h2>
                        </div>
                        <a href="{{ route('categories.show', $slug) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700">
                            Lihat kategori
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($items as $topicSlug => $topic)
                            <a href="{{ route('topics.show', ['category' => $slug, 'topic' => $topicSlug]) }}"
                               class="group relative h-full rounded-3xl bg-gradient-to-br from-emerald-50 via-white to-emerald-100 border border-white/70 shadow-[0_18px_40px_rgba(0,0,0,0.08)] p-6 flex flex-col gap-4 transition hover:-translate-y-1 hover:shadow-[0_30px_70px_rgba(16,185,129,0.18)] overflow-hidden">
                                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/40 blur-2xl"></div>
                                <div class="relative inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 text-gray-800 text-[11px] font-semibold tracking-wide shadow-sm ring-1 ring-white">
                                    {{ $name }}
                                </div>
                                <div class="relative">
                                    <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2">Topik {{ $topic['title'] ?? Str::headline($topicSlug) }}</h3>
                                    @if (!empty($topic['summary']))
                                        <p class="text-sm text-gray-700 mb-4">{{ Str::limit($topic['summary'], 140) }}</p>
                                    @endif
                                </div>
                                <div class="relative mt-auto flex items-center gap-2 text-emerald-700 font-semibold text-sm">
                                    Lihat artikel
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-3 text-sm text-slate-500">Belum ada topik untuk kategori ini.</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </section>
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
