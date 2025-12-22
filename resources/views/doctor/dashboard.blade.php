<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Dokter - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="font-sans antialiased bg-slate-50 min-h-screen relative">

    <x-public-navbar />

    @php
        $trustBrands = ['Klinik Mandiri','SehatPlus','Medicare','Wellness Hub','Care+'];
        $utilities = [
            ['title' => 'Kelola Artikel', 'desc' => 'Edit, arsipkan, atau publikasikan artikel medis Anda.', 'cta' => 'Buka daftar', 'href' => route('doctor.articles.index')],
            ['title' => 'Buat Artikel Baru', 'desc' => 'Mulai dari template klinis agar penulisan lebih cepat.', 'cta' => 'Tulis artikel', 'href' => route('doctor.articles.create')],
        ];
        $collabs = [
            ['title' => 'Kolaborasi Klinik', 'desc' => 'Sinkronkan informasi layanan klinik dan jadwal praktik.', 'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Inisiatif Edukasi', 'desc' => 'Program webinar, bulletin, dan kampanye pencegahan.', 'image' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Pusat Komunitas', 'desc' => 'Tanggapi pertanyaan pasien dan kumpulkan umpan balik.', 'image' => 'https://images.unsplash.com/photo-1582719478248-48cd1f0c1a97?auto=format&fit=crop&w=900&q=80'],
        ];
    @endphp

    <main class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(16,185,129,0.12),transparent_35%),radial-gradient(circle_at_80%_10%,rgba(74,222,128,0.12),transparent_30%),radial-gradient(circle_at_50%_70%,rgba(52,211,153,0.12),transparent_35%)]"></div>

        {{-- Hero --}}
        <section class="relative pt-24 pb-24 lg:pb-28 mt-2 lg:mt-8">
            <div class="max-w-6xl mx-auto px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-[32px] shadow-[0_26px_80px_rgba(15,23,42,0.16)] bg-slate-900 text-white">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT1VKm9Wd6WasiHVYT4MZP6bGn3fPe-7y3Xng&s" alt="Consultations" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/70 to-slate-900/30"></div>
                    <div class="relative px-8 lg:px-12 py-16 lg:py-20 grid lg:grid-cols-2 gap-10 items-center">
                        <div class="space-y-5">
                            <p class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-xs font-semibold uppercase tracking-[0.2em]">Panel Dokter</p>
                            <h1 class="text-4xl lg:text-5xl font-bold leading-tight">Kelola Konten Medis dan Edukasi Pasien</h1>
                            <p class="text-slate-200 text-lg max-w-xl">Publikasikan artikel klinis, pantau performa bacaan, dan siapkan materi konsultasi lebih cepat.</p>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('doctor.articles.index') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-400 transition">
                                    Kelola artikel
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                                <a href="{{ route('doctor.articles.create') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-3 text-sm font-semibold text-white ring-1 ring-white/30 hover:ring-white/50 transition">
                                    Buat artikel baru
                                </a>
                            </div>
                        </div>
                        <div class="lg:pl-8">
                            <div class="bg-white/90 text-slate-900 rounded-[22px] p-6 shadow-xl ring-1 ring-white/50 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold mb-3">Ringkasan</p>
                                <div class="space-y-3 text-sm text-slate-700">
                                    <div class="flex items-center justify-between">
                                        <span>Total artikel</span>
                                        <span class="font-semibold text-emerald-700">{{ $totalArticles ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Artikel terbaru</span>
                                        <span class="font-semibold text-emerald-700">{{ $latestArticles->count() ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <a href="{{ route('doctor.articles.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700">
                                        Buka manajemen artikel
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Utilities --}}
        <section class="max-w-6xl mx-auto px-6 lg:px-8 mt-10 lg:mt-1">
            <div class="grid lg:grid-cols-2 gap-6">
                @foreach ($utilities as $tool)
                    <div class="bg-white rounded-[22px] shadow-[0_18px_40px_rgba(0,0,0,0.08)] ring-1 ring-slate-100 p-6 flex flex-col gap-3">
                        <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Alat Dokter</p>
                        <h3 class="text-xl font-bold text-slate-900">{{ $tool['title'] }}</h3>
                        <p class="text-sm text-slate-600">{{ $tool['desc'] }}</p>
                        <a href="{{ $tool['href'] }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700">
                            {{ $tool['cta'] }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Latest public articles --}}
        <section id="artikel" class="max-w-6xl mx-auto px-6 lg:px-8 mt-16">
            <div class="text-center space-y-2">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Artikel Publik Terbaru</p>
                <h2 class="text-3xl font-bold text-slate-900">Referensi terbaru untuk pasien</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Gunakan artikel publik sebagai referensi sebelum menulis atau memperbarui konten klinis.</p>
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
                                Baca artikel
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center text-slate-500 py-10">Belum ada artikel publik.</div>
                @endforelse
            </div>
        </section>

        {{-- CTA --}}
        <section class="max-w-6xl mx-auto px-6 lg:px-8 mt-16 mb-16">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-emerald-700 to-emerald-500 text-white shadow-[0_26px_70px_rgba(15,23,42,0.18)]">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(255,255,255,0.08),transparent_35%)]"></div>
                <div class="relative p-8 lg:p-12 grid lg:grid-cols-2 gap-6 items-center">
                    <div class="space-y-3">
                        <p class="text-xs uppercase tracking-[0.25em] text-emerald-100 font-semibold">Optimalkan praktik</p>
                        <h3 class="text-3xl font-bold">Master Your Wellness, Live Fully</h3>
                        <p class="text-white/90">Rapikan konten, siapkan materi konsultasi, dan bagikan edukasi yang konsisten untuk pasien.</p>
                    </div>
                    <div class="flex flex-wrap gap-3 justify-start lg:justify-end">
                        <a href="{{ route('doctor.articles.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-semibold text-emerald-700 shadow-lg shadow-emerald-500/30 hover:bg-emerald-50 transition">
                            Kelola artikel
                        </a>
                        <a href="{{ route('doctor.articles.create') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/40 hover:bg-emerald-400 transition">
                            Tulis artikel baru
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
