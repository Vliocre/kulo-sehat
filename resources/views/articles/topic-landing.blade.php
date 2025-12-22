<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Topik {{ $topic['title'] ?? 'Kesehatan' }} - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-white via-emerald-50 to-white relative">
    <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_15%_20%,rgba(16,185,129,0.08),transparent_35%),radial-gradient(circle_at_85%_10%,rgba(52,211,153,0.08),transparent_30%),radial-gradient(circle_at_50%_80%,rgba(16,185,129,0.06),transparent_32%)]"></div>

    <x-public-navbar :show-search="false" :active-category-slug="$categorySlug" />

    @php
        $symptoms = $topic['symptoms'] ?? [];
        $care = $topic['care'] ?? [];
        $prevention = $topic['prevention'] ?? [];
        $dangerSigns = $topic['danger_signs'] ?? [];
        $title = $topic['title'] ?? 'Topik Kesehatan';
        $summary = $topic['summary'] ?? '';
    @endphp

    <main class="pt-24 pb-16 relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(16,185,129,0.12),transparent_35%),radial-gradient(circle_at_80%_10%,rgba(74,222,128,0.12),transparent_30%),radial-gradient(circle_at_50%_70%,rgba(52,211,153,0.12),transparent_35%)]"></div>

        <section class="relative max-w-6xl mx-auto px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r {{ $palette ?? 'from-emerald-50 via-white to-emerald-100' }} shadow-[0_26px_80px_rgba(15,23,42,0.14)] ring-1 ring-white/70">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(16,185,129,0.12),transparent_40%),radial-gradient(circle_at_80%_0%,rgba(74,222,128,0.12),transparent_40%)]"></div>
                <div class="relative p-8 lg:p-12 flex flex-col gap-6">
                    <div class="flex flex-wrap items-center gap-3 text-sm text-emerald-800 font-semibold">
                        <a href="{{ route('categories.show', $categorySlug) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 ring-1 ring-emerald-100 hover:ring-emerald-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            {{ $categoryName }}
                        </a>
                        <span class="text-slate-500">/</span>
                        <span class="px-3 py-1 rounded-full bg-emerald-600 text-white ring-1 ring-emerald-500">Topik {{ $title }}</span>
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs uppercase tracking-[0.25em] text-emerald-700 font-semibold">Panduan Topik</p>
                        <h1 class="text-4xl lg:text-5xl font-bold leading-tight text-slate-900">{{ $title }}</h1>
                        @if ($summary)
                            <p class="text-lg text-slate-700 max-w-3xl">{{ $summary }}</p>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('articles.public.index', ['search' => $title]) }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                            Lihat artikel terkait
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="detail-topik" class="relative max-w-6xl mx-auto px-6 lg:px-8 mt-12">
            <div class="grid md:grid-cols-2 gap-6 lg:gap-8">
                <div class="rounded-3xl bg-white shadow-[0_18px_40px_rgba(0,0,0,0.08)] ring-1 ring-slate-100 p-6 flex flex-col gap-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-semibold uppercase tracking-wide">
                        Gejala utama
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Kenali gejala</h2>
                    <ul class="space-y-2 text-sm text-slate-700 list-disc list-inside">
                        @foreach ($symptoms as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="rounded-3xl bg-white shadow-[0_18px_40px_rgba(0,0,0,0.08)] ring-1 ring-slate-100 p-6 flex flex-col gap-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-semibold uppercase tracking-wide">
                        Tindakan cepat
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Langkah perawatan singkat</h2>
                    <ul class="space-y-2 text-sm text-slate-700 list-disc list-inside">
                        @foreach ($care as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-6 rounded-3xl bg-white shadow-[0_22px_60px_rgba(0,0,0,0.08)] ring-1 ring-slate-100 p-6 lg:p-7 flex flex-col gap-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-600 text-white text-[11px] font-semibold uppercase tracking-wide">
                    Pencegahan
                </div>
                <h2 class="text-xl font-bold text-slate-900">Cegah agar tidak berulang</h2>
                <ul class="space-y-2 text-sm text-slate-700 list-disc list-inside">
                    @foreach ($prevention as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </section>

        @if (!empty($dangerSigns))
            <section class="relative max-w-6xl mx-auto px-6 lg:px-8 mt-10">
                <div class="rounded-[24px] bg-gradient-to-br from-slate-900 via-emerald-900 to-emerald-800 text-white p-6 lg:p-8 shadow-[0_26px_70px_rgba(15,23,42,0.18)]">
                    <div class="flex items-center gap-2 text-xs uppercase tracking-[0.25em] text-emerald-200 font-semibold">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span> Waspadai tanda bahaya
                    </div>
                    <h3 class="mt-3 text-2xl font-bold">Segera hubungi tenaga medis bila muncul</h3>
                    <ul class="mt-4 space-y-2 text-sm text-slate-100 list-disc list-inside">
                        @foreach ($dangerSigns as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif
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
