<x-app-layout>
<x-public-navbar class="relative px-4 pt-4" />
<style>
@keyframes fade-in{from{opacity:0}to{opacity:1}}
@keyframes fade-up{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
@keyframes glow{0%,100%{opacity:.2}50%{opacity:.35}}
</style>
@php
    $totalKeluhan = $keluhans->count();
    $menunggu = $keluhans->where('status','menunggu')->count();
    $dijawab = $keluhans->where('status','dijawab')->count();
@endphp
<div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8 relative">
    <div class="pointer-events-none absolute -top-16 -right-10 h-48 w-48 rounded-full bg-emerald-300/20 blur-3xl animate-[glow_6s_ease-in-out_infinite]"></div>
    <div class="pointer-events-none absolute top-36 -left-10 h-40 w-40 rounded-full bg-emerald-500/10 blur-3xl animate-[glow_7s_ease-in-out_infinite]"></div>

    <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-emerald-700 via-emerald-600 to-emerald-500 text-white p-6 sm:p-8 shadow-[0_24px_60px_rgba(16,185,129,0.35)] animate-[fade-in_500ms_ease-out]">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.2),transparent_35%)]"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-100">Dashboard Dokter</p>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2">Keluhan Pasien</h2>
                <p class="text-emerald-50/90 mt-2 max-w-2xl">Tinjau keluhan masuk, prioritaskan yang menunggu, dan kirim jawaban.</p>
            </div>
            <a href="{{ route('doctor.dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-white/30 hover:bg-white/20 transition hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
        <div class="bg-white rounded-2xl p-4 shadow-[0_16px_36px_rgba(15,23,42,0.08)] ring-1 ring-emerald-50 animate-[fade-up_520ms_ease-out]">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Total</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $totalKeluhan }}</p>
            <p class="text-sm text-slate-500">Keluhan masuk</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-[0_16px_36px_rgba(15,23,42,0.08)] ring-1 ring-emerald-50 animate-[fade-up_560ms_ease-out]">
            <p class="text-xs uppercase tracking-[0.2em] text-amber-700 font-semibold">Menunggu</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $menunggu }}</p>
            <p class="text-sm text-slate-500">Perlu respon</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-[0_16px_36px_rgba(15,23,42,0.08)] ring-1 ring-emerald-50 animate-[fade-up_600ms_ease-out]">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Dijawab</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $dijawab }}</p>
            <p class="text-sm text-slate-500">Sudah diproses</p>
        </div>
    </div>

    @if(session('ok'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-3 rounded-2xl mt-5 animate-[fade-in_450ms_ease-out]">
            {{ session('ok') }}
        </div>
    @endif

    <div class="space-y-4 mt-6">
        @forelse($keluhans as $k)
            <div class="bg-white rounded-3xl shadow-[0_20px_48px_rgba(15,23,42,0.1)] ring-1 ring-emerald-50 p-6 transition hover:-translate-y-1 hover:shadow-[0_26px_60px_rgba(15,23,42,0.16)] animate-[fade-up_600ms_ease-out]">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Pasien</p>
                            <h3 class="text-xl font-bold text-slate-900">{{ $k->user->name }}</h3>
                            <p class="text-sm text-slate-500">{{ $k->user->email }}</p>
                        <div class="mt-3">
                            <p class="text-sm font-semibold text-slate-900">{{ $k->judul }}</p>
                            <p class="text-slate-600 mt-2">{{ $k->isi }}</p>
                            @if($k->gambar)
                                <img src="{{ asset('storage/'.$k->gambar) }}" alt="Lampiran keluhan"
                                     class="mt-3 rounded-2xl max-h-56 w-full object-cover">
                            @endif
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $k->status === 'dijawab' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $k->status }}
                    </span>
                </div>

                @if(!$k->jawaban)
                    <form method="POST"
                          action="{{ route('dokter.keluhan.jawab',$k) }}"
                          class="mt-4">
                        @csrf
                        <textarea name="jawaban"
                                  class="w-full border border-emerald-100 focus:ring-emerald-300 focus:border-emerald-300 rounded-xl p-3 text-sm mb-3"
                                  placeholder="Tulis jawaban dokter"></textarea>

                        <button class="bg-emerald-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-emerald-500 transition hover:-translate-y-0.5">
                            Kirim Jawaban
                        </button>
                    </form>
                @else
                    <div class="mt-4 rounded-2xl bg-emerald-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 mb-1">Jawaban</p>
                        <p class="text-slate-700">{{ $k->jawaban }}</p>
                    </div>
                @endif

            </div>
        @empty
            <div class="bg-white rounded-3xl shadow-[0_20px_48px_rgba(15,23,42,0.1)] ring-1 ring-emerald-50 p-8 text-center text-slate-500">
                Belum ada keluhan dari user.
            </div>
        @endforelse
    </div>

</div>
</x-app-layout>
