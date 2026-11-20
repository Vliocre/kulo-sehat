@extends('layouts.admin')

@section('title', 'Pengajuan Penarikan')
@section('header-subtitle', 'Keuangan dokter')
@section('header-title', 'Pengajuan Penarikan')

@section('content')
    @if(session('success')) <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div> @endif
    <section class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm sm:p-6">
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="grid gap-4 sm:grid-cols-4 sm:items-end">
            <label class="text-sm font-semibold text-slate-700">Status
                <select name="status" class="mt-2 block w-full rounded-xl border-slate-300 text-sm">
                    <option value="">Semua status</option>
                    @foreach(['menunggu', 'disetujui', 'ditolak'] as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>@endforeach
                </select>
            </label>
            <label class="text-sm font-semibold text-slate-700">Dari tanggal<input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="mt-2 block w-full rounded-xl border-slate-300 text-sm"></label>
            <label class="text-sm font-semibold text-slate-700">Sampai tanggal<input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="mt-2 block w-full rounded-xl border-slate-300 text-sm"></label>
            <div class="flex gap-2"><button class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-800">Terapkan</button><a href="{{ route('admin.withdrawals.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600">Reset</a></div>
        </form>
    </section>
    <section class="overflow-hidden rounded-3xl border border-emerald-100 bg-white shadow-sm">
        <div class="overflow-x-auto"><table class="w-full min-w-[760px] text-left text-sm">
            <thead class="bg-emerald-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-6 py-4">Dokter</th><th class="px-6 py-4">Nominal</th><th class="px-6 py-4">Tanggal</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Detail</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($requests as $item)
                    <tr><td class="px-6 py-4"><p class="font-semibold text-slate-900">{{ $item->doctor?->name ?? 'Akun dokter dihapus' }}</p><p class="text-xs text-slate-500">{{ $item->doctor?->email }}</p></td><td class="px-6 py-4 font-semibold">Rp{{ number_format($item->amount, 0, ',', '.') }}</td><td class="px-6 py-4 text-slate-600">{{ $item->created_at->timezone(config('app.timezone'))->translatedFormat('d M Y, H:i') }}</td><td class="px-6 py-4">{{ ucfirst($item->status) }}</td><td class="px-6 py-4"><a class="font-semibold text-emerald-700 hover:underline" href="{{ route('admin.withdrawals.show', $item) }}">Lihat detail</a></td></tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada pengajuan yang cocok dengan filter.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        @if($requests->hasPages()) <div class="border-t border-slate-100 px-6 py-4">{{ $requests->links() }}</div> @endif
    </section>
@endsection
