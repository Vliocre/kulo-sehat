<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorWithdrawalRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', 'in:menunggu,disetujui,ditolak'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $requests = DoctorWithdrawalRequest::query()
            ->with('doctor:id,name,email')
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->whereDate('created_at', '<=', $to))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.withdrawals.index', compact('requests', 'filters'));
    }

    public function show(DoctorWithdrawalRequest $withdrawal): View
    {
        $withdrawal->load(['doctor:id,name,email,phone,specialty', 'processor:id,name']);

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(DoctorWithdrawalRequest $withdrawal): RedirectResponse
    {
        $this->process($withdrawal, DoctorWithdrawalRequest::STATUS_APPROVED, null);

        return redirect()->route('admin.withdrawals.show', $withdrawal)->with('success', 'Pengajuan disetujui. Status ini mencatat persetujuan pengajuan, bukan bukti transfer.');
    }

    public function reject(Request $request, DoctorWithdrawalRequest $withdrawal): RedirectResponse
    {
        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'max:2000'],
        ], [
            'admin_note.required' => 'Catatan alasan penolakan wajib diisi.',
        ]);

        $this->process($withdrawal, DoctorWithdrawalRequest::STATUS_REJECTED, $validated['admin_note']);

        return redirect()->route('admin.withdrawals.show', $withdrawal)->with('success', 'Pengajuan ditolak.');
    }

    private function process(DoctorWithdrawalRequest $withdrawal, string $status, ?string $note): void
    {
        DB::transaction(function () use ($withdrawal, $status, $note) {
            $locked = DoctorWithdrawalRequest::query()->lockForUpdate()->findOrFail($withdrawal->id);
            if ($locked->status !== DoctorWithdrawalRequest::STATUS_PENDING) {
                throw ValidationException::withMessages(['status' => 'Pengajuan ini sudah diproses.']);
            }

            $locked->update([
                'status' => $status,
                'admin_note' => $note,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);
        });
    }
}
