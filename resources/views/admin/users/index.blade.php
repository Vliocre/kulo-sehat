@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('header-subtitle', 'Panel Pengguna')
@section('header-title', 'Kelola Pengguna & Dokter')

@section('content')
    <div class="flex flex-col gap-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Pengguna Aktif</h2>
                <p class="text-sm text-gray-500">Kelola izin, peran, dan persetujuan akun dokter.</p>
                <p class="mt-2 text-sm text-amber-700">Dokter menunggu persetujuan: {{ $pendingDoctorCount }}</p>
            </div>
            <div class="rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-800">
                Fokus utama: tinjau dokter yang pending
            </div>
        </div>

        <div class="rounded-3xl bg-white shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-widest text-gray-500">
                        <tr>
                            <th class="px-5 py-3 text-left">Nama</th>
                            <th class="px-5 py-3 text-left">Email</th>
                            <th class="px-5 py-3 text-left">Umur</th>
                            <th class="px-5 py-3 text-left">Tinggi</th>
                            <th class="px-5 py-3 text-left">Berat</th>
                            <th class="px-5 py-3 text-left">Peran</th>
                            <th class="px-5 py-3 text-left">Verifikasi Dokter</th>
                            <th class="px-5 py-3 text-left">Dokumen</th>
                            <th class="px-5 py-3 text-left">Bergabung</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-5 py-4 text-gray-900">{{ $user->name }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $user->age ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $user->height ? rtrim(rtrim(number_format($user->height, 2, '.', ''), '0'), '.') . ' cm' : '-' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $user->weight ? rtrim(rtrim(number_format($user->weight, 2, '.', ''), '0'), '.') . ' kg' : '-' }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        @class([
                                            'bg-red-100 text-red-700' => $user->role === 'admin',
                                            'bg-emerald-100 text-emerald-700' => $user->role === 'dokter',
                                            'bg-gray-100 text-gray-600' => !in_array($user->role, ['admin','dokter']),
                                        ])
                                    ">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $doctorStatus = [
                                            'not_required' => 'Tidak perlu',
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                        ][$user->doctor_verification_status ?? 'not_required'];
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        @class([
                                            'bg-gray-100 text-gray-600' => ($user->doctor_verification_status ?? 'not_required') === 'not_required',
                                            'bg-amber-100 text-amber-700' => $user->doctor_verification_status === 'pending',
                                            'bg-emerald-100 text-emerald-700' => $user->doctor_verification_status === 'approved',
                                            'bg-red-100 text-red-700' => $user->doctor_verification_status === 'rejected',
                                        ])
                                    ">{{ $doctorStatus }}</span>
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    @if ($user->role === 'dokter')
                                        <div class="flex flex-col gap-2">
                                            <span class="text-xs text-gray-500">IDI: {{ $user->doctor_idi_number ?: '-' }}</span>
                                            @if ($user->doctor_str_file)
                                                <a href="{{ asset('storage/' . $user->doctor_str_file) }}" target="_blank" class="text-emerald-600 hover:text-emerald-800">Lihat STR</a>
                                            @endif
                                            @if ($user->doctor_sip_file)
                                                <a href="{{ asset('storage/' . $user->doctor_sip_file) }}" target="_blank" class="text-emerald-600 hover:text-emerald-800">Lihat SIP</a>
                                            @endif
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">Edit</a>
                                        @if ($user->role === 'dokter' && $user->doctor_verification_status !== 'approved')
                                            <form action="{{ route('admin.users.approve-doctor', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900">Setujui</button>
                                            </form>
                                        @endif
                                        @if ($user->role === 'dokter' && $user->doctor_verification_status !== 'rejected')
                                            <form action="{{ route('admin.users.reject-doctor', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800">Tolak</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-6 text-center text-gray-500">Tidak ada data pengguna ditemukan.</td>
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
