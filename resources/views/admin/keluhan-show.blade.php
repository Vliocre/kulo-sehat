@extends('layouts.admin')

@section('title', 'Detail Keluhan')
@section('header-subtitle', 'Lihat detail dan jawaban keluhan')
@section('header-title', 'Detail Keluhan')

@section('content')
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">ID Keluhan #{{ $keluhan->id }}</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $keluhan->judul }}</h2>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.keluhan.edit', $keluhan) }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                Edit Keluhan
            </a>
            <a href="{{ route('admin.keluhan') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-600 hover:text-emerald-600 hover:border-emerald-300 transition">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 rounded-3xl bg-white shadow-lg border border-emerald-50 p-6 space-y-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Isi Keluhan</p>
                <p class="text-gray-700 mt-2 leading-relaxed">{{ $keluhan->isi }}</p>
            </div>

            @if($keluhan->gambar)
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Lampiran</p>
                    <img src="{{ asset('storage/'.$keluhan->gambar) }}" alt="Lampiran keluhan" class="mt-3 rounded-2xl max-h-[420px] w-full object-cover">
                </div>
            @endif

            <div class="rounded-2xl bg-emerald-50 p-5">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Jawaban Dokter</p>
                <p class="text-gray-700 mt-2">{{ $keluhan->jawaban ?: 'Belum ada jawaban.' }}</p>
            </div>
        </div>

        <div class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6 space-y-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">User</p>
                <p class="text-lg font-semibold text-gray-900 mt-2">{{ $keluhan->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $keluhan->user->email }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">Status</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $keluhan->status === 'dijawab' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $keluhan->status }}
                </span>
            </div>
            <div class="text-sm text-gray-500 space-y-1">
                <p>Dibuat: {{ $keluhan->created_at?->format('d M Y H:i') }}</p>
                <p>Diperbarui: {{ $keluhan->updated_at?->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
@endsection
