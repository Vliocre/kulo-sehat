<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Akun Premium - Helavio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak]{display:none!important}
        .page-bg{background-color:#f5faf7;background-image:radial-gradient(18% 24% at 15% 18%,rgba(52,211,153,.09),transparent 50%),radial-gradient(22% 26% at 82% 10%,rgba(34,197,94,.08),transparent 48%),linear-gradient(135deg,#f9fdfb 0%,#edf6f1 45%,#e9f3ef 100%)}
    </style>
</head>
<body class="page-bg min-h-screen font-sans antialiased text-slate-900">
<x-public-navbar :show-search="false" />

<main class="pb-16 pt-28">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-[32px] bg-gradient-to-r from-slate-950 via-emerald-900 to-emerald-600 p-7 text-white shadow-[0_24px_65px_rgba(15,118,110,.25)] sm:p-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(255,255,255,.16),transparent_32%)]"></div>
            <div class="relative max-w-3xl">
                <p class="text-xs font-bold uppercase tracking-[.26em] text-emerald-200">Helavio Premium</p>
                <h1 class="mt-3 text-3xl font-bold sm:text-4xl">Buat dan Aktifkan Akun Premium</h1>
                <p class="mt-3 leading-7 text-emerald-50/85">Nikmati konsultasi yang lebih leluasa, pesan lanjutan dengan dokter, lampiran privat, dan riwayat kesehatan yang tersimpan rapi.</p>
                @if($selectedDoctor)
                    <div class="mt-5 inline-flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/20">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white font-bold text-emerald-700">{{ collect(explode(' ', $selectedDoctor->name))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('') }}</span>
                        <span><span class="block text-xs text-emerald-200">Dokter pilihan Anda</span><strong>{{ $selectedDoctor->name }}</strong></span>
                    </div>
                @endif
            </div>
        </section>

        @if($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        @if($user->hasActivePremium())
            <div class="mt-6 flex flex-col gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 sm:flex-row sm:items-center sm:justify-between">
                <div><strong>Akun Anda sudah Premium.</strong> Masa aktif saat ini sampai {{ $user->premium_until->translatedFormat('d M Y') }}.</div>
                <span class="font-semibold">Paket baru akan menambah masa aktif.</span>
            </div>
        @endif

        <section class="mt-9">
            <div class="text-center">
                <p class="text-xs font-bold uppercase tracking-[.22em] text-emerald-700">Pilihan akun premium</p>
                <h2 class="mt-2 text-2xl font-bold sm:text-3xl">Pilih masa aktif akun Anda</h2>
                <p class="mt-2 text-sm text-slate-600">Semua paket memperoleh fitur premium yang sama. Anda hanya perlu memilih durasinya.</p>
            </div>

            <div x-data="{ activePlan: 'quarterly' }" class="mt-7 grid gap-6 lg:grid-cols-3">
                @foreach($plans as $key => $plan)
                    <article
                        tabindex="0"
                        @mouseenter="activePlan = '{{ $key }}'"
                        @focusin="activePlan = '{{ $key }}'"
                        :class="activePlan === '{{ $key }}'
                            ? 'border-emerald-400 ring-4 ring-emerald-100 -translate-y-1 shadow-[0_24px_55px_rgba(16,185,129,.16)]'
                            : 'border-emerald-100 shadow-[0_18px_45px_rgba(15,23,42,.08)]'"
                        class="relative flex cursor-pointer flex-col rounded-[28px] border bg-white p-6 transition-all duration-300 ease-out focus:outline-none"
                    >
                        <span
                            x-show="activePlan === '{{ $key }}'"
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute -top-3 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-emerald-600 px-4 py-1 text-xs font-bold text-white shadow-md"
                            x-text="'{{ !empty($plan['recommended']) ? 'Paling diminati' : 'Paket pilihan' }}'"
                        ></span>
                        <p class="text-sm font-bold text-emerald-700">{{ $plan['name'] }}</p>
                        <div class="mt-3 flex items-end gap-2"><strong class="text-3xl text-slate-950">{{ $plan['price'] }}</strong><span class="pb-1 text-sm text-slate-500">/ {{ $plan['duration'] }}</span></div>
                        <p class="mt-4 min-h-[3rem] text-sm leading-6 text-slate-600">{{ $plan['description'] }}</p>
                        <ul class="mt-5 flex-1 space-y-3 text-sm text-slate-700">
                            <li class="flex gap-2"><span class="text-emerald-600">&#10003;</span> Chat lanjutan dengan dokter</li>
                            <li class="flex gap-2"><span class="text-emerald-600">&#10003;</span> Lampiran gambar privat</li>
                            <li class="flex gap-2"><span class="text-emerald-600">&#10003;</span> Riwayat konsultasi tersimpan</li>
                            <li class="flex gap-2"><span class="text-emerald-600">&#10003;</span> Pilihan dokter terverifikasi</li>
                        </ul>
                        <form method="POST" action="{{ route('premium.activate') }}" class="mt-6">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $key }}">
                            @if($selectedDoctor)<input type="hidden" name="doctor_id" value="{{ $selectedDoctor->id }}">@endif
                            <button
                                :class="activePlan === '{{ $key }}' ? 'bg-emerald-600 shadow-lg shadow-emerald-500/20 hover:bg-emerald-500' : 'bg-slate-900 hover:bg-emerald-700'"
                                class="w-full rounded-2xl px-5 py-3 text-sm font-bold text-white transition-all duration-300"
                            >Daftar {{ $plan['name'] }}</button>
                        </form>
                    </article>
                @endforeach
            </div>

            <p class="mt-7 text-center text-xs leading-5 text-slate-500">Dengan mendaftar, akun premium langsung aktif. Integrasi pembayaran dapat ditambahkan pada tahap berikutnya.</p>
        </section>
    </div>
</main>
</body>
</html>
