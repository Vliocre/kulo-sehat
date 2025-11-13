@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header-subtitle', 'Halo, ' . Auth::user()->name)
@section('header-title', 'Ringkasan Operasional')

@section('content')
    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6 flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 14a4 4 0 10-8 0m8 0v1a4 4 0 01-4 4h-2a4 4 0 01-4-4v-1m8 0V8a4 4 0 10-8 0v6"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Pengguna</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
            </div>
        </div>
        <div class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6 flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-lime-100 text-lime-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Artikel</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalArticles }}</p>
            </div>
        </div>
        <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-lime-500 text-white shadow-lg p-6">
            <p class="text-sm text-white/80">Performa Platform</p>
            <p class="text-3xl font-bold mt-2">{{ $totalUsers + $totalArticles }} <span class="text-base font-normal text-white/80">asset aktif</span></p>
            <p class="text-sm text-white/80 mt-3">Gabungan pengguna dan artikel siap publikasi.</p>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Perhatian Hari Ini</p>
                        <h2 class="text-xl font-bold text-gray-900 mt-1">Prioritas Tindakan</h2>
                    </div>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Realtime</span>
                </div>
                <div class="mt-4 divide-y divide-gray-100">
                    <div class="py-4 flex items-start gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M5 17h14"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Review artikel terbaru</p>
                            <p class="text-sm text-gray-500">Pastikan artikel baru sesuai standar sebelum dipublikasikan.</p>
                            <a href="{{ route('admin.articles.index') }}" class="text-sm font-semibold text-emerald-600 mt-2 inline-flex items-center gap-1">Pergi ke daftar <span aria-hidden="true">?</span></a>
                        </div>
                    </div>
                    <div class="py-4 flex items-start gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-lime-50 text-lime-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Audit akun pengguna</p>
                            <p class="text-sm text-gray-500">Tinjau ulang akun dokter dan pengguna aktif untuk menjaga akurasi data.</p>
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-emerald-600 mt-2 inline-flex items-center gap-1">Kelola akun <span aria-hidden="true">?</span></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="space-y-6">
            <div class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6 space-y-4">
                <h2 class="text-lg font-bold text-gray-900">Tindakan Cepat</h2>
                <a href="{{ route('admin.articles.index') }}" class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 hover:border-emerald-200 hover:bg-emerald-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Kelola Semua Artikel</p>
                            <p class="text-xs text-gray-500">Edit, tinjau, atau terbitkan konten.</p>
                        </div>
                    </div>
                    <span aria-hidden="true">?</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 hover:border-emerald-200 hover:bg-emerald-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-2xl bg-lime-100 text-lime-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Kelola Akun Pengguna</p>
                            <p class="text-xs text-gray-500">Aktifkan atau sesuaikan peran pengguna.</p>
                        </div>
                    </div>
                    <span aria-hidden="true">?</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between rounded-2xl border border-gray-100 px-4 py-3 hover:border-emerald-200 hover:bg-emerald-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 20H7v-4.828L18.586 3.586z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Perbarui Profil</p>
                            <p class="text-xs text-gray-500">Kelola kredensial admin.</p>
                        </div>
                    </div>
                    <span aria-hidden="true">?</span>
                </a>
            </div>

        </div>
    </section>
@endsection

