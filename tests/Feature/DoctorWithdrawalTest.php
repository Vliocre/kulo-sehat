<?php

use App\Models\DoctorWithdrawalRequest;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    config(['app.timezone' => 'Asia/Jakarta']);
    Carbon::setTestNow(Carbon::parse('2026-10-26 10:00:00', 'Asia/Jakarta'));
});

afterEach(function () {
    Carbon::setTestNow();
});

function approvedDoctor(array $attributes = []): User
{
    return User::factory()->create(array_merge([
        'role' => 'dokter',
        'doctor_verification_status' => 'approved',
        'doctor_verified_at' => now(),
    ], $attributes));
}

it('validates positive integer amounts and creates a pending request for the logged in doctor', function () {
    $doctor = approvedDoctor();

    $this->actingAs($doctor)->post(route('doctor.withdrawals.store'), ['amount' => 0])
        ->assertSessionHasErrors('amount');
    $this->actingAs($doctor)->post(route('doctor.withdrawals.store'), ['amount' => 100000])
        ->assertRedirect(route('doctor.withdrawals.index'));

    $this->assertDatabaseHas('doctor_withdrawal_requests', [
        'doctor_id' => $doctor->id,
        'amount' => 100000,
        'status' => 'menunggu',
    ]);
});

it('counts pending and approved requests toward the calendar month limit but excludes rejected requests', function () {
    $doctor = approvedDoctor();
    $doctor->doctorWithdrawalRequests()->create(['amount' => 80000, 'status' => 'menunggu']);
    $doctor->doctorWithdrawalRequests()->create(['amount' => 70000, 'status' => 'disetujui']);
    $doctor->doctorWithdrawalRequests()->create(['amount' => 90000, 'status' => 'ditolak']);

    $this->actingAs($doctor)->get(route('doctor.withdrawals.index'))
        ->assertOk()
        ->assertSee('Rp150.000')
        ->assertSee('Rp50.000');

    $this->actingAs($doctor)->post(route('doctor.withdrawals.store'), ['amount' => 50001])
        ->assertSessionHasErrors('amount');
    $this->actingAs($doctor)->post(route('doctor.withdrawals.store'), ['amount' => 50000])
        ->assertRedirect(route('doctor.withdrawals.index'));
});

it('does not count requests from a different calendar month', function () {
    $doctor = approvedDoctor();
    $old = $doctor->doctorWithdrawalRequests()->create(['amount' => 200000, 'status' => 'disetujui']);
    $old->forceFill(['created_at' => Carbon::parse('2026-09-30 23:59:59', 'Asia/Jakarta')])->saveQuietly();

    $this->actingAs($doctor)->post(route('doctor.withdrawals.store'), ['amount' => 200000])
        ->assertRedirect(route('doctor.withdrawals.index'));
});

it('restricts doctor and admin pages to their respective roles and doctors only see their own records', function () {
    $doctor = approvedDoctor();
    $otherDoctor = approvedDoctor();
    $request = $otherDoctor->doctorWithdrawalRequests()->create(['amount' => 5000, 'status' => 'menunggu']);
    $patient = User::factory()->create(['role' => 'pengguna']);
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($doctor)->get(route('admin.withdrawals.index'))->assertForbidden();
    $this->actingAs($patient)->get(route('doctor.withdrawals.index'))->assertForbidden();
    $this->actingAs($doctor)->get(route('doctor.withdrawals.index'))->assertOk()->assertDontSee('Rp5.000');
    $this->actingAs($admin)->get(route('admin.withdrawals.index'))->assertOk()->assertSee($otherDoctor->name);
    $this->actingAs($doctor)->patch(route('admin.withdrawals.approve', $request))->assertForbidden();
});

it('allows admin to approve or reject once and requires a rejection note', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $doctor = approvedDoctor();
    $request = $doctor->doctorWithdrawalRequests()->create(['amount' => 25000, 'status' => 'menunggu']);

    $this->actingAs($admin)->patch(route('admin.withdrawals.reject', $request), ['admin_note' => 'Data rekening belum lengkap.'])
        ->assertRedirect(route('admin.withdrawals.show', $request));
    expect($request->fresh()->status)->toBe('ditolak')
        ->and($request->fresh()->admin_note)->toBe('Data rekening belum lengkap.');

    $this->actingAs($admin)->patch(route('admin.withdrawals.approve', $request))->assertSessionHasErrors('status');

    $pending = $doctor->doctorWithdrawalRequests()->create(['amount' => 30000, 'status' => 'menunggu']);
    $this->actingAs($admin)->patch(route('admin.withdrawals.reject', $pending), ['admin_note' => ''])
        ->assertSessionHasErrors('admin_note');
    $this->actingAs($admin)->patch(route('admin.withdrawals.approve', $pending))
        ->assertRedirect(route('admin.withdrawals.show', $pending));
    expect($pending->fresh()->status)->toBe('disetujui')
        ->and($pending->fresh()->processed_by)->toBe($admin->id);
});
