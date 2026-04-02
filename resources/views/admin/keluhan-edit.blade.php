@extends('layouts.admin')

@section('title', 'Edit Keluhan')
@section('header-subtitle', 'Perbarui keluhan dan jawaban dokter')
@section('header-title', 'Edit Keluhan')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">ID Keluhan #{{ $keluhan->id }}</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $keluhan->judul }}</h2>
        </div>
        <a href="{{ route('admin.keluhan.show', $keluhan) }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-600 hover:text-emerald-600 hover:border-emerald-300 transition">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.keluhan.update', $keluhan) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @csrf
        @method('PUT')

        <div class="xl:col-span-2 rounded-3xl bg-white shadow-lg border border-emerald-50 p-6 space-y-4">
            <div>
                <label class="text-sm font-semibold text-gray-700">Judul Keluhan</label>
                <input type="text" name="judul" value="{{ old('judul', $keluhan->judul) }}"
                       class="mt-2 w-full border border-emerald-100 focus:ring-emerald-300 focus:border-emerald-300 rounded-xl p-3 text-sm">
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Isi Keluhan</label>
                <textarea name="isi" rows="6"
                          class="mt-2 w-full border border-emerald-100 focus:ring-emerald-300 focus:border-emerald-300 rounded-xl p-3 text-sm">{{ old('isi', $keluhan->isi) }}</textarea>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Jawaban Dokter</label>
                <textarea name="jawaban" rows="5"
                          class="mt-2 w-full border border-emerald-100 focus:ring-emerald-300 focus:border-emerald-300 rounded-xl p-3 text-sm"
                          placeholder="Tambahkan jawaban dokter jika diperlukan">{{ old('jawaban', $keluhan->jawaban) }}</textarea>
            </div>

            <div class="rounded-2xl bg-emerald-50 p-4 space-y-3">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Lampiran</p>
                @if($keluhan->gambar)
                    <img src="{{ asset('storage/'.$keluhan->gambar) }}" alt="Lampiran keluhan" class="rounded-2xl max-h-56 w-full object-cover">
                @else
                    <p class="text-sm text-emerald-700">Belum ada lampiran.</p>
                @endif
                <input type="file" name="gambar" accept="image/*"
                       class="w-full border border-emerald-100 rounded-xl p-2 text-sm bg-white">
                @if($keluhan->gambar)
                    <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="hapus_gambar" value="1" class="rounded border-gray-300">
                        Hapus lampiran saat ini
                    </label>
                @endif
            </div>
        </div>

        <div class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6 space-y-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">User</p>
                <p class="text-lg font-semibold text-gray-900 mt-2">{{ $keluhan->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $keluhan->user->email }}</p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Status</label>
                <select name="status" class="mt-2 w-full border border-emerald-100 focus:ring-emerald-300 focus:border-emerald-300 rounded-xl p-3 text-sm">
                    <option value="menunggu" @selected(old('status', $keluhan->status) === 'menunggu')>Menunggu</option>
                    <option value="dijawab" @selected(old('status', $keluhan->status) === 'dijawab')>Dijawab</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-emerald-600 text-white px-5 py-3 rounded-xl font-semibold hover:bg-emerald-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
@endsection
