@props([
    'class' => 'absolute top-0 left-0 right-0 z-20 px-4 pt-4',
    'showSearch' => true,
])

<header {{ $attributes->merge(['class' => $class]) }}>
    <div class="max-w-7xl mx-auto bg-white/95 backdrop-blur-sm rounded-[32px] shadow-lg">
        <nav class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <!-- Kiri: Link Navigasi -->
                <div class="flex items-center justify-start gap-3 sm:gap-8 flex-wrap text-sm order-2 lg:order-1 flex-1">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-green-600 font-medium transition-colors">Beranda</a>
                    @else
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-green-600 font-medium transition-colors">Beranda</a>
                    @endauth

                    <!-- Dropdown Kategori -->
                    <div x-data="{ open: false }" class="relative" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center text-gray-700 hover:text-green-600 font-medium transition-colors focus:outline-none">
                            <span>Kategori</span>
                            <svg class="w-4 h-4 ml-1 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                    <div x-cloak
                         x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="absolute mt-3 w-40 bg-white rounded-xl shadow-xl py-2">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50 transition-colors">Bayi</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50 transition-colors">Remaja</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50 transition-colors">Dewasa</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50 transition-colors">Lansia</a>
                    </div>
                </div>

                    <a href="{{ route('articles.public.index') }}" class="text-gray-700 hover:text-green-600 font-medium transition-colors">Artikel</a>
                </div>

                <!-- Brand di Tengah -->
                <div class="order-1 lg:order-2 flex justify-center flex-1">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-center">
                        <span class="text-green-600">Kulo</span><span class="text-gray-900">Sehat.</span>
                    </a>
                </div>

                <!-- Kanan: Search + User/Login -->
                <div class="order-3 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-5 w-full lg:w-auto flex-1">
                    @if ($showSearch)
                        <form action="{{ route('articles.public.index') }}" method="GET" class="w-full sm:w-auto flex-1">
                            <label class="sr-only" for="search">Cari Artikel</label>
                            <div class="flex items-center rounded-full border border-gray-200 bg-white px-3 py-1.5 focus-within:border-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z"/>
                                </svg>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari artikel..." class="w-full bg-transparent border-0 focus:ring-0 text-sm text-gray-700 placeholder-gray-400 ms-2" />
                            </div>
                        </form>
                    @else
                        <div class="flex-1 hidden lg:block"></div>
                    @endif

                    @auth
                        <div x-data="{ open: false }" class="relative" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <span class="font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-cloak
                                 x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl shadow-gray-500/10 py-1 origin-top">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                @if (Auth::user()->role === 'dokter')
                                    <a href="{{ route('doctor.articles.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kelola Artikel</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Log Out
                                    </a>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2 flex-wrap justify-center lg:justify-end w-full">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 rounded-full border border-gray-200 hover:border-green-300 hover:text-green-600 transition">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-green-500 rounded-full hover:bg-green-600 transition w-full sm:w-auto text-center">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</header>
