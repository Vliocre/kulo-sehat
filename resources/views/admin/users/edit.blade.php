@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('header-subtitle', 'Panel Pengguna')
@section('header-title', 'Edit Pengguna: ' . $user->name)

@section('content')
    <div class="max-w-3xl mx-auto rounded-3xl bg-white shadow-lg border border-gray-100 p-8 space-y-6">
        @if ($user->role === 'dokter')
            <div class="rounded-3xl border border-emerald-100 bg-emerald-50/70 p-6 space-y-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Dokumen Verifikasi Dokter</p>
                    <h2 class="mt-2 text-lg font-semibold text-gray-900">Tinjau dokumen profesi sebelum menyetujui akun</h2>
                </div>
                <div class="grid gap-4 md:grid-cols-3 text-sm">
                    <div>
                        <p class="text-gray-500">Nomor IDI</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $user->doctor_idi_number ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Surat Tanda Registrasi</p>
                        @if ($user->doctor_str_file)
                            <a href="{{ asset('storage/' . $user->doctor_str_file) }}" target="_blank" class="mt-1 inline-flex text-emerald-600 hover:text-emerald-800">Buka STR</a>
                        @else
                            <p class="mt-1 font-semibold text-gray-900">-</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-500">Surat Izin Praktik</p>
                        @if ($user->doctor_sip_file)
                            <a href="{{ asset('storage/' . $user->doctor_sip_file) }}" target="_blank" class="mt-1 inline-flex text-emerald-600 hover:text-emerald-800">Buka SIP</a>
                        @else
                            <p class="mt-1 font-semibold text-gray-900">-</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="email" class="text-sm font-semibold text-gray-700">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <label for="age" class="text-sm font-semibold text-gray-700">Umur</label>
                    <input type="number" name="age" id="age" value="{{ old('age', $user->age) }}" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" min="0">
                    @error('age') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="height" class="text-sm font-semibold text-gray-700">Tinggi (cm)</label>
                    <input type="number" step="0.01" name="height" id="height" value="{{ old('height', $user->height) }}" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" min="0">
                    @error('height') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="weight" class="text-sm font-semibold text-gray-700">Berat (kg)</label>
                    <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight', $user->weight) }}" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" min="0">
                    @error('weight') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label for="role" class="text-sm font-semibold text-gray-700">Peran</label>
                <select name="role" id="role" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>
                    <option value="pengguna" {{ $user->role == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                    <option value="dokter" {{ $user->role == 'dokter' ? 'selected' : '' }}>Dokter</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            @if ($user->role === 'dokter')
                <div>
                    <label for="doctor_verification_status" class="text-sm font-semibold text-gray-700">Status Verifikasi Dokter</label>
                    <select name="doctor_verification_status" id="doctor_verification_status" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200">
                        <option value="pending" {{ old('doctor_verification_status', $user->doctor_verification_status) == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ old('doctor_verification_status', $user->doctor_verification_status) == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ old('doctor_verification_status', $user->doctor_verification_status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    @error('doctor_verification_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            @endif
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-600 hover:border-gray-300">Batal</a>
                <button type="submit" class="rounded-full bg-emerald-500 px-6 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-600">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
