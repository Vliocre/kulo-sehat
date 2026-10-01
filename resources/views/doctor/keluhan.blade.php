<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keluhan Pasien - KuloSehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>.page-bg{background-color:#f5faf7;background-image:radial-gradient(18% 24% at 15% 18%,rgba(52,211,153,.08),transparent 50%),radial-gradient(22% 26% at 82% 10%,rgba(34,197,94,.07),transparent 48%),linear-gradient(135deg,#f9fdfb 0%,#edf6f1 45%,#e9f3ef 100%)}</style>
</head>
<body class="page-bg min-h-screen font-sans antialiased text-slate-900">
<x-public-navbar />

@php
    $premiumLabels = ['requested' => 'Permintaan baru', 'active' => 'Aktif', 'closed' => 'Selesai', 'rejected' => 'Ditolak'];
    $premiumTones = ['requested' => 'bg-amber-100 text-amber-700', 'active' => 'bg-emerald-100 text-emerald-700', 'closed' => 'bg-slate-100 text-slate-600', 'rejected' => 'bg-rose-100 text-rose-700'];
@endphp

<main class="relative overflow-hidden pb-12 pt-28">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_18%_12%,rgba(16,185,129,.12),transparent_30%)]"></div>
    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-[30px] bg-gradient-to-r from-emerald-800 via-emerald-600 to-teal-500 p-6 text-white shadow-[0_24px_65px_rgba(16,185,129,.3)] sm:p-8">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_15%,rgba(255,255,255,.2),transparent_35%)]"></div>
            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div><p class="text-xs font-semibold uppercase tracking-[.28em] text-emerald-100">Dashboard Dokter</p><h1 class="mt-3 text-3xl font-bold sm:text-4xl">Keluhan Pasien</h1><p class="mt-2 max-w-2xl text-emerald-50/90">Kelola permintaan premium yang ditujukan kepada Anda dan bantu keluhan gratis dengan satu jawaban.</p></div>
                <a href="{{ route('doctor.dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-white/10 px-5 py-2.5 text-sm font-semibold ring-1 ring-white/30 transition hover:bg-white/20"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>Kembali ke Dashboard</a>
            </div>
        </section>

        @if(session('ok'))<div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('ok') }}</div>@endif
        @if($errors->any())<div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="mt-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div><p class="text-xs font-bold uppercase tracking-[.22em] text-emerald-700">Keluhan Premium</p><h2 class="mt-1 text-2xl font-bold sm:text-3xl">Percakapan pribadi Anda</h2><p class="mt-2 text-sm text-slate-600">Hanya pasien yang memilih Anda yang muncul di bagian ini.</p></div>
                <div class="flex gap-2 text-xs font-bold"><span class="rounded-full bg-amber-100 px-3 py-1.5 text-amber-700">{{ $premiumKeluhans->where('premium_status', 'requested')->count() }} baru</span><span class="rounded-full bg-emerald-100 px-3 py-1.5 text-emerald-700">{{ $premiumKeluhans->where('premium_status', 'active')->count() }} aktif</span></div>
            </div>

            <div class="mt-5 space-y-5">
                @forelse($premiumKeluhans as $consultation)
                    <article class="overflow-hidden rounded-[28px] border border-emerald-100 bg-white shadow-[0_18px_45px_rgba(15,23,42,.08)]">
                        <header class="flex flex-col gap-4 border-b border-emerald-50 bg-gradient-to-r from-emerald-50 to-white p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-slate-900 font-bold text-white">{{ collect(explode(' ', $consultation->user->name))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('') }}</div>
                                <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">Pasien Premium</p><h3 class="font-bold">{{ $consultation->user->name }}</h3><p class="mt-1 text-sm text-slate-500">{{ $consultation->judul }}</p></div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $premiumTones[$consultation->premium_status] ?? 'bg-slate-100 text-slate-600' }}">{{ $premiumLabels[$consultation->premium_status] ?? ucfirst($consultation->premium_status) }}</span>
                                @if($consultation->premium_status === 'requested')
                                    <form method="POST" action="{{ route('dokter.keluhan-premium.accept', $consultation) }}">@csrf @method('PATCH')<button class="rounded-full bg-emerald-600 px-4 py-1.5 text-xs font-bold text-white hover:bg-emerald-500">Terima</button></form>
                                    <form method="POST" action="{{ route('dokter.keluhan-premium.reject', $consultation) }}" onsubmit="return confirm('Tolak permintaan konsultasi ini?');">@csrf @method('PATCH')<button class="rounded-full bg-rose-50 px-4 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-100">Tolak</button></form>
                                @elseif($consultation->premium_status === 'active')
                                    <form method="POST" action="{{ route('keluhan.premium.close', $consultation) }}" onsubmit="return confirm('Selesaikan konsultasi ini?');">@csrf @method('PATCH')<button class="rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-bold text-slate-600 hover:text-rose-600">Selesaikan</button></form>
                                @endif
                            </div>
                        </header>

                        <div class="max-h-[430px] space-y-3 overflow-y-auto bg-slate-50/60 p-4 sm:p-6">
                            @foreach($consultation->pesanPremium->sortBy('created_at') as $message)
                                @php $mine = $message->sender_id === auth()->id(); @endphp
                                <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[88%] rounded-2xl px-4 py-3 sm:max-w-[72%] {{ $mine ? 'rounded-br-md bg-emerald-600 text-white' : 'rounded-bl-md border border-slate-100 bg-white text-slate-700 shadow-sm' }}">
                                        <p class="whitespace-pre-line text-sm leading-6">{{ $message->pesan }}</p>
                                        @if($message->gambar)<a href="{{ route('keluhan.premium.attachment', [$consultation, $message]) }}" target="_blank"><img src="{{ route('keluhan.premium.attachment', [$consultation, $message]) }}" alt="Lampiran pasien" loading="lazy" class="mt-2 max-h-56 w-full rounded-xl object-cover"></a>@endif
                                        <p class="mt-1.5 text-[10px] {{ $mine ? 'text-emerald-100' : 'text-slate-400' }}">{{ $message->created_at->format('d M, H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($consultation->premium_status === 'active')
                            <form method="POST" action="{{ route('keluhan.premium.message', $consultation) }}" enctype="multipart/form-data" class="border-t border-slate-100 p-4 sm:p-5">
                                @csrf
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                                    <textarea name="pesan" rows="2" maxlength="5000" placeholder="Tulis balasan untuk pasien..." class="w-full flex-1 resize-none rounded-2xl border-slate-200 text-sm focus:border-emerald-400 focus:ring-emerald-200"></textarea>
                                    <label class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-xs font-semibold text-slate-600 hover:border-emerald-300 hover:text-emerald-700">Lampirkan gambar<input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="sr-only"></label>
                                    <button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-500">Kirim</button>
                                </div>
                            </form>
                        @elseif($consultation->premium_status === 'requested')
                            <p class="border-t border-amber-100 bg-amber-50 px-5 py-4 text-sm text-amber-700">Terima permintaan untuk membuka percakapan dua arah.</p>
                        @else
                            <p class="border-t border-slate-100 bg-slate-50 px-5 py-4 text-sm text-slate-500">Percakapan ini sudah ditutup dan hanya dapat dibaca.</p>
                        @endif
                    </article>
                @empty
                    <div class="rounded-[28px] border border-dashed border-emerald-200 bg-white/70 p-8 text-center text-sm text-slate-500">Belum ada Keluhan Premium yang ditujukan kepada Anda.</div>
                @endforelse
            </div>
        </section>

        <section class="mt-12 border-t border-emerald-100 pt-10">
            <div><p class="text-xs font-bold uppercase tracking-[.22em] text-emerald-700">Keluhan Gratis</p><h2 class="mt-1 text-2xl font-bold sm:text-3xl">Pertanyaan satu kali jawab</h2><p class="mt-2 text-sm text-slate-600">Jawaban pertama akan tercatat atas nama dokter yang merespons.</p></div>
            <div class="mt-5 space-y-4">
                @forelse($keluhans as $keluhan)
                    <article class="rounded-[26px] border border-emerald-100 bg-white p-5 shadow-[0_14px_35px_rgba(15,23,42,.07)] sm:p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">{{ $keluhan->user->name }}</p><h3 class="mt-1 text-lg font-bold">{{ $keluhan->judul }}</h3><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $keluhan->isi }}</p></div>
                            <span class="w-fit rounded-full px-3 py-1 text-xs font-bold {{ $keluhan->status === 'dijawab' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($keluhan->status) }}</span>
                        </div>
                        @if($keluhan->gambar)<img src="{{ asset('storage/'.$keluhan->gambar) }}" alt="Lampiran keluhan" loading="lazy" class="mt-4 max-h-60 w-full rounded-2xl object-cover">@endif
                        @if(!$keluhan->jawaban)
                            <form method="POST" action="{{ route('dokter.keluhan.jawab', $keluhan) }}" class="mt-4">@csrf<textarea name="jawaban" rows="4" maxlength="5000" required placeholder="Tulis satu jawaban yang jelas..." class="w-full rounded-2xl border-emerald-100 text-sm focus:border-emerald-400 focus:ring-emerald-200"></textarea><button class="mt-3 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-500">Kirim Jawaban</button></form>
                        @else
                            <div class="mt-4 rounded-2xl bg-emerald-50 p-4"><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">Jawaban tersimpan</p><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $keluhan->jawaban }}</p></div>
                        @endif
                    </article>
                @empty
                    <div class="rounded-[26px] border border-dashed border-emerald-200 bg-white/70 p-8 text-center text-sm text-slate-500">Belum ada keluhan gratis.</div>
                @endforelse
            </div>
        </section>
    </div>
</main>
</body>
</html>
