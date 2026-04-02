@extends('layouts.admin')

@section('title', 'Keluhan User')
@section('header-subtitle', 'Pantau, edit, dan hapus keluhan pengguna')
@section('header-title', 'Dashboard Keluhan')

@section('content')
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Total</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalKeluhan }}</p>
            <p class="text-sm text-gray-500">Keluhan tercatat</p>
        </div>
        <div class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6">
            <p class="text-xs uppercase tracking-[0.2em] text-amber-700 font-semibold">Menunggu</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $menunggu }}</p>
            <p class="text-sm text-gray-500">Belum dijawab</p>
        </div>
        <div class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Dijawab</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $dijawab }}</p>
            <p class="text-sm text-gray-500">Sudah diproses</p>
        </div>
    </div>

    <div class="rounded-3xl bg-white shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-widest text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left">User</th>
                        <th class="px-5 py-3 text-left">Keluhan</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Jawaban</th>
                        <th class="px-5 py-3 text-left">Diperbarui</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($keluhans as $k)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-900">{{ $k->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $k->user->email }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-800">
                                <p class="font-semibold">{{ Str::limit($k->judul, 48) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($k->isi, 70) }}</p>
                                @if($k->gambar)
                                    <div class="mt-2 text-xs text-emerald-600 font-semibold">Ada lampiran</div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $k->status === 'dijawab' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $k->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-600">
                                {{ $k->jawaban ? Str::limit($k->jawaban, 70) : 'Belum ada jawaban.' }}
                            </td>
                            <td class="px-5 py-4 text-gray-500">
                                {{ $k->updated_at?->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-3 text-sm font-semibold">
                                    <a href="{{ route('admin.keluhan.show', $k) }}" class="text-gray-600 hover:text-gray-900">Lihat</a>
                                    <a href="{{ route('admin.keluhan.edit', $k) }}" class="text-emerald-600 hover:text-emerald-800">Edit</a>
                                    <form action="{{ route('admin.keluhan.destroy', $k) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus keluhan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-6 text-center text-gray-500">Belum ada keluhan dari user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $keluhans->links() }}
        </div>
    </div>
@endsection
