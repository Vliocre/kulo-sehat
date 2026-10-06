<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Percakapan Premium - KuloSehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak]{display:none!important}
        .conversation-bg{background-color:#eef8f3;background-image:radial-gradient(22% 30% at 8% 10%,rgba(16,185,129,.10),transparent 55%),radial-gradient(26% 34% at 92% 6%,rgba(20,184,166,.09),transparent 55%),linear-gradient(145deg,#f8fcfa 0%,#edf7f2 50%,#e8f3ef 100%)}
        .chat-pattern{background-image:radial-gradient(circle at 1px 1px,rgba(15,118,110,.07) 1px,transparent 0);background-size:22px 22px}
    </style>
</head>
<body class="conversation-bg min-h-screen font-sans antialiased text-slate-900">
<x-public-navbar :show-search="false" />

@php
    $statusLabels = ['requested' => 'Menunggu dokter', 'active' => 'Sedang aktif', 'paused' => 'Dijeda', 'closed' => 'Selesai', 'rejected' => 'Ditolak'];
    $statusClasses = ['requested' => 'bg-amber-100 text-amber-700', 'active' => 'bg-emerald-100 text-emerald-700', 'paused' => 'bg-orange-100 text-orange-700', 'closed' => 'bg-slate-100 text-slate-600', 'rejected' => 'bg-rose-100 text-rose-700'];
    $currentDoctor = $draftDoctor ?? $conversation?->doctor;
@endphp

<main class="pb-10 pt-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <section class="mb-6 flex flex-col gap-4 rounded-[28px] bg-gradient-to-r from-slate-950 via-emerald-900 to-emerald-600 px-6 py-6 text-white shadow-[0_20px_55px_rgba(15,118,110,.22)] sm:flex-row sm:items-center sm:justify-between sm:px-8">
            <div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-white/15 px-3 py-1 text-[10px] font-bold uppercase tracking-[.2em] ring-1 ring-white/20">Ruang Privat</span>
                    <span class="text-xs text-emerald-100">KuloSehat Premium</span>
                </div>
                <h1 class="mt-3 text-2xl font-bold sm:text-3xl">Percakapan dengan dokter</h1>
                <p class="mt-1 text-sm text-emerald-50/80">Konsultasi, lampiran, dan riwayat Anda tersimpan aman dalam satu ruang.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('doctors.index') }}" class="rounded-full bg-white/10 px-4 py-2.5 text-sm font-semibold ring-1 ring-white/25 transition hover:bg-white/20">Pilih dokter</a>
                <a href="{{ route('profile.edit') }}" class="rounded-full bg-white px-4 py-2.5 text-sm font-bold text-emerald-800 transition hover:bg-emerald-50">Kembali ke profil</a>
            </div>
        </section>

        @if(session('ok'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-medium text-emerald-800">{{ session('ok') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm text-rose-700">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="grid min-h-[680px] overflow-hidden rounded-[32px] border border-white/80 bg-white/70 shadow-[0_28px_75px_rgba(15,23,42,.14)] backdrop-blur-xl lg:grid-cols-[320px_minmax(0,1fr)]">
            <aside class="border-b border-slate-100 bg-white/90 lg:border-b-0 lg:border-r">
                <div class="border-b border-slate-100 p-5">
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">Riwayat konsultasi</p>
                    <div class="mt-2 flex items-end justify-between">
                        <h2 class="text-xl font-bold">Percakapan Anda</h2>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-500">{{ $conversations->count() }}</span>
                    </div>
                </div>

                <div class="max-h-[300px] space-y-2 overflow-y-auto p-3 lg:max-h-[610px]">
                    @if($draftDoctor)
                        <a href="{{ route('premium.conversations.start', $draftDoctor) }}" class="block rounded-2xl border p-3 transition {{ ($showDraft ?? false) ? 'border-emerald-300 bg-emerald-50 ring-2 ring-emerald-100' : 'border-emerald-100 bg-white hover:border-emerald-300 hover:bg-emerald-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 font-bold text-white">{{ collect(explode(' ', $draftDoctor->name))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('') }}</div>
                                <div class="min-w-0 flex-1"><p class="truncate text-sm font-bold">Percakapan baru</p><p class="truncate text-xs text-emerald-700">{{ $draftDoctor->name }}</p></div>
                                @if(!($showDraft ?? false))<svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg>@endif
                            </div>
                        </a>
                    @endif

                    @forelse($conversations as $item)
                        @php
                            $isSelected = !($showDraft ?? false) && $conversation?->id === $item->id;
                            $lastMessage = $item->pesanPremium->last();
                            $unread = $item->pesanPremium->where('sender_id', '!=', $user->id)->whereNull('dibaca_at')->count();
                        @endphp
                        <a href="{{ route('premium.conversations.show', array_filter(['keluhan' => $item->id, 'draft_doctor' => $draftDoctor?->id])) }}" class="block rounded-2xl border p-3 transition {{ $isSelected ? 'border-emerald-300 bg-emerald-50 ring-2 ring-emerald-100' : 'border-transparent hover:border-emerald-100 hover:bg-slate-50' }}">
                            <div class="flex items-start gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-700 text-sm font-bold text-white">{{ collect(explode(' ', $item->doctor->name ?? 'Dokter'))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('') }}</div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2"><p class="truncate text-sm font-bold">{{ $item->doctor->name ?? 'Dokter' }}</p>@if($unread)<span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-emerald-600 px-1 text-[10px] font-bold text-white">{{ $unread }}</span>@endif</div>
                                    <p class="mt-0.5 truncate text-xs text-slate-500">{{ $item->judul }}</p>
                                    <div class="mt-2 flex items-center justify-between gap-2"><span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $statusClasses[$item->premium_status] ?? 'bg-slate-100 text-slate-600' }}">{{ $statusLabels[$item->premium_status] ?? ucfirst($item->premium_status) }}</span><span class="text-[10px] text-slate-400">{{ ($lastMessage?->created_at ?? $item->created_at)->format('d M') }}</span></div>
                                </div>
                            </div>
                        </a>
                    @empty
                        @if(!$draftDoctor)
                            <div class="px-3 py-10 text-center"><div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a10.8 10.8 0 0 1-4-.75L3 20l1.3-3.5A7.35 7.35 0 0 1 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/></svg></div><p class="mt-3 text-sm font-semibold">Belum ada percakapan</p><p class="mt-1 text-xs leading-5 text-slate-500">Pilih dokter untuk memulai konsultasi Premium.</p></div>
                        @endif
                    @endforelse
                </div>
            </aside>

            <section class="flex min-h-[680px] min-w-0 flex-col bg-white">
                @if(($showDraft ?? false) && $draftDoctor)
                    <header class="border-b border-slate-100 px-6 py-5 sm:px-8">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-lg font-bold text-white shadow-lg shadow-emerald-500/20">{{ collect(explode(' ', $draftDoctor->name))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('') }}</div>
                            <div><div class="flex items-center gap-2"><h2 class="text-lg font-bold">{{ $draftDoctor->name }}</h2><span class="text-emerald-500">&#10003;</span></div><p class="text-sm font-semibold text-emerald-700">{{ $draftDoctor->specialty ?: 'Dokter Umum' }}</p><p class="text-xs text-slate-500">{{ $draftDoctor->workplace ?: 'Konsultasi online KuloSehat' }}</p></div>
                        </div>
                    </header>
                    <form x-data="{ fileName: '' }" method="POST" action="{{ route('keluhan.premium.store', $draftDoctor) }}" enctype="multipart/form-data" class="flex min-h-0 flex-1 flex-col">
                        @csrf
                        <div class="chat-pattern flex flex-1 flex-col justify-end overflow-y-auto p-4 sm:p-7">
                            <div class="mx-auto mb-auto max-w-md rounded-2xl bg-white/90 px-4 py-3 text-center text-xs leading-5 text-slate-500 shadow-sm ring-1 ring-slate-100">Mulai seperti mengirim chat biasa. Dokter akan menerima topik dan pesan pertama Anda sebagai permintaan konsultasi.</div>
                            <div class="mb-4 max-w-xl rounded-2xl rounded-bl-md border border-slate-100 bg-white p-4 shadow-sm sm:p-5">
                                <p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">Topik percakapan</p>
                                <input id="judul" name="judul" value="{{ old('judul') }}" maxlength="150" required placeholder="Contoh: Nyeri kepala berulang" class="mt-2 w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-400 focus:ring-emerald-200">
                                <p class="mt-2 text-[11px] text-slate-400">Topik membantu dokter memahami konteks percakapan sejak awal.</p>
                            </div>
                        </div>
                        <div class="border-t border-slate-100 bg-white p-3 sm:px-5 sm:py-4">
                            <div x-show="fileName" x-cloak class="mb-2 flex items-center justify-between rounded-xl bg-emerald-50 px-3 py-2 text-xs text-emerald-700"><span class="truncate" x-text="fileName"></span><button type="button" @click="fileName = ''; $refs.firstImage.value = ''" class="ml-3 font-bold">Hapus</button></div>
                            <div class="flex items-end gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-2 focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-50">
                                <label class="flex h-11 w-11 shrink-0 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition hover:bg-white hover:text-emerald-600" title="Lampirkan gambar maksimal 5 MB">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.172 7 8 14.172a2.828 2.828 0 1 0 4 4L20 10a5.657 5.657 0 0 0-8-8L4 10a8.485 8.485 0 0 0 12 12l6-6"/></svg>
                                    <input x-ref="firstImage" @change="fileName = $event.target.files[0]?.name || ''" type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="sr-only">
                                </label>
                                <textarea id="pesan" name="pesan" rows="1" maxlength="5000" required @keydown.enter.exact.prevent="$el.form.requestSubmit()" placeholder="Tulis pesan pertama untuk dokter..." class="max-h-32 min-h-[44px] flex-1 resize-none border-0 bg-transparent px-2 py-2.5 text-sm focus:ring-0">{{ old('pesan') }}</textarea>
                                <button class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-md transition hover:bg-emerald-500" aria-label="Kirim pesan dan buka percakapan"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 14-9-4 18-3-7-7-2Zm7 2 3-3"/></svg></button>
                            </div>
                            <div class="mt-2 flex items-center justify-between px-1 text-[10px] text-slate-400"><span>Enter untuk kirim · Shift+Enter untuk baris baru</span><span>Gambar maks. 5 MB</span></div>
                        </div>
                    </form>
                @elseif($conversation)
                    <header class="flex flex-col gap-4 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 font-bold text-white">{{ collect(explode(' ', $conversation->doctor->name ?? 'Dokter'))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('') }}</div>
                            <div><div class="flex items-center gap-2"><h2 class="font-bold">{{ $conversation->doctor->name ?? 'Dokter' }}</h2><span class="h-2 w-2 rounded-full {{ $conversation->premium_status === 'active' ? 'bg-emerald-500' : 'bg-amber-400' }}"></span></div><p class="text-xs font-semibold text-emerald-700">{{ $conversation->doctor?->specialty ?: 'Dokter Umum' }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $conversation->judul }}</p></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $statusClasses[$conversation->premium_status] ?? 'bg-slate-100 text-slate-600' }}">{{ $statusLabels[$conversation->premium_status] ?? ucfirst($conversation->premium_status) }}</span>
                            @if($conversation->premium_status === 'active')
                                <form method="POST" action="{{ route('keluhan.premium.close', $conversation) }}" onsubmit="return confirm('Selesaikan konsultasi ini? Riwayat pesan tetap tersimpan.');">@csrf @method('PATCH')<button class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-600 hover:border-rose-200 hover:text-rose-600">Selesaikan</button></form>
                            @endif
                        </div>
                    </header>

                    <div x-data x-init="$nextTick(() => { $refs.messages.scrollTop = $refs.messages.scrollHeight })" x-ref="messages" class="chat-pattern flex-1 space-y-4 overflow-y-auto p-4 sm:p-7">
                        <div class="mx-auto mb-5 max-w-md rounded-2xl bg-white/90 px-4 py-3 text-center text-xs leading-5 text-slate-500 shadow-sm ring-1 ring-slate-100">Percakapan ini bersifat privat dan hanya dapat diakses oleh Anda serta dokter yang dipilih.</div>
                        @foreach($conversation->pesanPremium->sortBy('created_at') as $message)
                            @php $mine = $message->sender_id === $user->id; @endphp
                            <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[88%] sm:max-w-[72%]">
                                    <p class="mb-1 px-1 text-[10px] font-semibold {{ $mine ? 'text-right text-emerald-700' : 'text-slate-500' }}">{{ $mine ? 'Anda' : ($message->sender->name ?? 'Dokter') }}</p>
                                    <div class="rounded-2xl px-4 py-3 shadow-sm {{ $mine ? 'rounded-br-md bg-gradient-to-br from-emerald-600 to-teal-600 text-white' : 'rounded-bl-md border border-slate-100 bg-white text-slate-700' }}">
                                        @if($message->pesan)<p class="whitespace-pre-line text-sm leading-6">{{ $message->pesan }}</p>@endif
                                        @if($message->gambar)<a href="{{ route('keluhan.premium.attachment', [$conversation, $message]) }}" target="_blank"><img src="{{ route('keluhan.premium.attachment', [$conversation, $message]) }}" alt="Lampiran percakapan" loading="lazy" class="mt-2 max-h-64 w-full rounded-xl object-cover"></a>@endif
                                        <p class="mt-1.5 text-right text-[10px] {{ $mine ? 'text-emerald-100' : 'text-slate-400' }}">{{ $message->created_at->format('d M, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($user->hasActivePremium() && in_array($conversation->premium_status, ['requested', 'active'], true))
                        <form x-data="{ fileName: '' }" method="POST" action="{{ route('keluhan.premium.message', $conversation) }}" enctype="multipart/form-data" class="border-t border-slate-100 bg-white p-3 sm:px-5 sm:py-4">
                            @csrf
                            @if($conversation->premium_status === 'requested')
                                <div class="mb-2 rounded-xl bg-amber-50 px-3 py-2 text-center text-xs text-amber-700"><strong>Menunggu dokter menerima konsultasi.</strong> Anda tetap dapat mengirim informasi tambahan.</div>
                            @endif
                            <div x-show="fileName" x-cloak class="mb-2 flex items-center justify-between rounded-xl bg-emerald-50 px-3 py-2 text-xs text-emerald-700"><span class="truncate" x-text="fileName"></span><button type="button" @click="fileName = ''; $refs.chatImage.value = ''" class="ml-3 font-bold">Hapus</button></div>
                            <div class="flex items-end gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-2 focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-50">
                                <label class="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition hover:bg-white hover:text-emerald-600" title="Lampirkan gambar maksimal 5 MB"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.172 7 8 14.172a2.828 2.828 0 1 0 4 4L20 10a5.657 5.657 0 0 0-8-8L4 10a8.485 8.485 0 0 0 12 12l6-6"/></svg><input x-ref="chatImage" @change="fileName = $event.target.files[0]?.name || ''" type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="sr-only"></label>
                                <textarea name="pesan" rows="1" maxlength="5000" @keydown.enter.exact.prevent="$el.form.requestSubmit()" placeholder="Tulis pesan untuk dokter..." class="max-h-32 min-h-[40px] flex-1 resize-none border-0 bg-transparent px-2 py-2 text-sm focus:ring-0"></textarea>
                                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-md transition hover:bg-emerald-500" aria-label="Kirim pesan"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 14-9-4 18-3-7-7-2Zm7 2 3-3"/></svg></button>
                            </div>
                            <p class="mt-2 px-1 text-[10px] text-slate-400">Enter untuk kirim · Shift+Enter untuk baris baru · Gambar maksimal 5 MB</p>
                        </form>
                    @elseif($conversation->premium_status === 'paused' || ! $user->hasActivePremium())
                        <div class="border-t border-orange-100 bg-orange-50 px-5 py-4">
                            <div class="flex flex-col gap-3 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                                <div><p class="text-sm font-bold text-orange-800">Percakapan dijeda</p><p class="mt-0.5 text-xs text-orange-700">Riwayat tetap dapat dibaca, tetapi pesan baru hanya bisa dikirim setelah akun Premium diaktifkan kembali.</p></div>
                                <a href="{{ route('premium.register') }}" class="shrink-0 rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-500">Aktifkan Premium</a>
                            </div>
                        </div>
                    @else
                        <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 text-center text-sm text-slate-500">Percakapan ini telah {{ $conversation->premium_status === 'rejected' ? 'ditolak' : 'selesai' }} dan sekarang hanya dapat dibaca.</div>
                    @endif
                @else
                    <div class="chat-pattern flex flex-1 items-center justify-center p-8 text-center">
                        <div class="max-w-sm"><div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[26px] bg-emerald-50 text-emerald-600 shadow-inner"><svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a10.8 10.8 0 0 1-4-.75L3 20l1.3-3.5A7.35 7.35 0 0 1 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/></svg></div><h2 class="mt-5 text-2xl font-bold">Mulai percakapan pertama</h2><p class="mt-2 text-sm leading-6 text-slate-600">Pilih dokter terverifikasi dan ceritakan keluhan Anda melalui ruang konsultasi Premium.</p><a href="{{ route('doctors.index') }}" class="mt-5 inline-flex rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 hover:bg-emerald-500">Pilih dokter</a></div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</main>
</body>
</html>
