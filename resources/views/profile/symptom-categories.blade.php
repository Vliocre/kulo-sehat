<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gejala Penyakit - KuloSehat</title>

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
                        <h1 class="mt-3 text-3xl font-bold text-slate-900">Kategori Gejala Penyakit</h1>
                        <p class="mt-2 text-sm text-slate-600">Simpan gejala penyakit yang ingin Anda pantau atau baca kembali.</p>
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

                <div class="mt-8">
                    <p class="text-xs uppercase tracking-[0.25em] text-emerald-700 font-semibold">Tersimpan</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Gejala yang Anda simpan</h2>

                    <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                        @forelse ($savedTopics as $savedTopic)
                            <article class="rounded-3xl bg-white p-5 shadow-[0_16px_34px_rgba(15,118,110,0.08)] ring-1 ring-emerald-50 flex flex-col">
                                <div>
                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        {{ $savedTopic['category_name'] }}
                                    </span>
                                    <h3 class="mt-3 text-lg font-bold text-slate-900 leading-snug">{{ $savedTopic['title'] }}</h3>
                                    @if (!empty($savedTopic['summary']))
                                        <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($savedTopic['summary'], 120) }}</p>
                                    @endif
                                </div>

                                <div class="mt-auto pt-5 flex flex-wrap items-center gap-3">
                                    <form method="POST" action="{{ route('topics.unbookmark', ['category' => $savedTopic['category_slug'], 'topic' => $savedTopic['topic_slug']]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 ring-1 ring-rose-100 hover:bg-rose-100">
                                            Hapus Simpanan
                                        </button>
                                    </form>
                                    <a href="{{ route('topics.show', ['category' => $savedTopic['category_slug'], 'topic' => $savedTopic['topic_slug']]) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                                        Buka panduan
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="md:col-span-2 lg:col-span-3 rounded-2xl border border-dashed border-emerald-100 bg-emerald-50/50 px-4 py-8 text-center text-sm text-slate-600">
                                Belum ada gejala penyakit tersimpan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
