@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('header-subtitle', 'Panel Pengguna')
@section('header-title', 'Edit Pengguna: ' . $user->name)

@section('content')
    <div class="max-w-3xl mx-auto rounded-3xl bg-white shadow-lg border border-gray-100 p-8 space-y-6">
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
            <div>
                <label for="role" class="text-sm font-semibold text-gray-700">Peran</label>
                <select name="role" id="role" class="mt-2 w-full rounded-2xl border-gray-200 focus:border-emerald-400 focus:ring-emerald-200" required>
                    <option value="pengguna" {{ $user->role == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                    <option value="dokter" {{ $user->role == 'dokter' ? 'selected' : '' }}>Dokter</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-600 hover:border-gray-300">Batal</a>
                <button type="submit" class="rounded-full bg-emerald-500 px-6 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-600">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
