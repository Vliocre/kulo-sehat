<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Artikel Tersimpan - KuloSehat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-white via-emerald-50 to-lime-50">
    <x-public-navbar :show-search="false" />

    <main class="pt-32 pb-20">
        <section class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="rounded-[32px] bg-white/95 p-8 lg:p-10 shadow-[0_26px_70px_rgba(15,23,42,0.12)] ring-1 ring-emerald-50">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-emerald-700 font-semibold">Profil</p>
                        <h1 class="mt-3 text-3xl font-bold text-slate-900">Artikel Tersimpan</h1>
                        <p class="mt-2 text-sm text-slate-600">Daftar artikel yang sudah Anda simpan untuk dibaca kembali.</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-100 hover:bg-emerald-50">
                        Kembali ke Profil
                    </a>
                </div>

                @if (session('success'))
                    <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mt-8 grid gap-6 md:grid-cols-2">
                    @forelse ($savedArticles as $savedArticle)
                        <article class="rounded-3xl bg-white shadow-[0_18px_40px_rgba(15,118,110,0.1)] ring-1 ring-emerald-50 overflow-hidden">
                            <a href="{{ route('articles.public.show', $savedArticle->slug) }}" class="block">
                                @if ($savedArticle->image)
                                    <img src="{{ asset('storage/' . $savedArticle->image) }}" alt="{{ $savedArticle->title }}" class="h-52 w-full object-cover">
                                @else
                                    <div class="flex h-52 w-full items-center justify-center bg-emerald-50 text-sm font-semibold text-emerald-700">
                                        Artikel KuloSehat
                                    </div>
                                @endif
                            </a>
                            <div class="p-6">
                                <p class="text-xs uppercase tracking-widest text-emerald-600 font-semibold">
                                    {{ optional($savedArticle->category)->name ?? 'Kesehatan' }}
                                </p>
                                <h2 class="mt-2 text-xl font-semibold text-slate-900 leading-snug">
                                    <a href="{{ route('articles.public.show', $savedArticle->slug) }}" class="hover:text-emerald-700">
                                        {{ $savedArticle->title }}
                                    </a>
                                </h2>
                                <p class="mt-3 text-sm text-slate-600">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($savedArticle->content), 130) }}
                                </p>
                                <div class="mt-5 flex flex-wrap items-center gap-3">
                                    <a href="{{ route('articles.public.show', $savedArticle->slug) }}" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                        Baca Artikel
                                    </a>
                                    <form method="POST" action="{{ route('articles.unbookmark', $savedArticle->slug) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 ring-1 ring-rose-100 hover:bg-rose-100">
                                            Hapus Simpanan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="md:col-span-2 rounded-3xl border border-dashed border-emerald-100 bg-emerald-50/50 px-6 py-10 text-center">
                            <p class="text-sm text-slate-600">Belum ada artikel tersimpan.</p>
                            <a href="{{ route('articles.public.index') }}" class="mt-4 inline-flex rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                                Cari Artikel
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
</body>
</html>
