@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('header-subtitle', 'Panel Pengguna')
@section('header-title', 'Kelola Pengguna & Dokter')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Pengguna Aktif</h2>
                <p class="text-sm text-gray-500">Kelola izin dan peran pengguna dengan mudah.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                Tambah Pengguna Baru
            </a>
        </div>

        <div class="rounded-3xl bg-white shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-widest text-gray-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Nama</th>
                            <th class="px-5 py-3 text-left">Email</th>
                            <th class="px-5 py-3 text-left">Peran</th>
                            <th class="px-5 py-3 text-left">Bergabung</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-5 py-4 text-gray-900">{{ $user->name }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        @class([
                                            'bg-red-100 text-red-700' => $user->role === 'admin',
                                            'bg-emerald-100 text-emerald-700' => $user->role === 'dokter',
                                            'bg-gray-100 text-gray-600' => !in_array($user->role, ['admin','dokter']),
                                        ])
                                    ">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td class="px-5 py-4 text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-gray-500">Tidak ada data pengguna ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
