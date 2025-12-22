<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya - KuloSehat</title>

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
            <div class="profile-card bg-white/95 backdrop-blur rounded-[32px] p-10">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-1 text-center md:text-left">
                        <p class="text-sm uppercase tracking-[0.35em] text-gray-400 font-semibold">Pengaturan Akun</p>
                        <h1 class="mt-4 text-3xl md:text-4xl font-bold text-gray-900">Atur Profil &amp; Keamanan Anda</h1>
                        <p class="mt-3 text-gray-600 leading-relaxed">
                            Perbarui informasi pribadi, ubah kata sandi, dan kelola keamanan akun Anda dengan tampilan yang lebih bersih dan konsisten dengan pengalaman KuloSehat.
                        </p>
                    </div>
                    <div class="flex-1">
                        <div class="profile-hero rounded-[32px] bg-white p-8">
                            <div class="text-center">
                                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                <div class="mt-6 flex items-center justify-center gap-3 text-sm text-gray-600">
                                    <span class="px-4 py-1 rounded-full bg-green-50 text-green-600 font-semibold capitalize">{{ Auth::user()->role ?? 'pengguna' }}</span>
                                    <span>{{ __('Bergabung sejak') }} {{ Auth::user()->created_at->isoFormat('D MMMM YYYY') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 grid gap-8 lg:grid-cols-2">
                <div class="bg-white/95 backdrop-blur rounded-[28px] p-8 shadow-[0_25px_45px_rgba(15,118,110,0.08)]">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-white/95 backdrop-blur rounded-[28px] p-8 shadow-[0_25px_45px_rgba(15,118,110,0.08)]">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="bg-white/95 backdrop-blur rounded-[28px] p-8 shadow-[0_25px_45px_rgba(15,118,110,0.08)] lg:col-span-2">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </section>
    </main>

</body>
</html>
