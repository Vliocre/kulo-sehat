<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorWithdrawalRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    public function index(Request $request): View
    {
        $now = Carbon::now(config('app.timezone'));
        $periodStart = $now->copy()->startOfMonth();
        $periodEnd = $now->copy()->endOfMonth();
        $spent = DoctorWithdrawalRequest::query()
            ->where('doctor_id', $request->user()->id)
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->whereIn('status', [DoctorWithdrawalRequest::STATUS_PENDING, DoctorWithdrawalRequest::STATUS_APPROVED])
            ->sum('amount');

        $requests = DoctorWithdrawalRequest::query()
            ->where('doctor_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('doctor.withdrawals.index', [
            'requests' => $requests,
            'monthlyLimit' => DoctorWithdrawalRequest::MONTHLY_LIMIT,
            'spent' => (int) $spent,
            'remaining' => max(0, DoctorWithdrawalRequest::MONTHLY_LIMIT - (int) $spent),
            'periodStart' => $periodStart,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:'.DoctorWithdrawalRequest::MONTHLY_LIMIT],
        ], [
            'amount.required' => 'Nominal pengajuan wajib diisi.',
            'amount.integer' => 'Nominal harus berupa bilangan bulat rupiah.',
            'amount.min' => 'Nominal pengajuan harus lebih besar dari nol.',
            'amount.max' => 'Nominal tidak boleh melebihi batas bulanan Rp200.000.',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // Kunci baris dokter agar dua pengajuan bersamaan dihitung berurutan.
            $doctor = $request->user()->newQuery()->lockForUpdate()->findOrFail($request->user()->id);
            $now = Carbon::now(config('app.timezone'));
            $spent = DoctorWithdrawalRequest::query()
                ->where('doctor_id', $doctor->id)
                ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
                ->whereIn('status', [DoctorWithdrawalRequest::STATUS_PENDING, DoctorWithdrawalRequest::STATUS_APPROVED])
                ->sum('amount');
            $remaining = DoctorWithdrawalRequest::MONTHLY_LIMIT - (int) $spent;

            if ((int) $validated['amount'] > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal melebihi sisa batas pengajuan bulan ini sebesar Rp'.number_format(max(0, $remaining), 0, ',', '.').'.',
                ]);
            }

            $doctor->doctorWithdrawalRequests()->create([
                'amount' => (int) $validated['amount'],
                'status' => DoctorWithdrawalRequest::STATUS_PENDING,
            ]);
        });

        return redirect()->route('doctor.withdrawals.index')->with('success', 'Pengajuan penarikan berhasil dikirim dan menunggu persetujuan admin.');
    }
}
