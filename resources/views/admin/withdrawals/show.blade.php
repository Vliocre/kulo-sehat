@extends('layouts.admin')

@section('title', 'Detail Pengajuan Penarikan')
@section('header-subtitle', 'Keuangan dokter')
@section('header-title', 'Detail Pengajuan')
@section('header-actions')<a href="{{ route('admin.withdrawals.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">Kembali ke daftar</a>@endsection

@section('content')
    @if(session('success')) <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">{{ $errors->first() }}</div> @endif
    <section class="grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
        <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-emerald-700">Informasi pengajuan</p>
            <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                <div><dt class="text-sm text-slate-500">Dokter</dt><dd class="mt-1 font-semibold">{{ $withdrawal->doctor?->name ?? 'Akun dokter dihapus' }}</dd></div>
                <div><dt class="text-sm text-slate-500">Email</dt><dd class="mt-1 font-semibold">{{ $withdrawal->doctor?->email ?? '—' }}</dd></div>
                <div><dt class="text-sm text-slate-500">Nominal</dt><dd class="mt-1 text-2xl font-bold">Rp{{ number_format($withdrawal->amount, 0, ',', '.') }}</dd></div>
                <div><dt class="text-sm text-slate-500">Tanggal pengajuan</dt><dd class="mt-1 font-semibold">{{ $withdrawal->created_at->timezone(config('app.timezone'))->translatedFormat('d M Y, H:i') }}</dd></div>
                <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1 font-semibold">{{ ucfirst($withdrawal->status) }}</dd></div>
                <div><dt class="text-sm text-slate-500">Diproses oleh</dt><dd class="mt-1 font-semibold">{{ $withdrawal->processor?->name ?? '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-sm text-slate-500">Catatan admin</dt><dd class="mt-1 whitespace-pre-line font-medium">{{ $withdrawal->admin_note ?: 'Tidak ada catatan.' }}</dd></div>
            </dl>
            <p class="mt-7 rounded-2xl bg-amber-50 p-4 text-sm leading-6 text-amber-800">Persetujuan mencatat keputusan atas pengajuan. Status ini tidak menyatakan bahwa transfer uang telah dilakukan.</p>
        </div>
        @if($withdrawal->status === 'menunggu')
            <div class="space-y-5">
                <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold">Setujui pengajuan</h2>
                    <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" onsubmit="return confirm('Setujui pengajuan ini? Ini hanya mencatat persetujuan, bukan bukti transfer.');" class="mt-4">@csrf @method('PATCH')<button class="w-full rounded-xl bg-emerald-700 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-800">Setujui</button></form>
                </div>
                <div class="rounded-3xl border border-rose-100 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold">Tolak pengajuan</h2>
                    <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="mt-4">@csrf @method('PATCH')<label for="admin_note" class="text-sm font-semibold text-slate-700">Alasan penolakan</label><textarea id="admin_note" name="admin_note" required maxlength="2000" rows="4" class="mt-2 w-full rounded-xl border-slate-300 text-sm" placeholder="Jelaskan alasan penolakan">{{ old('admin_note') }}</textarea>@error('admin_note')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror<button class="mt-3 w-full rounded-xl bg-rose-600 px-4 py-3 text-sm font-bold text-white hover:bg-rose-700">Tolak pengajuan</button></form>
                </div>
            </div>
        @endif
    </section>
@endsection
