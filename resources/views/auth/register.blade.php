<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 px-4">
    <div class="max-w-4xl w-full grid md:grid-cols-2 items-center gap-8">

        <!-- KIRI -->
        <div class="text-white">
            <p class="text-lg">Temukan Informasi Kesehatan Terpercaya</p>
            <h1 class="text-5xl font-bold mt-2 leading-tight">
                Buat Akun Baru Anda
            </h1>
            <p class="mt-4 text-white/90">
                Bergabunglah dengan KuloSehat untuk mendapatkan akses penuh ke
                informasi kesehatan terpercaya.
            </p>
        </div>

        <!-- KANAN -->
        <div class="bg-white/95 backdrop-blur-sm p-8 sm:p-12 rounded-2xl shadow-2xl">

            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Akun</h2>
                <p class="text-gray-500">Isi data di bawah ini untuk memulai</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- NAME -->
                <div>
                    <input type="text" name="name" required autofocus
                           placeholder="Nama Lengkap"
                           class="w-full border-gray-300 rounded-full px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                    <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                </div>

                <!-- EMAIL -->
                <div class="mt-4">
                    <input type="email" name="email" required
                           placeholder="Alamat Email"
                           class="w-full border-gray-300 rounded-full px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                </div>

                <!-- PASSWORD -->
                <div class="mt-4">
                    <input type="password" name="password" required
                           placeholder="Password"
                           class="w-full border-gray-300 rounded-full px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                    <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                </div>

                <!-- CONFIRM PASSWORD -->
                <div class="mt-4">
                    <input type="password" name="password_confirmation" required
                           placeholder="Konfirmasi Password"
                           class="w-full border-gray-300 rounded-full px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <!-- ROLE -->
                <div class="mt-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Daftar sebagai
                    </label>

                    <div class="flex gap-6">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="role" value="user" checked>
                            <span>User</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="role" value="dokter">
                            <span>Dokter</span>
                        </label>
                    </div>

                    <x-input-error :messages="$errors->get('role')" class="mt-2"/>
                </div>

                <!-- ACTION -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-600 underline">
                        Sudah ada akun?
                    </a>

                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full">
                        Register
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
</body>
</html>
