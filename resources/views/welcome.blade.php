<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KuloSehat - Informasi Kesehatan Terpercaya</title>
    <!-- aruzal -->

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
        .wave-divider {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 100px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='1' d='M0,192L80,176C160,160,320,128,480,133.3C640,139,800,181,960,197.3C1120,213,1280,203,1360,197.3L1440,192L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            background-repeat: no-repeat;
        }

        /* CSS untuk membuat efek fade transparan di bawah gambar robot */
        .robot-mask {
            mask-image: linear-gradient(to bottom, black 65%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 80%, transparent 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-white">

    <x-public-navbar :show-search="false" />

    <main>
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-green-400 to-lime-500 text-white pt-36 pb-40 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid md:grid-cols-2 gap-8 items-center">
                <!-- Teks di Kiri -->
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                        Akses Mudah, Informasi Akurat untuk Kesehatan Optimal Anda
                    </h1>
                    <p class="mt-4 text-lg text-gray-800/90">
                        Selamat datang di KuloSehat. Kami menyediakan berbagai artikel dan panduan informatif mengenai penyakit umum, gejala, cara pencegahan, serta penanganan awal yang bisa Anda lakukan.
                    </p>
                    <a href="{{ route('login') }}" class="mt-8 inline-block bg-gray-700 text-white font-bold py-3 px-10 rounded-lg shadow-lg hover:bg-gray-800 transition duration-300">
                        Login
                    </a>
                </div>

                <!-- Gambar Robot Dokter di Kanan (STRUKTUR BARU) -->
                <div class="hidden md:flex justify-center items-center">
                    <!-- Container untuk menampung shape dan robot -->
                    <div class="relative w-full max-w-sm h-96">

                        <!-- 1. Shape Latar Belakang -->
                        <div class="absolute inset-x-10 top-0 h-72 bg-gradient-to-b from-green-300 to-lime-400 rounded-3xl transform -rotate-6"></div>

                        <!-- 2. Gambar Robot dengan Masker -->
                        <img src="{{ asset('images/illustrations/sdsja9du9ahdpo 1.png') }}" alt="Robot Dokter" class="absolute inset-0 w-full h-full object-contain robot-mask">

                    </div>
                </div>

            </div>
            <!-- Wave Divider membutuhkan class 'relative' pada parent section -->
            <div class="wave-divider"></div>
        </section>

        <!-- Bagian Konten Bawah -->
        <section class="bg-white py-20">
            <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-800">
                    KuloSehat. mempermudah dan membantu Anda menemukan cara hidup sehat dan mencegah penyakit!
                </h2>
                <p class="mt-4 text-lg text-green-600 font-semibold">Bergabunglah sekarang!!</p>
                <p class="mt-6 text-gray-600 leading-relaxed">
                    Pertama, bergabunglah dengan KuloSehat gratis. Mulai dari informasi dasar tentang gaya hidup sehat hingga pencegahan penyakit kronis, semua artikel dan tips kami dapat diakses oleh publik. Mulai lindungi kesehatan Anda dan keluarga sekarang!
                </p>
            </div>
        </section>
    </main>

</body>
</html>
