<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keluhan - KuloSehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak]{display:none!important}
        .page-bg{background-color:#f5faf7;background-image:radial-gradient(18% 24% at 15% 18%,rgba(52,211,153,.08),transparent 50%),radial-gradient(22% 26% at 82% 10%,rgba(34,197,94,.07),transparent 48%),linear-gradient(135deg,#f9fdfb 0%,#edf6f1 45%,#e9f3ef 100%)}
    </style>
</head>
<body class="page-bg min-h-screen font-sans antialiased text-slate-900">
<x-public-navbar />

@php
    $pageMode = $pageMode ?? 'consultations';
    $totalKeluhan = $keluhans->count();
    $menunggu = $keluhans->where('status', 'menunggu')->count();
    $dijawab = $keluhans->where('status', 'dijawab')->count();
    $hasActivePremium = $hasActivePremium ?? $premiumKeluhans->whereIn('premium_status', ['requested', 'active'])->isNotEmpty();
    $premiumLabels = ['requested' => 'Menunggu dokter', 'active' => 'Sedang aktif', 'closed' => 'Selesai', 'rejected' => 'Ditolak'];
    $premiumTones = ['requested' => 'bg-amber-100 text-amber-700', 'active' => 'bg-emerald-100 text-emerald-700', 'closed' => 'bg-slate-100 text-slate-600', 'rejected' => 'bg-rose-100 text-rose-700'];
@endphp

<main class="relative overflow-hidden pb-12 pt-28">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_15%_12%,rgba(16,185,129,0.12),transparent_28%)]"></div>
    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-[30px] bg-gradient-to-r from-emerald-800 via-emerald-600 to-teal-500 p-6 text-white shadow-[0_24px_65px_rgba(16,185,129,.3)] sm:p-8">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_15%,rgba(255,255,255,.2),transparent_35%)]"></div>
            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[.28em] text-emerald-100">{{ $pageMode === 'doctors' ? 'Dokter Terverifikasi' : ($pageMode === 'premium-registration' ? 'Pendaftaran Premium' : 'Pusat Konsultasi') }}</p>
                        <span class="rounded-full px-3 py-1 text-[11px] font-bold {{ $user->hasActivePremium() ? 'bg-white text-emerald-700' : 'bg-white/15 text-white ring-1 ring-white/25' }}">
                            {{ $user->hasActivePremium() ? 'Premium aktif sampai '.$user->premium_until->translatedFormat('d M Y') : 'Akun gratis' }}
                        </span>
                    </div>
                    <h1 class="mt-3 text-3xl font-bold sm:text-4xl">{{ $pageMode === 'doctors' ? 'Pilih Dokter' : ($pageMode === 'premium-registration' ? 'Aktifkan Akun Premium' : 'Keluhan Saya') }}</h1>
                    <p class="mt-2 max-w-2xl text-emerald-50/90">{{ $pageMode === 'doctors' ? 'Temukan dokter yang telah diverifikasi dan mulai konsultasi sesuai kebutuhan Anda.' : ($pageMode === 'premium-registration' ? 'Selesaikan pendaftaran untuk melanjutkan konsultasi dengan dokter pilihan Anda.' : 'Kirim keluhan gratis untuk satu jawaban, atau lanjutkan percakapan melalui Keluhan Premium.') }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-white/10 px-5 py-2.5 text-sm font-semibold ring-1 ring-white/30 transition hover:bg-white/20">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </section>

        @if(session('ok'))
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('ok') }}</div>
        @endif
        @if($errors->any())
            <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="list-inside list-disc space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @if($pageMode === 'premium-registration')
            <section id="daftar-premium" class="mx-auto mt-8 max-w-3xl">
                <div class="overflow-hidden rounded-[30px] border border-emerald-100 bg-white shadow-[0_22px_55px_rgba(15,23,42,.1)]">
                    <div class="bg-gradient-to-r from-slate-950 via-emerald-900 to-emerald-600 p-6 text-white sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-[.22em] text-emerald-200">Paket 30 Hari</p>
                        <h2 class="mt-2 text-2xl font-bold sm:text-3xl">Keluhan Premium KuloSehat</h2>
                        <p class="mt-3 text-sm leading-6 text-emerald-50/85">Buka akses percakapan berkelanjutan dan konsultasikan keluhan Anda dengan dokter yang dipilih.</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-4 rounded-2xl bg-emerald-50 p-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 font-bold text-white">{{ collect(explode(' ', $selectedDoctor->name))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('') }}</div>
                            <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">Dokter pilihan</p><h3 class="font-bold text-slate-900">{{ $selectedDoctor->name }}</h3><p class="text-sm text-slate-500">{{ $selectedDoctor->specialty ?: 'Dokter Umum' }}</p></div>
                        </div>
                        <div class="mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl border border-slate-100 p-4 text-sm text-slate-600"><strong class="block text-slate-900">Chat lanjutan</strong>Pesan tidak berhenti pada satu jawaban.</div>
                            <div class="rounded-2xl border border-slate-100 p-4 text-sm text-slate-600"><strong class="block text-slate-900">Lampiran privat</strong>Gambar hanya dapat dibuka oleh peserta.</div>
                            <div class="rounded-2xl border border-slate-100 p-4 text-sm text-slate-600"><strong class="block text-slate-900">Riwayat aman</strong>Percakapan tetap tersimpan setelah selesai.</div>
                        </div>
                        <p class="mt-5 rounded-2xl bg-amber-50 px-4 py-3 text-xs leading-5 text-amber-700">Pendaftaran ini mengaktifkan akses premium selama 30 hari. Integrasi pembayaran dapat ditambahkan pada tahap berikutnya.</p>
                        <form method="POST" action="{{ route('premium.activate') }}" class="mt-5">
                            @csrf
                            <input type="hidden" name="doctor_id" value="{{ $selectedDoctor->id }}">
                            <button class="w-full rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-500">Daftar Premium dan lanjutkan</button>
                        </form>
                        <a href="{{ route('doctors.index') }}" class="mt-3 block text-center text-sm font-semibold text-slate-500 hover:text-emerald-700">Kembali pilih dokter lain</a>
                    </div>
                </div>
            </section>
        @endif

        @if($pageMode === 'consultations')
        <section id="percakapan-premium" class="mt-8 scroll-mt-28">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.22em] text-emerald-700">Layanan Premium</p>
                    <h2 class="mt-1 text-2xl font-bold sm:text-3xl">Percakapan dengan dokter pilihan</h2>
                </div>
                <span class="text-sm text-slate-500">{{ $premiumKeluhans->count() }} riwayat konsultasi</span>
            </div>

            <div class="mt-5 space-y-5">
                @forelse($premiumKeluhans as $consultation)
                    <article class="overflow-hidden rounded-[28px] border border-emerald-100 bg-white shadow-[0_18px_45px_rgba(15,23,42,.08)]">
                        <header class="flex flex-col gap-4 border-b border-emerald-50 bg-gradient-to-r from-emerald-50 to-white p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 font-bold text-white">
                                    {{ collect(explode(' ', $consultation->doctor->name ?? 'Dokter'))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('') }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">{{ $consultation->doctor?->specialty ?: 'Dokter Umum' }}</p>
                                    <h3 class="font-bold text-slate-900">{{ $consultation->doctor->name ?? 'Dokter tidak tersedia' }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ $consultation->judul }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $premiumTones[$consultation->premium_status] ?? 'bg-slate-100 text-slate-600' }}">{{ $premiumLabels[$consultation->premium_status] ?? ucfirst($consultation->premium_status) }}</span>
                                @if($consultation->premium_status === 'active')
                                    <form method="POST" action="{{ route('keluhan.premium.close', $consultation) }}" onsubmit="return confirm('Selesaikan konsultasi ini? Riwayat pesan tetap tersimpan.');">
                                        @csrf @method('PATCH')
                                        <button class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-bold text-slate-600 hover:border-rose-200 hover:text-rose-600">Selesaikan</button>
                                    </form>
                                @endif
                            </div>
                        </header>

                        <div class="max-h-[430px] space-y-3 overflow-y-auto bg-slate-50/60 p-4 sm:p-6">
                            @foreach($consultation->pesanPremium->sortBy('created_at') as $message)
                                @php $mine = $message->sender_id === $user->id; @endphp
                                <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[88%] rounded-2xl px-4 py-3 sm:max-w-[72%] {{ $mine ? 'rounded-br-md bg-emerald-600 text-white' : 'rounded-bl-md border border-slate-100 bg-white text-slate-700 shadow-sm' }}">
                                        <p class="whitespace-pre-line text-sm leading-6">{{ $message->pesan }}</p>
                                        @if($message->gambar)
                                            <a href="{{ route('keluhan.premium.attachment', [$consultation, $message]) }}" target="_blank"><img src="{{ route('keluhan.premium.attachment', [$consultation, $message]) }}" alt="Lampiran pesan" loading="lazy" class="mt-2 max-h-56 w-full rounded-xl object-cover"></a>
                                        @endif
                                        <p class="mt-1.5 text-[10px] {{ $mine ? 'text-emerald-100' : 'text-slate-400' }}">{{ $message->created_at->format('d M, H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($consultation->premium_status === 'active')
                            <form method="POST" action="{{ route('keluhan.premium.message', $consultation) }}" enctype="multipart/form-data" class="border-t border-slate-100 bg-white p-4 sm:p-5">
                                @csrf
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                                    <textarea name="pesan" rows="2" maxlength="5000" placeholder="Tulis pesan untuk dokter..." class="w-full flex-1 resize-none rounded-2xl border-slate-200 text-sm focus:border-emerald-400 focus:ring-emerald-200"></textarea>
                                    <label class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-xs font-semibold text-slate-600 hover:border-emerald-300 hover:text-emerald-700">Lampirkan gambar<input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="sr-only"></label>
                                    <button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-emerald-500/20 hover:bg-emerald-500">Kirim</button>
                                </div>
                            </form>
                        @elseif($consultation->premium_status === 'requested')
                            <p class="border-t border-amber-100 bg-amber-50 px-5 py-4 text-sm text-amber-700">Dokter belum menerima permintaan. Percakapan akan terbuka setelah disetujui.</p>
                        @else
                            <p class="border-t border-slate-100 bg-slate-50 px-5 py-4 text-sm text-slate-500">Percakapan telah {{ $consultation->premium_status === 'rejected' ? 'ditolak' : 'selesai' }} dan hanya dapat dibaca.</p>
                        @endif
                    </article>
                @empty
                    <div class="rounded-[28px] border border-dashed border-emerald-200 bg-white/70 p-8 text-center"><p class="font-semibold text-slate-800">Belum ada Keluhan Premium</p><p class="mt-1 text-sm text-slate-500">Pilih dokter terverifikasi di bawah untuk memulai.</p></div>
                @endforelse
            </div>
        </section>
        @endif

        @if($pageMode === 'doctors')
        <section id="daftar-dokter" class="mt-10 scroll-mt-28">
            <div><p class="text-xs font-bold uppercase tracking-[.22em] text-emerald-700">Dokter Terverifikasi</p><h2 class="mt-1 text-2xl font-bold sm:text-3xl">Pilih dokter Anda</h2><p class="mt-2 max-w-2xl text-sm text-slate-600">Hanya dokter yang telah disetujui admin yang ditampilkan.</p></div>
            <div class="mt-5 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse($doctors as $doctor)
                    <article x-data="{ open: {{ $user->hasActivePremium() && ! $hasActivePremium && ($selectedDoctorId ?? 0) === $doctor->id ? 'true' : 'false' }} }" class="rounded-[26px] border border-emerald-100 bg-white p-5 shadow-[0_14px_35px_rgba(15,23,42,.07)] transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-lg font-bold text-white shadow-md shadow-emerald-500/20">{{ collect(explode(' ', $doctor->name))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('') }}</div>
                            <div class="min-w-0"><div class="flex items-center gap-1.5"><h3 class="truncate font-bold">{{ $doctor->name }}</h3><span class="text-emerald-500">&#10003;</span></div><p class="text-sm font-semibold text-emerald-700">{{ $doctor->specialty ?: 'Dokter Umum' }}</p><p class="mt-1 text-xs text-slate-500">{{ $doctor->workplace ?: 'Konsultasi online KuloSehat' }}</p></div>
                        </div>
                        <p class="mt-4 line-clamp-3 min-h-[3.75rem] text-sm leading-5 text-slate-600">{{ $doctor->about ?: 'Siap membantu memberikan informasi dan arahan kesehatan melalui konsultasi KuloSehat.' }}</p>

                        @if($user->hasActivePremium() && ! $hasActivePremium)
                            <button type="button" @click="open = !open" class="mt-4 w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-500">Mulai Keluhan Premium</button>
                            <form x-show="open" x-cloak x-transition method="POST" action="{{ route('keluhan.premium.store', $doctor) }}" enctype="multipart/form-data" class="mt-4 space-y-3 border-t border-slate-100 pt-4">
                                @csrf
                                <input name="judul" maxlength="150" required placeholder="Topik keluhan" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-400 focus:ring-emerald-200">
                                <textarea name="pesan" rows="4" maxlength="5000" required placeholder="Ceritakan kondisi dan pertanyaan Anda..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-400 focus:ring-emerald-200"></textarea>
                                <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-slate-200 p-2 text-xs">
                                <button class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Kirim ke dokter</button>
                            </form>
                        @elseif($hasActivePremium)
                            <button disabled class="mt-4 w-full cursor-not-allowed rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-400">Selesaikan konsultasi aktif dahulu</button>
                        @else
                            <a href="{{ route('premium.register', ['doctor' => $doctor->id]) }}" class="mt-4 block rounded-xl bg-slate-900 px-4 py-2.5 text-center text-sm font-bold text-white transition hover:bg-emerald-700">Daftar Premium untuk konsultasi</a>
                        @endif
                    </article>
                @empty
                    <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-emerald-200 bg-white p-7 text-center text-sm text-slate-500">Belum ada dokter terverifikasi.</div>
                @endforelse
            </div>
        </section>
        @endif

        @if($pageMode === 'consultations')
        <section class="mt-12 border-t border-emerald-100 pt-10">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div><p class="text-xs font-bold uppercase tracking-[.22em] text-emerald-700">Tetap Gratis</p><h2 class="mt-1 text-2xl font-bold sm:text-3xl">Keluhan satu kali jawab</h2></div>
                <div class="flex gap-2 text-xs font-bold"><span class="rounded-full bg-white px-3 py-1.5 text-slate-600">{{ $totalKeluhan }} total</span><span class="rounded-full bg-amber-100 px-3 py-1.5 text-amber-700">{{ $menunggu }} menunggu</span><span class="rounded-full bg-emerald-100 px-3 py-1.5 text-emerald-700">{{ $dijawab }} dijawab</span></div>
            </div>
            <div class="mt-5 grid gap-6 lg:grid-cols-[.8fr_1.2fr]">
                <div class="h-fit rounded-[28px] border border-emerald-100 bg-white p-6 shadow-[0_18px_42px_rgba(15,23,42,.08)]">
                    <h3 class="text-lg font-bold">Kirim Keluhan Gratis</h3><p class="mt-1 text-sm text-slate-500">Dokter akan memberikan satu jawaban.</p>
                    <form method="POST" action="{{ route('keluhan.store') }}" enctype="multipart/form-data" class="mt-5 space-y-3">
                        @csrf
                        <input name="judul" value="{{ old('judul') }}" maxlength="255" required placeholder="Judul keluhan" class="w-full rounded-xl border-emerald-100 text-sm focus:border-emerald-400 focus:ring-emerald-200">
                        <textarea name="isi" rows="5" maxlength="5000" required placeholder="Tulis keluhan dengan jelas..." class="w-full rounded-xl border-emerald-100 text-sm focus:border-emerald-400 focus:ring-emerald-200">{{ old('isi') }}</textarea>
                        <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-emerald-100 p-2 text-sm">
                        <button class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-500">Kirim Keluhan Gratis</button>
                    </form>
                </div>
                <div class="space-y-4">
                    @forelse($keluhans as $keluhan)
                        <article class="rounded-[26px] border border-emerald-100 bg-white p-5 shadow-[0_14px_35px_rgba(15,23,42,.07)] sm:p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">Keluhan Gratis</p><h3 class="mt-1 text-lg font-bold">{{ $keluhan->judul }}</h3><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $keluhan->isi }}</p></div>
                                <div class="flex shrink-0 items-center gap-2"><span class="rounded-full px-3 py-1 text-xs font-bold {{ $keluhan->status === 'dijawab' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($keluhan->status) }}</span><form method="POST" action="{{ route('keluhan.destroy', $keluhan) }}" onsubmit="return confirm('Hapus keluhan ini?');">@csrf @method('DELETE')<button class="rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-600 hover:bg-rose-100">Hapus</button></form></div>
                            </div>
                            @if($keluhan->gambar)<img src="{{ asset('storage/'.$keluhan->gambar) }}" alt="Lampiran keluhan" loading="lazy" class="mt-4 max-h-60 w-full rounded-2xl object-cover">@endif
                            @if($keluhan->jawaban)<div class="mt-4 rounded-2xl bg-emerald-50 p-4"><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">Jawaban Dokter</p><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $keluhan->jawaban }}</p></div>@else<p class="mt-4 text-sm text-slate-500">Menunggu satu jawaban dari dokter.</p>@endif
                        </article>
                    @empty
                        <div class="rounded-[26px] border border-dashed border-emerald-200 bg-white/70 p-8 text-center text-sm text-slate-500">Belum ada keluhan gratis.</div>
                    @endforelse
                </div>
            </div>
        </section>
        @endif
    </div>
</main>
</body>
</html>
