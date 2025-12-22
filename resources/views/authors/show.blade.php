<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil {{ $author->name }} - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-white via-emerald-50 to-white min-h-screen">
    <x-public-navbar />

    <main class="pt-28 pb-16">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[28px] bg-white shadow-[0_26px_70px_rgba(15,23,42,0.12)] ring-1 ring-emerald-50">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(16,185,129,0.12),transparent_35%),radial-gradient(circle_at_80%_10%,rgba(74,222,128,0.12),transparent_30%)] pointer-events-none"></div>
                <div class="relative p-8 lg:p-10">
                    <div class="flex flex-col md:flex-row md:items-center gap-6">
                        <div class="h-20 w-20 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(mb_substr($author->name, 0, 1)) }}
                        </div>
                        <div class="space-y-2">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold uppercase tracking-wide">
                                {{ $author->role === 'admin' ? 'Admin' : 'Dokter' }}
                            </div>
                            <h1 class="text-3xl font-bold text-slate-900">{{ $author->name }}</h1>
                            @if ($author->specialty)
                                <p class="text-sm font-semibold text-emerald-700">{{ $author->specialty }}</p>
                            @endif
                            @if ($author->workplace)
                                <p class="text-sm text-slate-600">Tempat praktik: <span class="font-semibold text-slate-800">{{ $author->workplace }}</span></p>
                            @endif
                        </div>
                    </div>

                    @if ($author->about)
                        <div class="mt-6 text-slate-700 leading-relaxed">
                            {!! nl2br(e($author->about)) !!}
                        </div>
                    @endif

                    <div class="mt-8 grid sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Kontak</p>
                            <p class="mt-1 text-sm text-slate-700">
                                {{ $author->phone ? $author->phone : 'Nomor tidak tersedia' }}
                            </p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-white px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Email</p>
                            <p class="mt-1 text-sm text-slate-700">{{ $author->email }}</p>
                        </div>
                    </div>

                    <div class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700">
                        <a href="{{ route('articles.public.index', ['search' => $author->name]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-600 text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                            Lihat artikel penulis
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-emerald-700 ring-1 ring-emerald-100 hover:ring-emerald-200 transition">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
