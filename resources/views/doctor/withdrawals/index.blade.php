  <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengajuan Penarikan - Helavio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-lime-100 font-sans text-slate-900">
    <x-public-navbar />
    <main class="mx-auto max-w-6xl px-5 pb-16 pt-32 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">← Kembali ke profil</a>
                <p class="mt-5 text-xs font-bold uppercase tracking-[.25em] text-emerald-700">Panel Dokter</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Pengajuan Penarikan</h1>
                <p class="mt-2 max-w-2xl text-slate-600">Batas ini mengatur nominal pengajuan per bulan. Aplikasi belum mencatat saldo atau pendapatan konsultasi.</p>
            </div>
        </div>

        @if (session('success'))
            <div role="status" class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif

        <section class="grid gap-5 md:grid-cols-3">
            <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Batas bulan ini</p>
                <p class="mt-3 text-2xl font-bold">Rp{{ number_format($monthlyLimit, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-slate-500">Periode {{ $periodStart->translatedFormat('F Y') }}</p>
            </div>
            <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Menunggu atau disetujui</p>
                <p class="mt-3 text-2xl font-bold">Rp{{ number_format($spent, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-slate-500">Pengajuan ditolak tidak dihitung</p>
            </div>
            <div class="rounded-3xl border border-emerald-200 bg-emerald-700 p-6 text-white shadow-sm">
                <p class="text-sm font-medium text-emerald-100">Sisa batas pengajuan</p>
                <p class="mt-3 text-2xl font-bold">Rp{{ number_format($remaining, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-emerald-100">Bukan informasi saldo tersedia</p>
            </div>
        </section>

        <section class="mt-8 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold">Ajukan nominal</h2>
                <p class="mt-1 text-sm text-slate-500">Masukkan nominal rupiah yang ingin diajukan, maksimal sebesar sisa batas bulan ini.</p>
            </div>
            <form id="withdrawal-form" method="POST" action="{{ route('doctor.withdrawals.store') }}" class="max-w-xl">
                @csrf
                <label for="amount" class="mb-2 block text-sm font-semibold text-slate-700">Nominal penarikan</label>
                <div class="flex overflow-hidden rounded-xl border border-slate-300 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-100">
                    <span class="border-r border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-500">Rp</span>
                    <input id="amount" name="amount" type="number" min="1" max="{{ $remaining }}" step="1" value="{{ old('amount') }}" required class="w-full border-0 px-4 py-3 focus:ring-0" placeholder="Contoh: 100000" aria-describedby="amount-help">
                </div>
                <p id="amount-help" class="mt-2 text-xs text-slate-500">Angka tanpa titik atau koma. Maksimum saat ini: Rp{{ number_format($remaining, 0, ',', '.') }}.</p>
                @error('amount') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                <button id="open-confirmation" type="button" {{ $remaining < 1 ? 'disabled' : '' }} class="mt-5 inline-flex items-center rounded-xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-50">Ajukan Penarikan</button>
            </form>
        </section>

        <section class="mt-8 overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                <h2 class="text-xl font-bold">Riwayat pengajuan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-6 py-4">Tanggal</th><th class="px-6 py-4">Nominal</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Catatan admin</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($requests as $item)
                            @php($statusClass = ['menunggu' => 'bg-amber-50 text-amber-700', 'disetujui' => 'bg-emerald-50 text-emerald-700', 'ditolak' => 'bg-rose-50 text-rose-700'][$item->status])
                            <tr>
                                <td class="px-6 py-4 text-slate-600">{{ $item->created_at->timezone(config('app.timezone'))->translatedFormat('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 font-semibold">Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4"><span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ ucfirst($item->status) }}</span></td>
                                <td class="max-w-sm px-6 py-4 text-slate-600">{{ $item->admin_note ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada pengajuan penarikan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages()) <div class="border-t border-slate-100 px-6 py-4">{{ $requests->links() }}</div> @endif
        </section>
    </main>

    <dialog id="confirmation-dialog" class="w-[calc(100%-2rem)] max-w-md rounded-3xl p-0 shadow-2xl backdrop:bg-slate-950/40">
        <div class="p-7">
            <h2 class="text-xl font-bold">Konfirmasi pengajuan</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">Anda akan mengajukan <strong id="confirmation-amount" class="text-slate-900"></strong>. Pengajuan akan menunggu persetujuan admin.</p>
            <div class="mt-7 flex justify-end gap-3">
                <button id="cancel-confirmation" type="button" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Periksa lagi</button>
                <button id="confirm-submit" type="button" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-800">Konfirmasi &amp; Ajukan</button>
            </div>
        </div>
    </dialog>
    <script>
        const amountInput = document.getElementById('amount');
        const form = document.getElementById('withdrawal-form');
        const dialog = document.getElementById('confirmation-dialog');
        document.getElementById('open-confirmation').addEventListener('click', () => {
            if (!form.reportValidity()) return;
            document.getElementById('confirmation-amount').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(Number(amountInput.value));
            dialog.showModal();
        });
        document.getElementById('cancel-confirmation').addEventListener('click', () => dialog.close());
        document.getElementById('confirm-submit').addEventListener('click', () => form.submit());
        dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
    </script>
</body>
</html>
