<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >
    <style>
        [x-cloak]{display:none!important;}
        .no-scrollbar::-webkit-scrollbar{display:none;}
        .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none;}
        @keyframes page-fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes page-fade-up {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page-enter {
            animation: page-fade-in 420ms ease-out both;
        }
        .page-enter > * {
            animation: page-fade-up 520ms ease-out both;
        }
        .page-enter > *:nth-child(2) { animation-delay: 60ms; }
        .page-enter > *:nth-child(3) { animation-delay: 120ms; }
        .page-enter > *:nth-child(4) { animation-delay: 180ms; }
        .page-enter > *:nth-child(5) { animation-delay: 240ms; }
        .page-enter > *:nth-child(6) { animation-delay: 300ms; }
        .page-enter > *:nth-child(7) { animation-delay: 360ms; }
        .page-enter > *:nth-child(8) { animation-delay: 420ms; }
        @media (prefers-reduced-motion: reduce) {
            .page-enter,
            .page-enter > * {
                animation: none !important;
                transform: none !important;
            }
        }
        .page-bg{
            background-color:#f5faf7;
            background-image:
                radial-gradient(18% 24% at 15% 18%, rgba(52, 211, 153, 0.08), transparent 50%),
                radial-gradient(22% 26% at 82% 10%, rgba(34, 197, 94, 0.07), transparent 48%),
                linear-gradient(135deg, #f9fdfb 0%, #edf6f1 45%, #e9f3ef 100%);
        }
        .hospital-map {
            height: 500px;
            min-height: 420px;
            width: 100%;
        }
        .hospital-map-shell {
            background:
                radial-gradient(circle at top left, rgba(16, 185, 129, 0.16), transparent 28%),
                radial-gradient(circle at bottom right, rgba(45, 212, 191, 0.14), transparent 24%),
                linear-gradient(145deg, rgba(15, 23, 42, 0.98), rgba(15, 118, 110, 0.94));
        }
        .hospital-map__status[data-tone="loading"] {
            background-color: rgba(16, 185, 129, 0.16);
            color: rgb(167 243 208);
        }
        .hospital-map__status[data-tone="success"] {
            background-color: rgba(59, 130, 246, 0.18);
            color: rgb(191 219 254);
        }
        .hospital-map__status[data-tone="error"] {
            background-color: rgba(248, 113, 113, 0.18);
            color: rgb(254 202 202);
        }
        .hospital-map__list-item {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: transform 180ms ease, border-color 180ms ease, background-color 180ms ease;
        }
        .hospital-map__list-item:hover,
        .hospital-map__list-item:focus-visible {
            transform: translateY(-2px);
            border-color: rgba(52, 211, 153, 0.45);
            background: rgba(255, 255, 255, 0.1);
            outline: none;
        }
        .hospital-map__list-item.is-active {
            border-color: rgba(52, 211, 153, 0.8);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.18), rgba(59, 130, 246, 0.12));
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.18);
        }
        .hospital-map__skeleton {
            background: linear-gradient(90deg, rgba(255,255,255,0.08), rgba(255,255,255,0.16), rgba(255,255,255,0.08));
            background-size: 220% 100%;
            animation: shimmer 1.6s linear infinite;
        }
        .hospital-map__panel {
            backdrop-filter: blur(18px);
            background: linear-gradient(180deg, rgba(255,255,255,0.12), rgba(255,255,255,0.06));
        }
        .leaflet-container {
            font-family: inherit;
            background: #d9f6ee;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        }
        .leaflet-popup-content {
            margin: 14px 16px;
            min-width: 220px;
        }
        .hospital-popup__link {
            color: rgb(5 150 105);
            font-weight: 700;
            text-decoration: none;
        }
        .hospital-popup__link:hover {
            text-decoration: underline;
        }
        .hospital-marker,
        .user-marker {
            background: transparent;
            border: 0;
        }
        .hospital-marker__pin {
            position: relative;
            width: 22px;
            height: 22px;
            border-radius: 9999px;
            background: linear-gradient(135deg, #34d399, #0f766e);
            border: 3px solid rgba(255, 255, 255, 0.96);
            box-shadow: 0 10px 24px rgba(15, 118, 110, 0.38);
        }
        .hospital-marker__pin::after {
            content: "";
            position: absolute;
            inset: -8px;
            border-radius: 9999px;
            border: 1px solid rgba(52, 211, 153, 0.45);
            animation: hospitalPing 2.4s ease-out infinite;
        }
        .user-marker__pin {
            position: relative;
            width: 18px;
            height: 18px;
            border-radius: 9999px;
            background: #2563eb;
            border: 3px solid rgba(255, 255, 255, 0.96);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }
        .user-marker__pin::after {
            content: "";
            position: absolute;
            inset: -10px;
            border-radius: 9999px;
            background: rgba(59, 130, 246, 0.16);
            animation: userPulse 2s ease-out infinite;
        }
        @keyframes hospitalPing {
            0% { opacity: 0.9; transform: scale(0.8); }
            80% { opacity: 0; transform: scale(1.65); }
            100% { opacity: 0; transform: scale(1.65); }
        }
        @keyframes userPulse {
            0% { opacity: 0.9; transform: scale(0.75); }
            100% { opacity: 0; transform: scale(1.9); }
        }
        @keyframes shimmer {
            from { background-position: 200% 0; }
            to { background-position: -20% 0; }
        }
        @media (prefers-reduced-motion: reduce) {
            .hospital-map__skeleton,
            .hospital-marker__pin::after,
            .user-marker__pin::after {
                animation: none !important;
            }
        }
    </style>
</head>
<body class="page-bg font-sans antialiased min-h-screen relative">

    <x-public-navbar />

    @php
        $trustBrands = ['Klinik Mandiri','SehatPlus','Medicare','Wellness Hub','Care+'];
        $serviceCards = [
            [
                'tag' => 'Konsultasi Cerdas',
                'title' => 'Konsultasi Kesehatan dengan Ahli, Kapan Saja, Di Mana Saja',
                'desc' => 'Akses rekomendasi dokter dan langkah awal yang aman sebelum tatap muka.',
                'cta' => 'Mulai sekarang',
                'image' => 'https://images.unsplash.com/photo-1579154341053-3f6686dc0c4e?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'tag' => 'Layanan Keluarga',
                'title' => 'Health Services for Your Well-being',
                'desc' => 'Panduan praktis, tanda bahaya, dan tips gaya hidup yang mudah diikuti.',
                'cta' => 'Lihat layanan',
                'image' => 'https://images.unsplash.com/photo-1582719478181-2f2dfcd6c36c?auto=format&fit=crop&w=1200&q=80',
            ],
        ];
        $collabs = [

        ];
        $diseases = [
            ['name' => 'Stroke', 'slug' => 'stroke', 'category' => 'dewasa', 'desc' => 'Kenali FAST: wajah turun, lengan lemah, bicara pelo.'],
            ['name' => 'Demam', 'slug' => 'demam', 'category' => 'dewasa', 'desc' => 'Pantau suhu, hidrasi, waspadai demam tinggi > 3 hari.'],
            ['name' => 'Hipertensi', 'slug' => 'hipertensi', 'category' => 'dewasa', 'desc' => 'Tekanan darah tinggi; batasi garam dan rutin cek tensi.'],
            ['name' => 'Diabetes', 'slug' => 'diabetes', 'category' => 'dewasa', 'desc' => 'Sering haus, sering BAK; cek gula darah dan pola makan.'],
            ['name' => 'Asma', 'slug' => 'asma', 'category' => 'dewasa', 'desc' => 'Sesak, mengi; siapkan inhaler kontrol dan hindari pencetus.'],
            ['name' => 'GERD', 'slug' => 'gerd', 'category' => 'dewasa', 'desc' => 'Nyeri ulu hati, asam naik; hindari kopi dan makan larut malam.'],
            ['name' => 'Migrain', 'slug' => 'migrain', 'category' => 'dewasa', 'desc' => 'Sakit berdenyut, sensitif cahaya; cukup tidur dan minum air.'],
            ['name' => 'Anemia', 'slug' => 'anemia', 'category' => 'dewasa', 'desc' => 'Lemas, pucat; periksa hemoglobin dan pola makan bergizi.'],
            ['name' => 'COVID-19', 'slug' => 'covid-19', 'category' => 'dewasa', 'desc' => 'Demam, batuk, anosmia; isolasi, masker, konsultasi bila perlu.'],
            ['name' => 'Diare', 'slug' => 'diare', 'category' => 'dewasa', 'desc' => 'Rehidrasi, perhatikan tanda dehidrasi atau darah pada tinja.'],
        ];
    @endphp

    <main class="relative overflow-hidden pt-6 page-enter">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(16,185,129,0.12),transparent_35%),radial-gradient(circle_at_80%_10%,rgba(74,222,128,0.12),transparent_30%),radial-gradient(circle_at_50%_70%,rgba(52,211,153,0.12),transparent_35%)]"></div>

        {{-- Hero --}}
        <section class="relative pt-24 pb-10 lg:pb-14">
            <div class="max-w-6xl mx-auto px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-[32px] shadow-[0_26px_80px_rgba(15,23,42,0.16)] bg-slate-900 text-white">
                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1600&q=80" alt="Consultations" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/70 to-slate-900/30"></div>
                    <div class="relative px-8 lg:px-12 py-16 lg:py-20 grid lg:grid-cols-1 gap-10 items-center">
                        <div class="space-y-5">
                            <p class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-xs font-semibold uppercase tracking-[0.2em]">Panduan & Artikel</p>
                            <h1 class="text-4xl lg:text-3xl font-bold leading-tight">Konsultasi Kesehatan dengan Ahli, Kapan Saja, Di Mana Saja</h1>
                            <p class="text-slate-200 text-lg max-w-xl">Akses edukasi, cek tanda bahaya, dan hubungkan dengan tenaga medis terpercaya. Semua di satu dasbor yang ringkas.</p>
                            <div class="flex flex-wrap gap-3">
                                <a href="#artikel" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-400 transition">
                                    Lihat artikel
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                                <a href="{{ route('topics.all') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-3 text-sm font-semibold text-white ring-1 ring-white/30 hover:ring-white/50 transition">
                                    Lihat Panduan
                                </a>
                            </div>
                            @if(!empty($topicCategories))
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-white mb-2">Pilih Kategori Panduan</label>
                                    <select id="topic-category-select" onchange="if(this.value) window.location='{{ route('topics.all') }}' + '#category-' + this.value" class="rounded-2xl border border-white/20 bg-white/10 text-white px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-300">
                                        <option value="" selected disabled>Pilih kategori (mis. Bayi)</option>
                                        @foreach($topicCategories as $slug => $label)
                                            <option value="{{ $slug }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-6xl mx-auto px-6 lg:px-8 mt-2">
            <div class="hospital-map-shell relative overflow-hidden rounded-[34px] p-6 lg:p-8 text-white shadow-[0_28px_80px_rgba(15,23,42,0.2)]">
                <div class="absolute inset-0 pointer-events-none bg-[radial-gradient(circle_at_15%_20%,rgba(255,255,255,0.12),transparent_24%),radial-gradient(circle_at_82%_12%,rgba(56,189,248,0.14),transparent_22%)]"></div>
                <div class="relative">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-200">Peta Layanan Terdekat</p>
                            <h2 class="mt-3 text-3xl font-bold leading-tight text-white">Temukan rumah sakit di sekitar Anda secara real-time</h2>
                            <p class="mt-3 text-sm text-emerald-50/90 lg:text-base">
                                Peta ini bisa digeser, diperbesar, dan dijelajahi. Kami akan memakai lokasi Anda untuk menampilkan rumah sakit terdekat dan rute tercepat saat dibutuhkan.
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span data-map-status data-tone="loading" class="hospital-map__status inline-flex items-center rounded-full px-4 py-2 text-xs font-semibold tracking-wide">
                                Menyiapkan peta layanan terdekat...
                            </span>
                            <button
                                type="button"
                                data-map-refresh
                                class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2.5 text-sm font-semibold text-emerald-800 shadow-lg shadow-slate-900/10 transition hover:bg-emerald-50"
                            >
                                Gunakan lokasi saya
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-6 lg:grid-cols-[0.92fr_1.08fr]">
                        <div class="space-y-4">
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div class="hospital-map__panel rounded-[24px] p-4 ring-1 ring-white/10">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-200">Lokasi Aktif</p>
                                    <p data-map-location class="mt-2 text-lg font-semibold text-white">Mendeteksi lokasi...</p>
                                    <p class="mt-1 text-xs text-emerald-50/75">Fallback otomatis ke Jakarta jika izin lokasi tidak tersedia.</p>
                                </div>
                                <div class="hospital-map__panel rounded-[24px] p-4 ring-1 ring-white/10">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-200">Rumah Sakit</p>
                                    <p data-map-count class="mt-2 text-lg font-semibold text-white">0 lokasi</p>
                                    <p class="mt-1 text-xs text-emerald-50/75">Daftar lokasi disusun dari titik lokasi aktif Anda.</p>
                                </div>
                                <div class="hospital-map__panel rounded-[24px] p-4 ring-1 ring-white/10">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-200">Terdekat</p>
                                    <p data-map-nearest class="mt-2 text-lg font-semibold text-white">Belum tersedia</p>
                                    <p class="mt-1 text-xs text-emerald-50/75">Klik kartu lokasi untuk fokus ke marker.</p>
                                </div>
                            </div>

                            <div class="hospital-map__panel rounded-[28px] p-4 sm:p-5 ring-1 ring-white/10">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-200">Daftar Lokasi</p>
                                        <h3 class="mt-1 text-xl font-semibold text-white">Rumah sakit dalam jangkauan</h3>
                                    </div>
                                    <div class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-emerald-100">
                                        Drag map untuk eksplor area
                                    </div>
                                </div>
                                <div data-hospital-list class="mt-4 grid gap-3">
                                    <div class="hospital-map__list-item hospital-map__skeleton h-[88px] rounded-[24px]"></div>
                                    <div class="hospital-map__list-item hospital-map__skeleton h-[88px] rounded-[24px]"></div>
                                    <div class="hospital-map__list-item hospital-map__skeleton h-[88px] rounded-[24px]"></div>
                                </div>
                            </div>
                        </div>

                        <div class="relative overflow-hidden rounded-[30px] ring-1 ring-white/10 bg-slate-950/40">
                            <div class="absolute left-4 top-4 z-[500] inline-flex items-center gap-2 rounded-full bg-slate-950/70 px-3 py-2 text-xs font-semibold text-white backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Leaflet + OpenStreetMap
                            </div>
                            <div class="absolute bottom-4 left-4 right-4 z-[500] flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-slate-950/65 px-4 py-3 text-xs text-slate-100 backdrop-blur">
                                <span>Geser peta, zoom, lalu klik marker untuk detail dan rute.</span>
                                <span data-map-hint class="font-semibold text-emerald-200">Sedang mengambil lokasi Anda...</span>
                            </div>
                            <div id="hospital-map" class="hospital-map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Disease slider --}}
        <section class="max-w-6xl mx-auto px-6 lg:px-8 mt-5">
            <div class="flex items-center justify-between mb-4">
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Penyakit Umum</p>
                    <h2 class="text-2xl font-bold text-slate-900">Kenali gejala dan langkah awal</h2>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <button type="button" data-disease-scroll="left" class="h-9 w-9 rounded-full border border-emerald-100 bg-white text-emerald-700 hover:bg-emerald-50 shadow-sm flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button type="button" data-disease-scroll="right" class="h-9 w-9 rounded-full border border-emerald-100 bg-white text-emerald-700 hover:bg-emerald-50 shadow-sm flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>
            <div class="relative">
                <div data-disease-slider class="flex gap-4 overflow-x-auto pb-2 snap-x snap-mandatory no-scrollbar">
                    @foreach ($diseases as $disease)
                        <a href="{{ route('topics.show', ['category' => $disease['category'], 'topic' => $disease['slug']]) }}"
                           class="min-w-[220px] sm:min-w-[240px] snap-start bg-white/90 backdrop-blur rounded-2xl shadow-[0_8px_18px_rgba(15,23,42,0.06)] ring-1 ring-emerald-50 p-4 flex flex-col gap-3 transition hover:-translate-y-0.5 hover:shadow-[0_14px_26px_rgba(15,23,42,0.08)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300">
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">{{ $disease['name'] }}</h3>
                                <p class="text-sm text-slate-600 mt-1">{{ $disease['desc'] }}</p>
                            </div>
                            <span class="text-xs font-semibold text-emerald-700 inline-flex items-center gap-1">
                                Pelajari
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Latest articles --}}
        <section id="artikel" class="max-w-6xl mx-auto px-6 lg:px-8 mt-12">
            <div class="text-center space-y-2">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Artikel dan Tips Terbaru</p>
                <h2 class="text-3xl font-bold text-slate-900">Tetap update dengan info kesehatan</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Kumpulan tips dan artikel terbaru yang sudah diringkas supaya mudah dipahami.</p>
            </div>
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($latestArticles as $article)
                    <article class="rounded-3xl bg-white shadow-[0_18px_40px_rgba(0,0,0,0.08)] ring-1 ring-slate-100 overflow-hidden group">
                        <a href="{{ route('articles.public.show', $article->slug) }}" class="block relative h-44 overflow-hidden">
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                        </a>
                        <div class="p-5 space-y-2">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-semibold uppercase tracking-wide">
                                {{ $article->category->name ?? 'Artikel' }}
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900">
                                <a href="{{ route('articles.public.show', $article->slug) }}" class="hover:text-emerald-700 transition">{{ $article->title }}</a>
                            </h3>
                            <p class="text-sm text-slate-600">{{ Str::limit(strip_tags($article->content), 140) }}</p>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 mt-2">
                                Baca selengkapnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center text-slate-500 py-10">Belum ada artikel.</div>
                @endforelse
            </div>
        </section>

        {{-- CTA --}}
        <section class="max-w-6xl mx-auto px-6 lg:px-8 mt-10">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-emerald-700 to-emerald-500 text-white shadow-[0_26px_70px_rgba(15,23,42,0.18)]">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(255,255,255,0.08),transparent_35%)]"></div>
                <div class="relative p-8 lg:p-12 grid lg:grid-cols-2 gap-6 items-center">
                    <div class="space-y-3">
                        <p class="text-xs uppercase tracking-[0.25em] text-emerald-100 font-semibold">Ayo mulai</p>
                        <h3 class="text-3xl font-bold">Master Your Wellness, Live Fully</h3>
                        <p class="text-white/90">Buka panduan lengkap, simpan topik favorit, dan siapkan pertanyaan sebelum konsultasi.</p>
                    </div>
                    <div class="flex flex-wrap gap-3 justify-start lg:justify-end">
                        <a href="{{ route('articles.public.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-semibold text-emerald-700 shadow-lg shadow-emerald-500/30 hover:bg-emerald-50 transition">
                            Buka artikel
                        </a>
                        <a href="#artikel" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/40 hover:bg-emerald-400 transition">
                            Lihat tips terbaru
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script>
        (() => {
            const slider = document.querySelector('[data-disease-slider]');
            const controls = document.querySelectorAll('[data-disease-scroll]');
            if (slider) {
                controls.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const dir = btn.dataset.diseaseScroll === 'left' ? -1 : 1;
                        slider.scrollBy({ left: dir * 260, behavior: 'smooth' });
                    });
                });
            }

            const mapContainer = document.getElementById('hospital-map');
            if (!mapContainer || typeof window.L === 'undefined') {
                return;
            }

            const fallbackLocation = {
                lat: -6.1754,
                lng: 106.8272,
                label: 'Jakarta Pusat',
            };

            const mapStatus = document.querySelector('[data-map-status]');
            const mapLocation = document.querySelector('[data-map-location]');
            const mapCount = document.querySelector('[data-map-count]');
            const mapNearest = document.querySelector('[data-map-nearest]');
            const mapHint = document.querySelector('[data-map-hint]');
            const listContainer = document.querySelector('[data-hospital-list]');
            const refreshButton = document.querySelector('[data-map-refresh]');

            const map = L.map(mapContainer, {
                zoomControl: false,
                attributionControl: true,
            }).setView([fallbackLocation.lat, fallbackLocation.lng], 13);

            L.control.zoom({ position: 'bottomright' }).addTo(map);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                subdomains: 'abcd',
                attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            }).addTo(map);

            const hospitalLayer = L.layerGroup().addTo(map);
            let userMarker = null;
            let hospitalMarkers = [];
            let activeHospitalId = null;
            let currentLocation = { ...fallbackLocation };
            let requestId = 0;
            let hospitals = [];

            const userIcon = L.divIcon({
                className: 'user-marker',
                html: '<div class="user-marker__pin"></div>',
                iconSize: [18, 18],
                iconAnchor: [9, 9],
            });

            const hospitalIcon = L.divIcon({
                className: 'hospital-marker',
                html: '<div class="hospital-marker__pin"></div>',
                iconSize: [22, 22],
                iconAnchor: [11, 11],
                popupAnchor: [0, -12],
            });

            const escapeHtml = (value) => String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            const setStatus = (message, tone = 'loading') => {
                if (!mapStatus) return;
                mapStatus.textContent = message;
                mapStatus.dataset.tone = tone;
            };

            const setHint = (message) => {
                if (mapHint) {
                    mapHint.textContent = message;
                }
            };

            const renderSkeletonList = () => {
                if (!listContainer) return;
                listContainer.innerHTML = `
                    <div class="hospital-map__list-item hospital-map__skeleton h-[88px] rounded-[24px]"></div>
                    <div class="hospital-map__list-item hospital-map__skeleton h-[88px] rounded-[24px]"></div>
                    <div class="hospital-map__list-item hospital-map__skeleton h-[88px] rounded-[24px]"></div>
                `;
            };

            const distanceKm = (from, to) => {
                const toRadians = (value) => (value * Math.PI) / 180;
                const earthRadius = 6371;
                const deltaLat = toRadians(to.lat - from.lat);
                const deltaLng = toRadians(to.lng - from.lng);
                const a =
                    Math.sin(deltaLat / 2) ** 2 +
                    Math.cos(toRadians(from.lat)) *
                    Math.cos(toRadians(to.lat)) *
                    Math.sin(deltaLng / 2) ** 2;
                return earthRadius * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            };

            const formatDistance = (distance) => {
                if (distance < 1) {
                    return `${Math.round(distance * 1000)} m`;
                }

                return `${distance.toFixed(1)} km`;
            };

            const updateStats = () => {
                if (mapLocation) {
                    mapLocation.textContent = currentLocation.label;
                }

                if (mapCount) {
                    mapCount.textContent = `${hospitals.length} lokasi`;
                }

                if (mapNearest) {
                    mapNearest.textContent = hospitals[0]
                        ? `${formatDistance(hospitals[0].distance)}`
                        : 'Belum tersedia';
                }
            };

            const focusHospital = (id, shouldFly = true) => {
                const target = hospitals.find((hospital) => hospital.id === id);
                if (!target) {
                    return;
                }

                activeHospitalId = id;

                document.querySelectorAll('[data-hospital-item]').forEach((item) => {
                    item.classList.toggle('is-active', item.dataset.hospitalItem === id);
                });

                const marker = hospitalMarkers.find((entry) => entry.id === id)?.marker;
                if (marker) {
                    marker.openPopup();
                    if (shouldFly) {
                        map.flyTo([target.lat, target.lng], Math.max(map.getZoom(), 15), {
                            duration: 1.1,
                        });
                    }
                }

                setHint(`Fokus pada ${target.name}. Klik "Rute" untuk buka navigasi.`);
            };

            const renderHospitalList = () => {
                if (!listContainer) {
                    return;
                }

                if (!hospitals.length) {
                    listContainer.innerHTML = `
                        <div class="rounded-[24px] border border-dashed border-white/15 bg-white/5 px-4 py-6 text-sm text-emerald-50/80">
                            Belum ada rumah sakit yang ditemukan di area ini. Coba gunakan lokasi Anda lagi atau geser area peta ke wilayah lain.
                        </div>
                    `;
                    return;
                }

                listContainer.innerHTML = hospitals.map((hospital, index) => `
                    <button
                        type="button"
                        data-hospital-item="${hospital.id}"
                        class="hospital-map__list-item ${index === 0 ? 'is-active' : ''} w-full rounded-[24px] p-4 text-left"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-base font-semibold text-white">${escapeHtml(hospital.name)}</p>
                                <p class="mt-1 text-sm text-emerald-50/80">${escapeHtml(hospital.address)}</p>
                            </div>
                            <span class="rounded-full bg-white/10 px-2.5 py-1 text-xs font-semibold text-emerald-100">
                                ${formatDistance(hospital.distance)}
                            </span>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-emerald-100/90">
                            <span>${hospital.emergency ? 'IGD tersedia' : 'Informasi IGD tidak tersedia'}</span>
                            <span>${escapeHtml(hospital.openLabel)}</span>
                            <span class="font-semibold text-emerald-200">Klik untuk fokus ke peta</span>
                        </div>
                    </button>
                `).join('');

                document.querySelectorAll('[data-hospital-item]').forEach((item) => {
                    item.addEventListener('click', () => {
                        focusHospital(item.dataset.hospitalItem);
                    });
                });
            };

            const renderHospitalsOnMap = () => {
                hospitalLayer.clearLayers();
                hospitalMarkers = [];

                hospitals.forEach((hospital) => {
                    const marker = L.marker([hospital.lat, hospital.lng], { icon: hospitalIcon })
                        .bindPopup(`
                            <div class="space-y-2">
                                <p class="text-base font-semibold text-slate-900">${escapeHtml(hospital.name)}</p>
                                <p class="text-sm text-slate-600">${escapeHtml(hospital.address)}</p>
                                <p class="text-xs font-semibold text-emerald-700">${formatDistance(hospital.distance)} dari titik Anda</p>
                                <a
                                    class="hospital-popup__link"
                                    href="https://www.google.com/maps/dir/?api=1&destination=${hospital.lat},${hospital.lng}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    Buka rute di Google Maps
                                </a>
                            </div>
                        `)
                        .addTo(hospitalLayer);

                    marker.on('click', () => focusHospital(hospital.id, false));
                    hospitalMarkers.push({ id: hospital.id, marker });
                });
            };

            const normalizeHospital = (element) => {
                const latitude = element.lat ?? element.center?.lat;
                const longitude = element.lon ?? element.center?.lon;

                if (!latitude || !longitude) {
                    return null;
                }

                const tags = element.tags ?? {};
                const street = [tags['addr:street'], tags['addr:housenumber']].filter(Boolean).join(' ');
                const city = [tags['addr:suburb'], tags['addr:city'], tags['addr:state']].filter(Boolean).join(', ');
                const fallbackAddress = tags.operator || tags['contact:website'] || 'Alamat belum tersedia';

                return {
                    id: `${element.type}-${element.id}`,
                    lat: latitude,
                    lng: longitude,
                    name: tags.name || tags['name:id'] || 'Rumah Sakit',
                    address: [street, city].filter(Boolean).join(', ') || fallbackAddress,
                    emergency: tags.emergency === 'yes',
                    openLabel: tags.opening_hours ? `Jam: ${tags.opening_hours}` : 'Jam operasional cek langsung',
                };
            };

            const updateUserMarker = () => {
                if (!userMarker) {
                    userMarker = L.marker([currentLocation.lat, currentLocation.lng], { icon: userIcon })
                        .addTo(map)
                        .bindPopup('Lokasi Anda saat ini');
                    return;
                }

                userMarker.setLatLng([currentLocation.lat, currentLocation.lng]);
            };

            const fetchHospitals = async (lat, lng) => {
                const thisRequest = ++requestId;
                renderSkeletonList();
                setStatus('Mencari rumah sakit di sekitar Anda...', 'loading');
                setHint('Mengambil data rumah sakit terdekat dari OpenStreetMap...');

                const query = `
                    [out:json][timeout:25];
                    (
                      node["amenity"="hospital"](around:9000,${lat},${lng});
                      way["amenity"="hospital"](around:9000,${lat},${lng});
                      relation["amenity"="hospital"](around:9000,${lat},${lng});
                    );
                    out center;
                `;

                try {
                    const response = await fetch('https://overpass-api.de/api/interpreter', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'text/plain;charset=UTF-8',
                        },
                        body: query.trim(),
                    });

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data rumah sakit.');
                    }

                    const data = await response.json();
                    if (thisRequest !== requestId) {
                        return;
                    }

                    const seen = new Set();
                    hospitals = (data.elements ?? [])
                        .map(normalizeHospital)
                        .filter(Boolean)
                        .filter((hospital) => {
                            const key = `${hospital.name}-${hospital.lat}-${hospital.lng}`;
                            if (seen.has(key)) {
                                return false;
                            }
                            seen.add(key);
                            return true;
                        })
                        .map((hospital) => ({
                            ...hospital,
                            distance: distanceKm({ lat, lng }, hospital),
                        }))
                        .sort((first, second) => first.distance - second.distance)
                        .slice(0, 6);

                    renderHospitalsOnMap();
                    renderHospitalList();
                    updateStats();

                    if (hospitals.length) {
                        setStatus(`${hospitals.length} rumah sakit ditemukan`, 'success');
                        focusHospital(hospitals[0].id, false);
                    } else {
                        setStatus('Belum ada rumah sakit pada area ini', 'error');
                        setHint('Coba geser peta ke area lain atau gunakan lokasi Anda lagi.');
                    }
                } catch (error) {
                    if (thisRequest !== requestId) {
                        return;
                    }

                    hospitals = [];
                    renderHospitalList();
                    updateStats();
                    setStatus('Tidak dapat memuat data rumah sakit saat ini', 'error');
                    setHint('Periksa koneksi internet lalu coba lagi beberapa saat.');
                }
            };

            const useLocation = (location, shouldFly = true) => {
                currentLocation = location;
                updateUserMarker();
                updateStats();

                if (shouldFly) {
                    map.flyTo([location.lat, location.lng], 13, { duration: 1.1 });
                }

                fetchHospitals(location.lat, location.lng);
            };

            const detectLocation = () => {
                if (!navigator.geolocation) {
                    setStatus('Browser tidak mendukung geolokasi, memakai Jakarta', 'error');
                    setHint('Geolokasi tidak tersedia. Menampilkan rumah sakit sekitar Jakarta.');
                    useLocation(fallbackLocation, true);
                    return;
                }

                setStatus('Meminta akses lokasi Anda...', 'loading');

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        useLocation({
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                            label: 'Lokasi Anda',
                        });
                    },
                    () => {
                        setStatus('Izin lokasi ditolak, memakai Jakarta sebagai acuan', 'error');
                        setHint('Aktifkan izin lokasi untuk hasil yang lebih akurat.');
                        useLocation(fallbackLocation, true);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000,
                    }
                );
            };

            refreshButton?.addEventListener('click', detectLocation);

            detectLocation();
        })();
    </script>

    <footer class="relative mt-16 bg-slate-900 text-slate-100 w-screen ml-[calc(50%-50vw)]">
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
