<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak]{display:none!important;}
        .no-scrollbar::-webkit-scrollbar{display:none;}
        .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none;}
        .page-bg{
            background-color:#f5faf7;
            background-image:
                radial-gradient(18% 24% at 15% 18%, rgba(52, 211, 153, 0.08), transparent 50%),
                radial-gradient(22% 26% at 82% 10%, rgba(34, 197, 94, 0.07), transparent 48%),
                linear-gradient(135deg, #f9fdfb 0%, #edf6f1 45%, #e9f3ef 100%);
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

    <main class="relative overflow-hidden pt-6">
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

        {{-- Collaborations --}}
        <section class="max-w-6xl mx-auto px-6 lg:px-8 mt-10">
            <div class="grid lg:grid-cols-3 gap-6">
                @foreach ($collabs as $item)
                    <div class="bg-white rounded-3xl shadow-[0_18px_40px_rgba(0,0,0,0.08)] ring-1 ring-slate-100 overflow-hidden">
                        <div class="h-40">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 space-y-2">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $item['title'] }}</h3>
                            <p class="text-sm text-slate-600">{{ $item['desc'] }}</p>
                            <div class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700">
                                Learn more
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </div>
                    </div>
                @endforeach
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

    <script>
        (() => {
            const slider = document.querySelector('[data-disease-slider]');
            const controls = document.querySelectorAll('[data-disease-scroll]');
            if (!slider) return;
            controls.forEach(btn => {
                btn.addEventListener('click', () => {
                    const dir = btn.dataset.diseaseScroll === 'left' ? -1 : 1;
                    slider.scrollBy({ left: dir * 260, behavior: 'smooth' });
                });
            });
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
