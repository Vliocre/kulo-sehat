<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @keyframes page-fade-in {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes page-fade-up {
                from { opacity: 0; transform: translateY(12px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .page-enter {
                animation: page-fade-in 420ms ease-out both;
            }
            .page-enter > * {
                animation: page-fade-up 520ms ease-out both;
            }
            .page-enter > *:nth-child(2) { animation-delay: 60ms; }
            .page-enter > *:nth-child(3) { animation-delay: 120ms; }
            .page-enter > *:nth-child(4) { animation-delay: 180ms; }
            .page-enter > *:nth-child(5) { animation-delay: 240ms; }
            .page-enter > *:nth-child(6) { animation-delay: 300ms; }
            .page-enter > *:nth-child(7) { animation-delay: 360ms; }
            .page-enter > *:nth-child(8) { animation-delay: 420ms; }
            @media (prefers-reduced-motion: reduce) {
                .page-enter,
                .page-enter > * {
                    animation: none !important;
                    transform: none !important;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="page-enter">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </body>
</html>
