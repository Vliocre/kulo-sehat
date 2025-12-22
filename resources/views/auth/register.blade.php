<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Register</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl w-full grid md:grid-cols-2 items-center gap-8">

                <!-- Kolom Teks di Kiri -->
                <div class="text-white">
                    <p class="text-lg">Temukan Informasi Kesehatan Terpercaya</p>
                    <h1 class="text-5xl font-bold mt-2 leading-tight">
                        Buat Akun Baru Anda
                    </h1>
                    <p class="mt-4 text-white/90">
                        Bergabunglah dengan KuloSehat untuk mendapatkan akses penuh ke artikel kesehatan terverifikasi, tips gaya hidup sehat, dan berbagai informasi bermanfaat lainnya.
                    </p>
                </div>

                <!-- Kolom Form Register di Kanan -->
                <div class="w-full">
                    <div class="bg-white/95 backdrop-blur-sm p-8 sm:p-12 rounded-2xl shadow-2xl">

                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-gray-800">Daftar Akun</h2>
                            <p class="text-gray-500">Isi data di bawah ini untuk memulai</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div>
                                <label for="name" class="sr-only">Nama</label>
                                <input id="name" class="block mt-1 w-full border-gray-300 rounded-full shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3" placeholder="Nama Lengkap" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email Address -->
                            <div class="mt-4">
                                <label for="email" class="sr-only">Email</label>
                                <input id="email" class="block mt-1 w-full border-gray-300 rounded-full shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3" placeholder="Alamat Email" type="email" name="email" :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="mt-4">
                                <label for="password" class="sr-only">Password</label>
                                <input id="password" class="block mt-1 w-full border-gray-300 rounded-full shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3" placeholder="Password"
                                                type="password"
                                                name="password"
                                                required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="mt-4">
                                <label for="password_confirmation" class="sr-only">Konfirmasi Password</label>
                                <input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-full shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3" placeholder="Konfirmasi Password"
                                                type="password"
                                                name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" href="{{ route('login') }}">
                                    {{ __('Sudah ada akun?') }}
                                </a>

                                <button type="submit" class="ms-4 text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-6 py-3 text-center">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
