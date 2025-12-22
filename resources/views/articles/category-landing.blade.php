<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kategori {{ $categoryName }} - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .page-bg {
            background-color: #f5faf7;
            background-image:
                radial-gradient(18% 24% at 15% 18%, rgba(52, 211, 153, 0.08), transparent 50%),
                radial-gradient(22% 26% at 82% 10%, rgba(34, 197, 94, 0.07), transparent 48%),
                linear-gradient(135deg, #f9fdfb 0%, #edf6f1 45%, #e9f3ef 100%);
        }
    </style>
</head>
<body class="page-bg font-sans antialiased min-h-screen relative">

    <x-public-navbar :show-search="false" :active-category-slug="$categorySlug" />

    <main class="pt-24 pb-16 relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(16,185,129,0.12),transparent_35%),radial-gradient(circle_at_80%_10%,rgba(74,222,128,0.12),transparent_30%),radial-gradient(circle_at_50%_70%,rgba(52,211,153,0.12),transparent_35%)]"></div>

        @php
            $categoryLower = strtolower($categoryName);

            $featureHighlights = [
                [
                    'eyebrow' => 'Konsultasi Cerdas',
                    'title' => 'Panduan cepat bersama dokter tepercaya',
                    'desc' => 'Langsung temukan langkah awal yang tepat, lengkap dengan edukasi singkat, tindakan mandiri, dan tanda bahaya.',
                    'cta' => 'Mulai cek gejala',
                ],
                [
                    'eyebrow' => 'Materi Terverifikasi',
                    'title' => 'Ditulis praktisi, disusun supaya mudah dipakai',
                    'desc' => 'Artikel disunting tim medis dan dikemas ringkas agar keluarga bisa segera praktek dengan aman.',
                    'cta' => 'Lihat kurasi topik',
                ],
            ];

            $wellnessPillars = [
                ['title' => 'Bahas apa yang penting', 'desc' => 'Gejala, pencegahan, hingga perawatan harian yang relevan untuk ' . $categoryLower . '.', 'icon' => '*'],
                ['title' => 'Tindakan terstruktur', 'desc' => 'Checklist singkat, kapan perlu ke dokter, dan cara komunikasi dengan tenaga medis.', 'icon' => '*'],
                ['title' => 'Selalu terbarui', 'desc' => 'Konten diperbarui mengikuti rekomendasi praktik terbaru.', 'icon' => '*'],
            ];

            $palettes = [
                'flu' => 'from-emerald-50 via-green-50 to-emerald-100',
                'demam' => 'from-lime-50 via-green-50 to-emerald-100',
                'diare' => 'from-emerald-50 via-emerald-50 to-teal-100',
                'ruam-popok' => 'from-green-50 via-emerald-50 to-emerald-100',
                'akne' => 'from-emerald-50 via-emerald-50 to-emerald-100',
                'anemia' => 'from-emerald-50 via-green-50 to-lime-100',
                'migrain' => 'from-teal-50 via-emerald-50 to-teal-100',
                'skoliosis' => 'from-emerald-50 via-teal-50 to-emerald-100',
                'depresi' => 'from-slate-50 via-slate-50 to-emerald-50',
                'ansietas' => 'from-teal-50 via-emerald-50 to-emerald-100',
                'insomnia' => 'from-emerald-50 via-emerald-50 to-teal-100',
                'gastritis' => 'from-lime-50 via-green-50 to-emerald-100',
                'hepatitis' => 'from-emerald-50 via-green-50 to-emerald-100',
                'obesitas' => 'from-emerald-50 via-green-50 to-emerald-100',
                'hipertensi' => 'from-emerald-50 via-emerald-50 to-lime-100',
                'diabetes' => 'from-emerald-50 via-emerald-50 to-teal-100',
                'stroke' => 'from-emerald-50 via-emerald-50 to-teal-100',
                'kanker' => 'from-emerald-50 via-green-50 to-emerald-100',
                'asma' => 'from-teal-50 via-emerald-50 to-teal-100',
                'osteoartritis' => 'from-emerald-50 via-emerald-50 to-lime-100',
                'hiperlipidemia' => 'from-lime-50 via-green-50 to-emerald-100',
                'nefritis' => 'from-green-50 via-emerald-50 to-emerald-100',
                'demensia' => 'from-slate-50 via-slate-50 to-emerald-50',
                'alzheimer' => 'from-slate-50 via-emerald-50 to-emerald-100',
                'osteoporosis' => 'from-teal-50 via-emerald-50 to-emerald-100',
                'katarak' => 'from-lime-50 via-emerald-50 to-emerald-100',
                'parkinson' => 'from-emerald-50 via-teal-50 to-emerald-100',
            ];
        @endphp

        <section class="relative max-w-6xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-12 items-center">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-xs font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Fokus {{ $categoryLower }}
                    </div>
                    <div class="space-y-4">
                        <h1 class="text-4xl lg:text-5xl font-bold leading-tight text-slate-900">
                            Konsultasi & Artikel Sehat Kapan Saja, Di Mana Saja
                        </h1>
                        <p class="text-lg text-slate-700 max-w-2xl">
                            Ringkasan langkah praktis, tanda bahaya, dan tips gaya hidup yang siap dipakai untuk {{ $categoryLower }}. Tetap tenang, setiap keputusan kesehatan jadi lebih pasti.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('articles.public.index', ['category' => $categorySlug, 'view' => 'list']) }}"
                           class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                            Lihat semua artikel
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="#topik" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 hover:ring-emerald-200 transition">
                            Lihat topik unggulan
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-6 text-sm text-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-semibold">24/7</div>
                            <div>
                                <p class="font-semibold text-slate-900">Siap kapan saja</p>
                                <p class="text-slate-600">Akses artikel & panduan darurat cepat.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-semibold">ID</div>
                            <div>
                                <p class="font-semibold text-slate-900">Terverifikasi</p>
                                <p class="text-slate-600">Ditinjau oleh tim KuloSehat.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -top-6 -right-10 h-24 w-24 rounded-full bg-emerald-200/40 blur-3xl"></div>
                    <div class="relative rounded-[28px] overflow-hidden shadow-[0_26px_80px_rgba(15,23,42,0.14)] ring-1 ring-white/50 bg-gradient-to-br from-white via-emerald-50/40 to-emerald-100/40">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(52,211,153,0.12),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(74,222,128,0.12),transparent_35%)]"></div>
                        <div class="p-6 sm:p-8">
                            <div class="text-xs font-semibold text-emerald-700 inline-flex px-3 py-1 rounded-full bg-emerald-50 ring-1 ring-emerald-100 mb-4">Layanan Unggulan</div>
                        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-emerald-600 via-emerald-500 to-emerald-400 text-white p-5">
                                <div class="space-y-2">
                                    <p class="text-sm font-semibold text-emerald-100">Teman sehat keluarga</p>
                                    <p class="text-lg font-bold leading-tight">Panduan praktis untuk {{ $categoryLower }}</p>
                                    <div class="flex items-center gap-2 text-sm text-emerald-100">
                                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span> Akses cepat & terarah
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 grid grid-cols-2 gap-3">
                                @foreach ($featureHighlights as $highlight)
                                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-emerald-50">
                                        <p class="text-[11px] uppercase tracking-wide text-emerald-700 font-semibold">{{ $highlight['eyebrow'] }}</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $highlight['title'] }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ $highlight['desc'] }}</p>
                                        <div class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-emerald-700">
                                            {{ $highlight['cta'] }}
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 lg:mt-12 flex flex-wrap items-center gap-6 text-slate-500 text-xs uppercase tracking-wide">
                <span class="text-slate-700 font-semibold">Dipercaya oleh pembaca:</span>
                <div class="flex flex-wrap gap-4 sm:gap-6">
                    @foreach (['Klinik Mandiri','Rumah Sehat','Medicare','SehatPlus','Wellness Hub'] as $brand)
                        <span class="px-3 py-2 rounded-full bg-white shadow-sm ring-1 ring-slate-100 text-slate-600 font-semibold">{{ $brand }}</span>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="relative max-w-6xl mx-auto px-6 lg:px-8 mt-16" id="topik">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Kurasi Topik</p>
                    <h2 class="text-3xl font-bold text-slate-900 mt-2">Topik unggulan untuk {{ $categoryLower }}</h2>
                    <p class="text-slate-600 mt-2 max-w-2xl">Pilih topik yang paling relevan. Setiap panduan diringkas dengan bahasa sederhana dan langkah yang bisa langsung dipraktikkan.</p>
                </div>
                <a href="{{ route('articles.public.index', ['category' => $categorySlug, 'view' => 'list']) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700">
                    Lihat daftar artikel
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            <div class="mt-8">
                @if (!empty($cards))
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                        @foreach ($cards as $item)
                            @php
                                $slug = $item['slug'] ?? $categorySlug;
                                $label = $item['label'] ?? $categoryName;
                                $bg = $palettes[$slug] ?? 'from-emerald-50 via-white to-emerald-100';
                            @endphp
                            <a href="{{ route('topics.show', ['category' => $categorySlug, 'topic' => $slug]) }}"
                               class="group relative h-full rounded-3xl bg-gradient-to-br {{ $bg }} border border-white/70 shadow-[0_18px_40px_rgba(0,0,0,0.08)] p-6 flex flex-col gap-4 transition hover:-translate-y-1 hover:shadow-[0_30px_70px_rgba(16,185,129,0.18)] overflow-hidden">
                                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/40 blur-2xl"></div>
                                <div class="relative inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 text-gray-800 text-[11px] font-semibold tracking-wide shadow-sm ring-1 ring-white">
                                    {{ $label }}
                                </div>
                                <div class="relative">
                                    <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2">Topik {{ $label }}</h3>
                                    <p class="text-sm text-gray-700 mb-4">Pelajari gejala umum, penanganan mandiri, dan kapan perlu berkonsultasi tentang {{ strtolower($label) }}.</p>
                                </div>
                                <div class="relative mt-auto flex items-center gap-2 text-emerald-700 font-semibold text-sm">
                                    <span>Lihat artikel</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white/90 rounded-3xl p-10 shadow-[0_18px_40px_rgba(0,0,0,0.12)] border border-white/60 max-w-3xl mx-auto text-center text-gray-800">
                        <p class="text-lg font-semibold">Belum ada topik khusus untuk kategori {{ $categoryName }}.</p>
                        <p class="mt-2 text-gray-600">Langsung buka artikel kategori ini lewat daftar artikel.</p>
                    </div>
                @endif
            </div>
        </section>

        <section class="relative max-w-6xl mx-auto px-6 lg:px-8 mt-16 lg:mt-20">
            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-[0_26px_70px_rgba(15,23,42,0.08)] ring-1 ring-slate-100 p-8">
                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 text-emerald-700 px-3 py-1 text-xs font-semibold">Pilihan Editor</div>
                    <h3 class="text-2xl font-bold text-slate-900 mt-4">Materi kesehatan yang bisa langsung dipakai</h3>
                    <p class="text-slate-600 mt-2 max-w-2xl">Temukan rangkuman singkat, tips gaya hidup, serta panduan cek-langkah yang membantu mengambil keputusan lebih cepat.</p>
                    <div class="mt-6 grid sm:grid-cols-2 gap-4">
                        @foreach ($featureHighlights as $highlight)
                            <div class="rounded-2xl p-4 bg-gradient-to-br from-slate-50 to-white ring-1 ring-slate-100">
                                <p class="text-[11px] uppercase tracking-wide text-emerald-700 font-semibold">{{ $highlight['eyebrow'] }}</p>
                                <p class="mt-2 text-base font-semibold text-slate-900">{{ $highlight['title'] }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $highlight['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700">
                        Pelajari selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-slate-900 via-emerald-900 to-emerald-800 text-white rounded-3xl p-7 shadow-[0_26px_70px_rgba(15,23,42,0.18)]">
                    <p class="text-xs uppercase tracking-[0.25em] text-emerald-200 font-semibold">Butuh cepat?</p>
                    <h4 class="text-2xl font-bold mt-3">Ringkasan 3 menit</h4>
                    <p class="mt-2 text-slate-100/90">Ringkas dan jelas untuk pertama kali menangani keluhan. Ada tanda bahaya yang perlu diwaspadai.</p>
                    <div class="mt-5 space-y-3 text-sm text-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="h-9 w-9 rounded-2xl bg-white/10 flex items-center justify-center font-semibold text-emerald-200">01</span>
                            <div>
                                <p class="font-semibold text-white">Kenali gejala</p>
                                <p class="text-slate-200/80">Apa yang normal, apa yang harus diwaspadai.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-9 w-9 rounded-2xl bg-white/10 flex items-center justify-center font-semibold text-emerald-200">02</span>
                            <div>
                                <p class="font-semibold text-white">Tindakan mandiri</p>
                                <p class="text-slate-200/80">Langkah aman sebelum bertemu dokter.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-9 w-9 rounded-2xl bg-white/10 flex items-center justify-center font-semibold text-emerald-200">03</span>
                            <div>
                                <p class="font-semibold text-white">Kapan konsultasi</p>
                                <p class="text-slate-200/80">Batas waktu dan kondisi darurat.</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('articles.public.index', ['category' => $categorySlug, 'view' => 'list']) }}" class="mt-6 inline-flex items-center justify-center w-full rounded-full bg-emerald-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/40 hover:bg-emerald-400 transition">
                        Buka panduan cepat
                    </a>
                </div>
            </div>
        </section>

        <section class="relative max-w-6xl mx-auto px-6 lg:px-8 mt-16 lg:mt-20">
            <div class="rounded-[28px] bg-white shadow-[0_26px_70px_rgba(15,23,42,0.08)] ring-1 ring-slate-100 p-8 lg:p-10">
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach ($wellnessPillars as $pillar)
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

        <section class="relative max-w-6xl mx-auto px-6 lg:px-8 mt-16 lg:mt-20">
            <div class="grid lg:grid-cols-5 gap-6 items-center">
                <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 via-emerald-900 to-emerald-800 text-white rounded-3xl p-8 shadow-[0_26px_70px_rgba(15,23,42,0.18)]">
                    <p class="text-xs uppercase tracking-[0.25em] text-emerald-200 font-semibold">Testimoni</p>
                    <p class="mt-3 text-lg leading-relaxed">"Artikel di KuloSehat memudahkan saya memahami langkah awal sebelum ke dokter. Penjelasannya singkat, bahasa mudah, dan ada panduan kapan harus waspada."</p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="h-12 w-12 rounded-full bg-emerald-500/30 border border-emerald-200 flex items-center justify-center text-white font-semibold">A</div>
                        <div>
                            <p class="font-semibold">Aulia Putri</p>
                            <p class="text-sm text-emerald-100">Orang tua dua anak</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-3 rounded-3xl bg-white shadow-[0_26px_70px_rgba(15,23,42,0.08)] ring-1 ring-slate-100 p-8">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Ayo mulai</p>
                    </div>
                    <h3 class="mt-3 text-2xl font-bold text-slate-900">Mulai perjalanan sehat dengan panduan terpercaya</h3>
                    <p class="mt-2 text-slate-600">Kumpulkan topik favorit, baca ringkasannya, lalu simpan sebagai catatan sebelum konsultasi dengan tenaga medis.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('articles.public.index', ['category' => $categorySlug, 'view' => 'list']) }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                            Buka artikel
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="#topik" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 hover:ring-emerald-200 transition">
                            Lihat topik unggulan
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
