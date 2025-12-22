<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kategori Bayi - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-green-400 to-lime-500 min-h-screen relative">

    <x-public-navbar :show-search="false" :active-category-slug="'bayi'" />

    <main class="pt-28">
        <section class="relative overflow-hidden">
            <div class="relative max-w-6xl mx-auto px-6 lg:px-8 pb-24 pt-12">
                <div class="flex flex-wrap justify-center gap-6 lg:gap-8">
                    @php
                        $babyCategories = [
                            [
                                'slug' => 'flu',
                                'label' => 'Flu',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-10 h-10"><path d="M4.5 10c0-2.2 2.3-4 7.5-4s7.5 1.8 7.5 4v1.25A4.75 4.75 0 0 1 14.75 16h-5.5A4.75 4.75 0 0 1 4.5 11.25V10Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.75 11.25 3 12.5M19.25 11.25 21 12.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11h6" stroke-linecap="round"/><path d="M9 9h.01M15 9h.01" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                            ],
                            [
                                'slug' => 'demam',
                                'label' => 'Demam',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-10 h-10"><circle cx="11" cy="12" r="6.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 14.5c.7.6 1.7 1 2.5 1s1.8-.4 2.5-1" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 10.5h.01M12 10.5h.01" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.8 4.2c.7-.7 1.9-.4 2.1.6.2.8-.3 1.9-1.4 2.9-.8-.4-1.5-1-1.8-1.7-.3-.6-.1-1.3.4-1.8Z" fill="currentColor" stroke="none"/><path d="M15.5 9.5c.8-.7 1.5-1.7 1.5-2.6" stroke-linecap="round"/></svg>',
                            ],
                            [
                                'slug' => 'diare',
                                'label' => 'Diare',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-10 h-10"><path d="M14 5.5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" stroke-linecap="round" stroke-linejoin="round" fill="currentColor"/><path d="M6.5 21 8.2 14l3-2 1.3-3.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.5 9.4 12.1 11m0 0 3.4 1.8 1.5 3.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 14h3.2" stroke-linecap="round"/><path d="M4.5 18.5h2.5M17 18.5h2" stroke-linecap="round"/></svg>',
                            ],
                            [
                                'slug' => 'ruam-popok',
                                'label' => 'Ruam Popok',
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-10 h-10"><path d="M4.5 7.5A3.5 3.5 0 0 1 8 4h8a3.5 3.5 0 0 1 3.5 3.5v3A6.5 6.5 0 0 1 13 17h-2A6.5 6.5 0 0 1 4.5 10.5v-3Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 9.5h3l-1 2.2L7.5 13M17 9.5h-3l1 2.2 1.5 1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 16.5 8 20M14 16.5l2 3.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                            ],
                        ];
                    @endphp

                    @foreach ($babyCategories as $item)
                        <a href="{{ route('topics.show', ['category' => 'bayi', 'topic' => $item['slug']]) }}"
                           class="group w-40 sm:w-52 h-48 sm:h-56 bg-white/95 backdrop-blur-sm rounded-[24px] p-6 sm:p-8 flex flex-col items-center justify-center text-center text-gray-800 shadow-[0_20px_45px_rgba(0,0,0,0.12)] border border-white/60 hover:-translate-y-1 hover:shadow-[0_26px_60px_rgba(22,163,74,0.18)] transition transform">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-green-50 flex items-center justify-center text-green-500 group-hover:bg-green-100 transition">
                                {!! $item['icon'] !!}
                            </div>
                            <span class="mt-6 text-base sm:text-lg font-semibold">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

</body>
</html>
