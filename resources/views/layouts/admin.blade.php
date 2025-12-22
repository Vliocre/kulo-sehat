@php
    $documentTitle = trim($__env->yieldContent('title')) ?: 'Admin Panel';
    $navLinks = [
        [
            'label' => 'Dasbor Utama',
            'route' => 'admin.dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M5 17h14" />',
            'pattern' => 'admin.dashboard',
        ],
        [
            'label' => 'Kelola Pengguna/Dokter',
            'route' => 'admin.users.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 14a4 4 0 10-8 0m8 0v1a4 4 0 01-4 4H6m10-5v1a4 4 0 004 4h2" />',
            'pattern' => 'admin.users.*',
        ],
        [
            'label' => 'Kelola Artikel',
            'route' => 'admin.articles.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10" />',
            'pattern' => 'admin.articles.*',
        ],
        [
            'label' => 'Konten Topik',
            'route' => 'admin.topic-guides.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h10M5 17h8" />',
            'pattern' => 'admin.topic-guides.*',
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $documentTitle }} • KuloSehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="font-sans bg-gradient-to-br from-emerald-50 via-white to-lime-100 text-gray-900">
    <div class="min-h-screen flex">
        <aside class="w-72 flex-shrink-0 bg-white/85 backdrop-blur border-r border-emerald-100 shadow-lg flex flex-col">
            <div class="px-8 py-10">
                <span class="text-2xl font-bold text-emerald-600">Kulo</span><span class="text-2xl font-bold text-gray-900">Sehat.</span>
                <p class="text-sm text-gray-500 mt-1">Healthcare Admin Panel</p>
            </div>
            <nav class="flex-1 px-6 space-y-2">
                @foreach ($navLinks as $link)
                    @php
                        $isActive = request()->routeIs($link['pattern']);
                    @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ $isActive ? 'bg-emerald-100 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">{!! $link['icon'] !!}</svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
            <div class="px-6 pb-6 mt-auto">
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-5 text-sm text-gray-600">
                    <p class="font-semibold text-gray-800">Tips</p>
                    <p>Periksa laporan konten mingguan untuk menjaga kualitas artikel.</p>
                </div>
            </div>
        </aside>
        <div class="flex-1 flex flex-col">
            <header class="bg-white/80 backdrop-blur border-b border-emerald-100 px-6 lg:px-10 py-4 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">
                        @hasSection('header-subtitle')
                            @yield('header-subtitle')
                        @else
                            Halo, Admin
                        @endif
                    </p>
                    <h1 class="text-2xl font-bold text-gray-900">
                        @hasSection('header-title')
                            @yield('header-title')
                        @else
                            {{ $documentTitle }}
                        @endif
                    </h1>
                </div>
                <div class="flex items-center gap-3">
                    @yield('header-actions')
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-red-500 hover:border-red-300 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m12 0l-4-4m4 4l-4 4"/></svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </header>
            <main class="flex-1 px-6 lg:px-10 py-8 space-y-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
