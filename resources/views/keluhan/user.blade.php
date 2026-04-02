<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keluhan - KuloSehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .page-bg{
            background-color:#f5faf7;
            background-image:
                radial-gradient(18% 24% at 15% 18%, rgba(52, 211, 153, 0.08), transparent 50%),
                radial-gradient(22% 26% at 82% 10%, rgba(34, 197, 94, 0.07), transparent 48%),
                linear-gradient(135deg, #f9fdfb 0%, #edf6f1 45%, #e9f3ef 100%);
        }
        @keyframes fade-in{from{opacity:0}to{opacity:1}}
        @keyframes fade-up{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
        @keyframes glow{0%,100%{opacity:.2}50%{opacity:.35}}
    </style>
</head>
<body class="page-bg font-sans antialiased min-h-screen relative">
<x-public-navbar />
@php
    $totalKeluhan = $keluhans->count();
    $menunggu = $keluhans->where('status','menunggu')->count();
    $dijawab = $keluhans->where('status','dijawab')->count();
@endphp
<main class="relative overflow-hidden pt-24">
<div class="max-w-6xl mx-auto pb-10 px-4 sm:px-6 lg:px-8 relative">
    <div class="pointer-events-none absolute -top-16 -right-10 h-48 w-48 rounded-full bg-emerald-300/20 blur-3xl animate-[glow_6s_ease-in-out_infinite]"></div>
    <div class="pointer-events-none absolute top-36 -left-10 h-40 w-40 rounded-full bg-emerald-500/10 blur-3xl animate-[glow_7s_ease-in-out_infinite]"></div>

    <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-emerald-700 via-emerald-600 to-emerald-500 text-white p-6 sm:p-8 shadow-[0_24px_60px_rgba(16,185,129,0.35)] animate-[fade-in_500ms_ease-out]">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.2),transparent_35%)]"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-100">Dashboard Keluhan</p>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2">Keluhan Saya</h2>
                <p class="text-emerald-50/90 mt-2 max-w-2xl">Tulis keluhan dengan jelas dan pantau jawaban dokter secara real-time.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-white/30 hover:bg-white/20 transition hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
        <div class="bg-white rounded-2xl p-4 shadow-[0_16px_36px_rgba(15,23,42,0.08)] ring-1 ring-emerald-50 animate-[fade-up_520ms_ease-out]">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Total</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $totalKeluhan }}</p>
            <p class="text-sm text-slate-500">Keluhan terkirim</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-[0_16px_36px_rgba(15,23,42,0.08)] ring-1 ring-emerald-50 animate-[fade-up_560ms_ease-out]">
            <p class="text-xs uppercase tracking-[0.2em] text-amber-700 font-semibold">Menunggu</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $menunggu }}</p>
            <p class="text-sm text-slate-500">Belum dijawab</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-[0_16px_36px_rgba(15,23,42,0.08)] ring-1 ring-emerald-50 animate-[fade-up_600ms_ease-out]">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Dijawab</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $dijawab }}</p>
            <p class="text-sm text-slate-500">Sudah ada respon</p>
        </div>
    </div>

    @if(session('ok'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-3 rounded-2xl mt-5 animate-[fade-in_450ms_ease-out]">
            {{ session('ok') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-700 p-3 rounded-2xl mt-5">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6 mt-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-[0_20px_48px_rgba(15,23,42,0.1)] ring-1 ring-emerald-50 p-6 transition hover:-translate-y-1 hover:shadow-[0_26px_60px_rgba(15,23,42,0.16)] animate-[fade-up_520ms_ease-out]">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Kirim Keluhan Baru</h3>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-full">Aktif</span>
                </div>
                <p class="text-sm text-slate-600 mt-2">Ringkas, jelas, dan sertakan gejala utama.</p>
                <form method="POST" action="{{ route('keluhan.store') }}" enctype="multipart/form-data" class="space-y-3 mt-4">
                    @csrf
                    <input name="judul" placeholder="Judul Keluhan"
                           class="w-full border border-emerald-100 focus:ring-emerald-300 focus:border-emerald-300 rounded-xl p-3 text-sm">
                    <textarea name="isi" rows="5"
                              placeholder="Tulis keluhan..."
                              class="w-full border border-emerald-100 focus:ring-emerald-300 focus:border-emerald-300 rounded-xl p-3 text-sm"></textarea>
                    <div class="space-y-2">
                        {{-- <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Kategori Panduan</p> --}}
                        {{-- <select name="kategori_panduan"
                                class="w-full border border-emerald-100 focus:ring-emerald-300 focus:border-emerald-300 rounded-xl p-3 text-sm bg-white"
                                onchange="if(this.value){window.location=this.value}">
                            <option value="" selected disabled>Pilih kategori</option>
                            <option value="{{ route('categories.show', 'bayi') }}">Bayi</option>
                            <option value="{{ route('categories.show', 'remaja') }}">Remaja</option>
                            <option value="{{ route('categories.show', 'dewasa') }}">Dewasa</option>
                            <option value="{{ route('categories.show', 'lansia') }}">Lansia</option>
                        </select> --}}
                        {{-- <p class="text-xs text-slate-500">Memilih kategori akan membuka panduan sesuai usia.</p> --}}
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Lampiran (Opsional)</p>
                        <input type="file" name="gambar" accept="image/*"
                               class="w-full border border-emerald-100 rounded-xl p-2 text-sm bg-white">
                        <p class="text-xs text-slate-500">Maksimal 2MB, format JPG/PNG/WEBP.</p>
                    </div>
                    <button class="w-full bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-semibold hover:bg-emerald-500 transition hover:-translate-y-0.5">
                        Kirim Keluhan
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            @forelse($keluhans as $k)
                <div class="bg-white rounded-3xl shadow-[0_20px_48px_rgba(15,23,42,0.1)] ring-1 ring-emerald-50 p-6 transition hover:-translate-y-1 hover:shadow-[0_26px_60px_rgba(15,23,42,0.16)] animate-[fade-up_600ms_ease-out]">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Keluhan</p>
                            <h3 class="text-xl font-bold text-slate-900">{{ $k->judul }}</h3>
                            <p class="text-slate-600 mt-2">{{ $k->isi }}</p>
                            @if($k->gambar)
                                <img src="{{ asset('storage/'.$k->gambar) }}" alt="Lampiran keluhan"
                                     class="mt-3 rounded-2xl max-h-56 w-full object-cover">
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $k->status === 'dijawab' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $k->status }}
                            </span>
                            <form method="POST" action="{{ route('keluhan.destroy', $k) }}" onsubmit="return confirm('Hapus keluhan ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition hover:-translate-y-0.5">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($k->jawaban)
                        <div class="mt-4 rounded-2xl bg-emerald-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 mb-1">Jawaban Dokter</p>
                            <p class="text-slate-700">{{ $k->jawaban }}</p>
                        </div>
                    @else
                        <div class="mt-4 text-sm text-slate-500">Menunggu jawaban dokter.</div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-3xl shadow-[0_20px_48px_rgba(15,23,42,0.1)] ring-1 ring-emerald-50 p-8 text-center text-slate-500">
                    Belum ada keluhan. Silakan kirim keluhan pertama Anda.
                </div>
            @endforelse
        </div>
    </div>

</div>
</main>
</body>
</html>
