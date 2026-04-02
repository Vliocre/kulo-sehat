@props([
    'class' => 'absolute top-0 left-0 right-0 z-20 px-4 pt-4',
    'showSearch' => true,
    'categories' => collect(),
    'activeCategorySlug' => request()->query('category'),
])

@php
    $categoryItems = collect($categories);
@endphp

<header {{ $attributes->merge(['class' => $class]) }}>
    <div class="max-w-7xl mx-auto bg-white/95 backdrop-blur-sm rounded-[28px] shadow-lg">
        <nav class="px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                {{-- ================= LEFT MENU ================= --}}
                <div class="flex items-center gap-6 flex-wrap text-sm font-medium">

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="hover:text-green-600 {{ request()->routeIs('dashboard') ? 'text-green-600' : 'text-gray-700' }}">
                            Beranda
                        </a>
                    @else
                        <a href="{{ route('home') }}"
                           class="hover:text-green-600 {{ request()->routeIs('home') ? 'text-green-600' : 'text-gray-700' }}">
                            Beranda
                        </a>
                    @endauth

                    {{-- ARTIKEL --}}
                    <a href="{{ route('articles.public.index') }}"
                       class="hover:text-green-600 {{ request()->routeIs('articles.public.*') ? 'text-green-600' : 'text-gray-700' }}">
                        Artikel
                    </a>

                     <a href="{{ route('kalkulator.public.index') }}"
                       class="hover:text-green-600 {{ request()->routeIs('kalkulator.public.*') ? 'text-green-600' : 'text-gray-700' }}">
                        Kalkulator
                    </a>

                    {{-- KELUHAN USER --}}
                    @auth
                        @if(!Auth::user()->isDokter() && !Auth::user()->isAdmin())
                            <a href="{{ route('keluhan.index') }}"
                               class="hover:text-green-600 {{ request()->routeIs('keluhan.*') ? 'text-green-600' : 'text-gray-700' }}">
                                Keluhan
                            </a>
                        @endif
                    @endauth

                    {{-- KELUHAN DOKTER --}}
                    @auth
                        @if(Auth::user()->role === 'dokter')
                            <a href="{{ route('dokter.keluhan') }}"
                               class="hover:text-green-600 {{ request()->routeIs('dokter.keluhan') ? 'text-green-600' : 'text-gray-700' }}">
                                Keluhan Pasien
                            </a>
                        @endif
                    @endauth

                    {{-- DROPDOWN KATEGORI --}}
                    <div x-data="{ open: false }" class="relative" @click.away="open=false">
                        <button @click="open=!open"
                            class="flex items-center gap-1 text-gray-700 hover:text-green-600">
                            Kategori
                            <svg class="w-4 h-4 transition"
                                 :class="{'rotate-180': open}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                             class="absolute mt-3 w-48 bg-white rounded-xl shadow-xl py-2">
                            @if ($categoryItems->isNotEmpty())
                                @foreach ($categoryItems as $category)
                                    @php $isActive = $activeCategorySlug === $category->slug; @endphp
                                    <a href="{{ route('categories.show', $category->slug) }}"
                                       class="block px-4 py-2 rounded-lg text-sm
                                       {{ $isActive ? 'bg-green-50 text-green-600' : 'hover:bg-green-50 text-gray-700' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            @else
                                @php
                                    $staticCategories = [
                                        ['label' => 'Bayi', 'slug' => 'bayi'],
                                        ['label' => 'Remaja', 'slug' => 'remaja'],
                                        ['label' => 'Dewasa', 'slug' => 'dewasa'],
                                        ['label' => 'Lansia', 'slug' => 'lansia'],
                                    ];
                                @endphp
                                @foreach ($staticCategories as $category)
                                    <a href="{{ route('categories.show', $category['slug']) }}"
                                       class="block px-4 py-2 rounded-lg text-sm hover:bg-green-50 text-gray-700">
                                        {{ $category['label'] }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>

                {{-- ================= BRAND ================= --}}
                <div class="text-center">
                    <a href="{{ route('home') }}"
                       class="text-xl font-bold">
                        <span class="text-green-600">Kulo</span>
                        <span class="text-gray-900">Sehat.</span>
                    </a>
                </div>

                {{-- ================= RIGHT ================= --}}
                <div class="flex items-center gap-3 w-full lg:w-auto">

                    {{-- SEARCH --}}
                    @if ($showSearch)
                        <form action="{{ route('articles.public.index') }}"
                              method="GET"
                              class="flex-1">
                            <div class="flex items-center rounded-full border px-3 py-1.5">
                                <svg class="w-4 h-4 text-gray-400"
                                     fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M21 21l-4-4m0 0A7 7 0 1010 17a7 7 0 007-7z"/>
                                </svg>
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Cari artikel..."
                                       class="w-full border-0 focus:ring-0 text-sm ms-2">
                            </div>
                        </form>
                    @endif

                    {{-- USER MENU --}}
                    @auth
                        <div x-data="{ open:false }" class="relative" @click.away="open=false">

                            <button @click="open=!open"
                                class="border px-4 h-10 rounded-full text-sm font-semibold hover:border-green-300 hover:text-green-600">
                                {{ Auth::user()->name }}
                            </button>

                            <div x-show="open" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1">

                                <a href="{{ route('profile.edit') }}"
                                   class="block px-4 py-2 hover:bg-gray-100">
                                    Profil
                                </a>

                                @if(Auth::user()->role === 'dokter')
                                    <a href="{{ route('doctor.articles.index') }}"
                                       class="block px-4 py-2 hover:bg-gray-100">
                                        Kelola Artikel
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>

                            </div>
                        </div>

                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 h-10 flex items-center border rounded-full text-sm hover:border-green-300 hover:text-green-600">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                           class="px-4 h-10 flex items-center bg-green-500 text-white rounded-full hover:bg-green-600 text-sm">
                            Daftar
                        </a>
                    @endauth

                </div>

            </div>
        </nav>
    </div>
</header>
