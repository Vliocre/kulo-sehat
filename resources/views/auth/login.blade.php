<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login</title>

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
                        Mari Bergabung Bersama Kami
                    </h1>
                    <p class="mt-4 text-white/90">
                        Selamat datang di KuloSehat. Kami menyediakan berbagai artikel dan panduan informatif mengenai penyakit umum, gejala, cara pencegahan, serta penanganan awal yang bisa Anda lakukan.
                    </p>
                </div>

                <!-- Kolom Form Login di Kanan -->
                <div class="w-full">
                    <div class="bg-white/95 backdrop-blur-sm p-8 sm:p-12 rounded-2xl shadow-2xl">

                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang!</h2>
                            <p class="text-gray-500">Silakan masuk ke akun Anda</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="sr-only">Email</label>
                                <input id="email"
                                    class="block mt-1 w-full border-gray-300 rounded-full shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-3"
                                    placeholder="Masukkan Email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="mt-4">
                                <label for="password" class="sr-only">Password</label>
                                <input id="password"
                                    class="block mt-1 w-full border-gray-300 rounded-full shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-3"
                                    placeholder="Masukkan Password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- 🔹 Pilihan Role (User / Dokter) -->
                            <div class="mt-5">
                                <p class="text-sm font-medium text-gray-700 mb-2">Masuk sebagai</p>
                                <div class="flex gap-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio"
                                               name="role"
                                               value="user"
                                               class="text-emerald-600 focus:ring-emerald-500"
                                               checked>
                                        <span class="ml-2 text-sm text-gray-700">User</span>
                                    </label>

                                    <label class="inline-flex items-center">
                                        <input type="radio"
                                               name="role"
                                               value="dokter"
                                               class="text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-2 text-sm text-gray-700">Dokter</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="block mt-4">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me"
                                           type="checkbox"
                                           class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                                           name="remember">
                                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                @if (Route::has('password.request'))
                                    <a class="underline text-sm text-gray-600 hover:text-gray-900"
                                       href="{{ route('password.request') }}">
                                        Lupa password?
                                    </a>
                                @endif

                                <button type="submit"
                                    class="ms-3 text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-medium rounded-full text-sm px-6 py-3">
                                    Log in
                                </button>
                            </div>

                            <div class="text-center mt-6">
                                <p class="text-sm text-gray-600">
                                    Belum punya akun?
                                    <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-500">
                                        Daftar di sini
                                    </a>
                                </p>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
