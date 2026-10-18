@extends('layouts.admin')
@section('title','Layanan Premium')
@section('header-title','Layanan Premium')
@section('header-subtitle','Pantau pengguna dan percakapan premium')
@section('content')
<div class="overflow-x-auto rounded-3xl border bg-white"><table class="min-w-full text-sm"><thead class="bg-gray-50"><tr><th class="p-4 text-left">Pengguna</th><th class="p-4 text-left">Email</th><th class="p-4 text-left">Aktif sampai</th><th class="p-4 text-left">Percakapan</th><th class="p-4 text-left">Aksi</th></tr></thead><tbody class="divide-y">
@forelse($premiumUsers as $user)
    @php($conversations = $user->keluhans()->where('jenis','premium')->latest()->get())
    <tr><td class="p-4 font-semibold">@if($conversations->isNotEmpty())<a class="text-emerald-700 underline" href="{{ route('admin.premium.show',$conversations->first()) }}">{{ $user->name }}</a>@else{{ $user->name }}@endif</td><td class="p-4">{{ $user->email }}</td><td class="p-4">{{ $user->premium_until->format('d M Y') }}</td><td class="p-4">@forelse($conversations as $conversation)<a class="mr-2 text-emerald-700 underline" href="{{ route('admin.premium.show',$conversation) }}">{{ $conversation->judul }}</a>@empty<span class="text-slate-400">Belum ada chat</span>@endforelse</td><td class="p-4"><form method="POST" action="{{ route('admin.premium.revoke',$user) }}" onsubmit="return confirm('Cabut layanan Premium pengguna ini?')">@csrf @method('DELETE')<button class="text-rose-600">Cabut Premium</button></form></td></tr>
@empty
    <tr><td colspan="5" class="p-8 text-center text-slate-500">Tidak ada akun Premium aktif.</td></tr>
@endforelse
</tbody></table></div><div class="mt-4">{{ $premiumUsers->links() }}</div>
@endsection
