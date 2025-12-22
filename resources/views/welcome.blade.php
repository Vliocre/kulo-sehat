<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KuloSehat - Beranda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="font-sans antialiased bg-slate-50 min-h-screen relative">

    <x-public-navbar :show-search="false" />

    @php
        $highlights = [
            [
                'eyebrow' => 'Kesehatan Keluarga',
                'title' => 'Akses cepat ke edukasi dan konsultasi tepercaya',
                'desc' => 'Langkah praktis, tanda bahaya, dan tips gaya hidup untuk menjaga keluarga tetap sehat.',
                'cta' => 'Mulai pelajari',
            ],
            [
                'eyebrow' => 'Didukung tenaga medis',
                'title' => 'Kurasi artikel oleh dokter & editor kesehatan',
                'desc' => 'Bahasa sederhana, tetap akurat, dan siap langsung dipraktikkan setiap hari.',
                'cta' => 'Lihat rekomendasi',
            ],
        ];

        $pillars = [
            ['title' => 'Bahasa sederhana', 'desc' => 'Ringkas, tanpa istilah rumit, fokus ke tindakan nyata.', 'icon' => '*'],
            ['title' => 'Terupdate', 'desc' => 'Konten direview berkala mengikuti praktik terbaik.', 'icon' => '*'],
            ['title' => 'Siap dipakai', 'desc' => 'Checklist singkat, tanda bahaya, dan kapan perlu konsultasi.', 'icon' => '*'],
        ];
    @endphp

    <main class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(16,185,129,0.12),transparent_30%),radial-gradient(circle_at_80%_10%,rgba(74,222,128,0.12),transparent_30%),radial-gradient(circle_at_60%_70%,rgba(52,211,153,0.12),transparent_30%)]"></div>

        {{-- Hero --}}
        <section class="relative pt-28 pb-16 lg:pb-20">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6 relative">
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-xs font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Untuk keluarga Indonesia
                    </div>
                    <div class="space-y-4">
                        <h1 class="text-4xl lg:text-5xl font-bold leading-tight text-slate-900">Konsultasi & Artikel Kesehatan. Kapan saja, di mana saja.</h1>
                        <p class="text-lg text-slate-700">Akses edukasi medis yang diringkas, langkah mandiri yang aman, dan panduan kapan harus bertemu dokter.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                            Masuk / Daftar
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="#artikel" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 hover:ring-emerald-200 transition">
                            Jelajahi artikel
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-6 text-sm text-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-semibold">24/7</div>
                            <div>
                                <p class="font-semibold text-slate-900">Akses kapan saja</p>
                                <p class="text-slate-600">Artikel, panduan darurat, dan tips praktis.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-semibold">MD</div>
                            <div>
                                <p class="font-semibold text-slate-900">Terverifikasi</p>
                                <p class="text-slate-600">Ditinjau dokter & editor medis.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -top-8 -right-8 h-28 w-28 rounded-full bg-emerald-200/40 blur-3xl"></div>
                    <div class="relative rounded-[28px] overflow-hidden shadow-[0_26px_80px_rgba(15,23,42,0.14)] ring-1 ring-white/50 bg-gradient-to-br from-white via-emerald-50/40 to-emerald-100/40">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(52,211,153,0.12),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(74,222,128,0.12),transparent_35%)]"></div>
                        <div class="p-6 sm:p-8">
                            <div class="text-xs font-semibold text-emerald-700 inline-flex px-3 py-1 rounded-full bg-emerald-50 ring-1 ring-emerald-100 mb-4">Layanan Unggulan</div>
                            <div class="relative rounded-2xl overflow-hidden">
                                <img src="https://iik.ac.id/blog/wp-content/uploads/2024/10/manajemen-kesehatan.jpeg" alt="Konsultasi kesehatan" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/70 via-slate-900/30 to-transparent"></div>
                                <div class="absolute left-0 bottom-0 p-5 text-white space-y-2 max-w-xs">
                                    <p class="text-sm font-semibold text-emerald-200">Teman sehat keluarga</p>
                                    <p class="text-lg font-bold leading-tight">Panduan praktis & terarah</p>
                                    <div class="flex items-center gap-2 text-sm text-emerald-100">
                                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span> Siap dipakai kapan saja
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 grid grid-cols-2 gap-3">
                                @foreach ($highlights as $item)
                                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-emerald-50">
                                        <p class="text-[11px] uppercase tracking-wide text-emerald-700 font-semibold">{{ $item['eyebrow'] }}</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $item['title'] }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $item['desc'] }}</p>
                                        <div class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-emerald-700">
                                            {{ $item['cta'] }}
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Pillars --}}
        <section class="relative max-w-6xl mx-auto px-6 lg:px-8 mt-6 lg:mt-8">
            <div class="rounded-[28px] bg-white shadow-[0_26px_70px_rgba(15,23,42,0.08)] ring-1 ring-slate-100 p-8 lg:p-10">
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach ($pillars as $pillar)
                        <div class="flex gap-4">
                            <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-xl flex items-center justify-center">{{ $pillar['icon'] }}</div>
                            <div>
                                <p class="text-base font-semibold text-slate-900">{{ $pillar['title'] }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $pillar['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="relative max-w-6xl mx-auto px-6 lg:px-8 mt-8 lg:mt-10">
            <div class="grid lg:grid-cols-5 gap-6 items-center">
                <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 via-emerald-900 to-emerald-800 text-white rounded-3xl p-8 shadow-[0_26px_70px_rgba(15,23,42,0.18)]">
                    <p class="text-xs uppercase tracking-[0.25em] text-emerald-200 font-semibold">Testimoni</p>
                    <p class="mt-3 text-lg leading-relaxed">"KuloSehat membantu saya memahami apa yang perlu dilakukan sebelum ke dokter. Informasinya singkat, jelas, dan ada batasan kapan harus waspada."</p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="h-12 w-12 rounded-full bg-emerald-500/30 border border-emerald-200 flex items-center justify-center text-white font-semibold">R</div>
                        <div>
                            <p class="font-semibold">Rani Pratama</p>
                            <p class="text-sm text-emerald-100">Ibu dua anak</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-3 rounded-3xl bg-white shadow-[0_26px_70px_rgba(15,23,42,0.08)] ring-1 ring-slate-100 p-8">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Ayo mulai</p>
                    </div>
                    <h3 class="mt-3 text-2xl font-bold text-slate-900">Buka panduan kesehatan kapan saja</h3>
                    <p class="mt-2 text-slate-600">Kumpulkan topik favorit, baca ringkasannya, lalu siapkan pertanyaan sebelum bertemu tenaga medis.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                            Masuk / Daftar
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="#artikel" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 hover:ring-emerald-200 transition">
                            Jelajahi artikel
                        </a>
                    </div>
                </div>
            </div>
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
