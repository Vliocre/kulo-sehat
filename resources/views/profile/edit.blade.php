<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya - Helavio</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
        .profile-hero {
            background: radial-gradient(circle at 20% 20%, rgba(16,185,129,0.25), transparent 55%),
                        radial-gradient(circle at 80% 0%, rgba(190,242,100,0.25), transparent 45%);
        }
        .profile-card {
            box-shadow: 0 25px 45px rgba(15, 118, 110, 0.12);
            border-radius: 28px;
        }
        .page-bg {
            background-color: #f5faf7;
            background-image:
                radial-gradient(18% 24% at 15% 18%, rgba(52, 211, 153, 0.08), transparent 50%),
                radial-gradient(22% 26% at 82% 10%, rgba(34, 197, 94, 0.07), transparent 48%),
                linear-gradient(135deg, #f9fdfb 0%, #edf6f1 45%, #e9f3ef 100%);
        }
    </style>
</head>
<body class="page-bg font-sans antialiased min-h-screen">

<x-public-navbar :show-search="false" />

<main class="pt-32 pb-20">
    <section class="max-w-6xl mx-auto px-6 lg:px-8">

        @if(session('status') === 'premium-deactivated')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/></svg>
                Premium berhasil dinonaktifkan. Akun dan riwayat konsultasi Anda tetap tersimpan.
            </div>
        @endif

        <!-- HEADER -->
        <div class="profile-card bg-white/95 backdrop-blur rounded-[32px] p-10">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex-1 text-center md:text-left">
                    <p class="text-sm uppercase tracking-[0.35em] text-gray-400 font-semibold">Pengaturan Akun</p>
                    <h1 class="mt-4 text-3xl md:text-4xl font-bold text-gray-900">Atur Profil &amp; Keamanan Anda</h1>
                    <p class="mt-3 text-gray-600 leading-relaxed">
                        Perbarui informasi pribadi, ubah kata sandi, dan kelola keamanan akun Anda dengan tampilan yang lebih bersih dan konsisten dengan pengalaman Helavio.
                    </p>
                </div>
                <div class="flex-1">
                    <div class="profile-hero relative rounded-[32px] bg-white p-8 {{ Auth::user()->hasActivePremium() ? 'border border-amber-300 ring-4 ring-amber-100/70 shadow-[0_18px_50px_rgba(217,119,6,.12)]' : '' }}">
                        @if(Auth::user()->hasActivePremium())
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                <span class="inline-flex items-center gap-2 whitespace-nowrap rounded-full border border-amber-300 bg-gradient-to-r from-amber-50 via-white to-amber-50 px-5 py-2 text-amber-700 shadow-[0_8px_24px_rgba(217,119,6,.18)]">
                                    <svg class="h-4 w-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                        <path d="M3.5 8.5 7 4h10l3.5 4.5L12 20 3.5 8.5Z" stroke-width="1.7" stroke-linejoin="round"/>
                                        <path d="m7 4 2 4.5L12 20l3-11.5L17 4M3.5 8.5h17" stroke-width="1.25" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="font-serif text-sm font-semibold italic tracking-[.12em]">Premium</span>
                                </span>
                            </div>
                        @endif
                        <div class="text-center">
                            <p class="text-lg font-semibold text-gray-800 {{ Auth::user()->hasActivePremium() ? 'mt-2' : '' }}">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            <div class="mt-6 flex items-center justify-center gap-3 text-sm text-gray-600">
                                <span class="px-4 py-1 rounded-full bg-green-50 text-green-600 font-semibold capitalize">
                                    {{ Auth::user()->role ?? 'pengguna' }}
                                </span>
                                <span>{{ __('Bergabung sejak') }} {{ Auth::user()->created_at->isoFormat('D MMMM YYYY') }}</span>
                            </div>
                            @if(Auth::user()->hasActivePremium())
                                <div class="mt-6 border-t border-amber-200/80 pt-5">
                                    <p class="text-xs text-amber-700">Aktif sampai {{ Auth::user()->premium_until->translatedFormat('d M Y') }}</p>
                                    <form method="POST" action="{{ route('profile.premium.deactivate') }}" onsubmit="return confirm('Nonaktifkan Premium sekarang? Riwayat konsultasi Anda akan tetap tersimpan.');" class="mt-3">
                                        @csrf
                                        @method('PATCH')
                                        <button class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-4 py-2 text-xs font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-50">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 1 1-12.728 0M12 3v9"/></svg>
                                            Nonaktifkan Premium
                                        </button>
                                    </form>
                                    <p class="mt-2 text-[11px] text-slate-400">Riwayat konsultasi tetap tersimpan.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="mt-12 space-y-8">

            <!-- INFORMASI PRIBADI -->
            <div class="bg-white/95 backdrop-blur rounded-[28px] p-8 shadow-[0_25px_45px_rgba(15,118,110,0.08)]">
                @include('profile.partials.update-profile-information-form')

                <!-- TOGGLE SECTION -->
                <div x-data="{ openSecurity: false, openDelete: false }" class="space-y-4 mt-8">

                    @if(Auth::user()->role === 'pengguna')
                        <a href="{{ route('premium.conversations.index') }}"
                            class="flex w-full items-center justify-between rounded-2xl border border-emerald-100 bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-4 font-semibold text-white shadow-lg shadow-emerald-500/15 transition hover:-translate-y-0.5 hover:shadow-xl">
                            <span>
                                <span class="block">Percakapan Premium</span>
                                <span class="mt-0.5 block text-xs font-normal text-emerald-100">Buka konsultasi aktif dan riwayat percakapan dokter</span>
                            </span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg>
                        </a>
                    @endif

                    <a href="{{ route('profile.saved-articles') }}"
                        class="block w-full px-5 py-3 rounded-2xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition font-semibold">
                        Artikel Tersimpan
                    </a>

                    <a href="{{ route('profile.symptoms') }}"
                        class="block w-full px-5 py-3 rounded-2xl bg-sky-50 text-sky-700 hover:bg-sky-100 transition font-semibold">
                        Kategori Gejala Penyakit
                    </a>

                    <!-- BUTTON KEAMANAN -->
                    <button @click="openSecurity = !openSecurity"
                        class="w-full text-left px-5 py-3 rounded-2xl bg-gray-100 hover:bg-gray-200 transition font-semibold">
                        🔒 Pengaturan Keamanan
                    </button>

                    <!-- FORM PASSWORD -->
                    <div x-show="openSecurity" x-transition class="mt-4">
                        <div class="bg-white/95 backdrop-blur rounded-[28px] p-6 shadow-[0_25px_45px_rgba(15,118,110,0.08)]">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- BUTTON DELETE -->
                    <button @click="openDelete = !openDelete"
                        class="w-full text-left px-5 py-3 rounded-2xl bg-red-50 text-red-600 hover:bg-red-100 transition font-semibold">
                        ⚠️ Hapus Akun
                    </button>

                    <!-- FORM DELETE -->
                    <div x-show="openDelete" x-transition class="mt-4">
                        <div class="bg-white/95 backdrop-blur rounded-[28px] p-6 shadow-[0_25px_45px_rgba(15,118,110,0.08)]">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
</main>

</body>
</html>
