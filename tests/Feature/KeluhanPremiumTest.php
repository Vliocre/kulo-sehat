<?php

namespace Tests\Feature;

use App\Models\Keluhan;
use App\Models\KeluhanPremium;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KeluhanPremiumTest extends TestCase
{
    use RefreshDatabase;

    public function test_premium_user_can_start_chat_and_doctor_can_accept_and_reply(): void
    {
        $patient = User::factory()->create([
            'role' => 'pengguna',
            'premium_until' => now()->addMonth(),
        ]);
        $doctor = User::factory()->create([
            'role' => 'dokter',
            'doctor_verification_status' => 'approved',
            'doctor_verified_at' => now(),
        ]);

        $this->actingAs($patient)
            ->post(route('keluhan.premium.store', $doctor), [
                'judul' => 'Keluhan berulang',
                'pesan' => 'Keluhan muncul sejak kemarin.',
            ])
            ->assertRedirect(route('keluhan.index'));

        $keluhan = Keluhan::where('jenis', 'premium')->firstOrFail();
        $this->assertDatabaseHas('keluhan_premiums', [
            'keluhan_id' => $keluhan->id,
            'sender_id' => $patient->id,
            'pesan' => 'Keluhan muncul sejak kemarin.',
        ]);

        $this->actingAs($doctor)
            ->patch(route('dokter.keluhan-premium.accept', $keluhan))
            ->assertRedirect();

        $this->actingAs($doctor)
            ->post(route('keluhan.premium.message', $keluhan), [
                'pesan' => 'Baik, apakah ada demam?',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('keluhan_premiums', [
            'keluhan_id' => $keluhan->id,
            'sender_id' => $doctor->id,
            'pesan' => 'Baik, apakah ada demam?',
        ]);
        $this->assertSame('active', $keluhan->fresh()->premium_status);

        $this->actingAs($patient)
            ->get(route('keluhan.index'))
            ->assertOk()
            ->assertSee('Baik, apakah ada demam?');

        $this->actingAs($doctor)
            ->get(route('dokter.keluhan'))
            ->assertOk()
            ->assertSee('Keluhan berulang');

        $this->actingAs($patient)
            ->patch(route('keluhan.premium.close', $keluhan))
            ->assertRedirect();

        $this->assertSame('closed', $keluhan->fresh()->premium_status);

        $this->actingAs($doctor)
            ->post(route('keluhan.premium.message', $keluhan), ['pesan' => 'Pesan setelah ditutup'])
            ->assertStatus(409);
    }

    public function test_free_user_cannot_start_premium_consultation(): void
    {
        $patient = User::factory()->create(['role' => 'pengguna']);
        $doctor = User::factory()->create([
            'role' => 'dokter',
            'doctor_verification_status' => 'approved',
            'doctor_verified_at' => now(),
        ]);

        $this->actingAs($patient)
            ->from(route('keluhan.index'))
            ->post(route('keluhan.premium.store', $doctor), [
                'judul' => 'Mencoba premium',
                'pesan' => 'Pesan percobaan.',
            ])
            ->assertRedirect(route('keluhan.index'))
            ->assertSessionHasErrors('premium');

        $this->assertDatabaseCount('keluhan_premiums', 0);
    }

    public function test_free_user_can_register_premium_from_selected_doctor(): void
    {
        $patient = User::factory()->create(['role' => 'pengguna']);
        $doctor = User::factory()->create([
            'name' => 'Dokter Pilihan',
            'role' => 'dokter',
            'doctor_verification_status' => 'approved',
            'doctor_verified_at' => now(),
        ]);

        $this->actingAs($patient)
            ->get(route('doctors.index'))
            ->assertOk()
            ->assertSee('Dokter Pilihan')
            ->assertSee('Daftar Premium untuk konsultasi');

        $this->actingAs($patient)
            ->get(route('premium.register', ['doctor' => $doctor->id]))
            ->assertOk()
            ->assertSee('Aktifkan Akun Premium')
            ->assertSee('Dokter Pilihan');

        $this->actingAs($patient)
            ->post(route('premium.activate'), ['doctor_id' => $doctor->id])
            ->assertRedirect(route('doctors.index', ['doctor' => $doctor->id]));

        $this->assertTrue($patient->fresh()->hasActivePremium());

        $this->actingAs($patient)
            ->get(route('doctors.index', ['doctor' => $doctor->id]))
            ->assertOk()
            ->assertSee('Mulai Keluhan Premium');
    }

    public function test_only_assigned_participants_can_access_premium_conversation(): void
    {
        $patient = User::factory()->create(['role' => 'pengguna', 'premium_until' => now()->addMonth()]);
        $doctor = User::factory()->create([
            'role' => 'dokter',
            'doctor_verification_status' => 'approved',
            'doctor_verified_at' => now(),
        ]);
        $otherDoctor = User::factory()->create([
            'role' => 'dokter',
            'doctor_verification_status' => 'approved',
            'doctor_verified_at' => now(),
        ]);
        $keluhan = Keluhan::create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'jenis' => 'premium',
            'judul' => 'Privat',
            'isi' => 'Pesan privat',
            'status' => 'menunggu',
            'premium_status' => 'active',
            'premium_started_at' => now(),
        ]);

        $this->actingAs($otherDoctor)
            ->post(route('keluhan.premium.message', $keluhan), ['pesan' => 'Tidak boleh masuk'])
            ->assertForbidden();

        $this->assertDatabaseMissing('keluhan_premiums', ['pesan' => 'Tidak boleh masuk']);

        Storage::fake('local');
        Storage::disk('local')->put('keluhan-premium/privat.jpg', 'private-image');
        $message = KeluhanPremium::create([
            'keluhan_id' => $keluhan->id,
            'sender_id' => $patient->id,
            'pesan' => 'Lampiran privat',
            'gambar' => 'keluhan-premium/privat.jpg',
        ]);

        $this->actingAs($patient)
            ->get(route('keluhan.premium.attachment', [$keluhan, $message]))
            ->assertOk();

        $this->actingAs($otherDoctor)
            ->get(route('keluhan.premium.attachment', [$keluhan, $message]))
            ->assertForbidden();
    }
}
