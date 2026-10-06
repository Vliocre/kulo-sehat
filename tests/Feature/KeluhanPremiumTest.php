<?php

namespace Tests\Feature;

use App\Models\Keluhan;
use App\Models\KeluhanPremium;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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

        $response = $this->actingAs($patient)
            ->post(route('keluhan.premium.store', $doctor), [
                'judul' => 'Keluhan berulang',
                'pesan' => 'Keluhan muncul sejak kemarin.',
            ]);

        $keluhan = Keluhan::where('jenis', 'premium')->firstOrFail();
        $response->assertRedirect(route('premium.conversations.show', $keluhan));
        $this->assertDatabaseHas('keluhan_premiums', [
            'keluhan_id' => $keluhan->id,
            'sender_id' => $patient->id,
            'pesan' => 'Keluhan muncul sejak kemarin.',
        ]);

        $this->actingAs($patient)
            ->post(route('keluhan.premium.message', $keluhan), [
                'pesan' => 'Tambahan informasi sambil menunggu dokter.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('keluhan_premiums', [
            'keluhan_id' => $keluhan->id,
            'sender_id' => $patient->id,
            'pesan' => 'Tambahan informasi sambil menunggu dokter.',
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
            ->get(route('premium.conversations.show', $keluhan))
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

    public function test_premium_conversation_accepts_images_up_to_five_megabytes(): void
    {
        Storage::fake('local');
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
                'judul' => 'Konsultasi dengan lampiran',
                'pesan' => 'Berikut gambar pendukung kondisi saya.',
                'gambar' => UploadedFile::fake()->create('kondisi.jpg', 4096, 'image/jpeg'),
            ])
            ->assertSessionHasNoErrors();

        $message = KeluhanPremium::firstOrFail();
        $this->assertNotNull($message->gambar);
        Storage::disk('local')->assertExists($message->gambar);
    }

    public function test_each_premium_period_uses_one_doctor_and_old_chat_stays_read_only(): void
    {
        $patient = User::factory()->create([
            'role' => 'pengguna',
            'premium_until' => now()->addMonth(),
        ]);
        $doctor = User::factory()->create([
            'name' => 'Dokter Lama',
            'role' => 'dokter',
            'doctor_verification_status' => 'approved',
            'doctor_verified_at' => now(),
        ]);
        $newDoctor = User::factory()->create([
            'name' => 'Dokter Baru',
            'role' => 'dokter',
            'doctor_verification_status' => 'approved',
            'doctor_verified_at' => now(),
        ]);
        $conversation = Keluhan::create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'jenis' => 'premium',
            'judul' => 'Konsultasi aktif',
            'isi' => 'Pesan pertama',
            'status' => 'menunggu',
            'premium_status' => 'active',
            'premium_started_at' => now(),
        ]);
        $conversation->pesanPremium()->create([
            'sender_id' => $patient->id,
            'pesan' => 'Pesan lama tetap tersimpan.',
        ]);

        $this->actingAs($patient)
            ->get(route('doctors.index'))
            ->assertOk()
            ->assertSee('Buka percakapan aktif')
            ->assertSee('Premium terhubung ke Dokter Lama');

        $this->actingAs($patient)
            ->get(route('premium.conversations.start', $newDoctor))
            ->assertRedirect(route('doctors.index'))
            ->assertSessionHasErrors('premium');

        $this->actingAs($patient)
            ->patch(route('profile.premium.deactivate'))
            ->assertRedirect(route('profile.edit'));

        $this->assertNull($patient->fresh()->premium_until);
        $this->assertSame('paused', $conversation->fresh()->premium_status);

        $this->actingAs($patient)
            ->get(route('premium.conversations.show', $conversation))
            ->assertOk()
            ->assertSee('Pesan lama tetap tersimpan.')
            ->assertSee('Percakapan dijeda')
            ->assertDontSee('Tulis pesan untuk dokter');

        $this->actingAs($patient)
            ->post(route('keluhan.premium.message', $conversation), [
                'pesan' => 'Pesan saat nonaktif',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('keluhan_premiums', [
            'pesan' => 'Pesan saat nonaktif',
        ]);

        $this->actingAs($patient)
            ->post(route('premium.activate'), ['plan' => 'monthly'])
            ->assertRedirect(route('doctors.index'));

        $this->assertTrue($patient->fresh()->hasActivePremium());
        $this->assertSame('paused', $conversation->fresh()->premium_status);

        $this->actingAs($patient)
            ->post(route('keluhan.premium.message', $conversation), [
                'pesan' => 'Pesan ke percakapan lama.',
            ])
            ->assertStatus(409);

        $this->actingAs($patient)
            ->get(route('premium.conversations.start', $newDoctor))
            ->assertOk()
            ->assertSee('Dokter Baru');

        $this->actingAs($patient)
            ->get(route('premium.conversations.show', [
                'keluhan' => $conversation,
                'draft_doctor' => $newDoctor->id,
            ]))
            ->assertOk()
            ->assertSee('Pesan lama tetap tersimpan.')
            ->assertSee('Percakapan baru')
            ->assertSee('Dokter Baru')
            ->assertSee(route('premium.conversations.start', $newDoctor), false);

        $this->actingAs($patient)
            ->post(route('keluhan.premium.store', $newDoctor), [
                'judul' => 'Konsultasi periode baru',
                'pesan' => 'Memulai konsultasi dengan dokter baru.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('keluhans', [
            'user_id' => $patient->id,
            'doctor_id' => $newDoctor->id,
            'judul' => 'Konsultasi periode baru',
            'premium_status' => 'requested',
        ]);

        $this->actingAs($patient)
            ->get(route('premium.conversations.show', $conversation))
            ->assertOk()
            ->assertSee('Pesan lama tetap tersimpan.')
            ->assertSee('Percakapan dijeda');
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
            ->assertSee('Tentang dokter')
            ->assertSee('Aktifkan Premium untuk konsultasi');

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

        $this->actingAs($patient)
            ->get(route('premium.conversations.start', $doctor))
            ->assertOk()
            ->assertSee('Topik percakapan')
            ->assertSee('Tulis pesan pertama untuk dokter')
            ->assertSee('Gambar maks. 5 MB');
    }

    public function test_free_user_can_open_plan_list_and_activate_premium_without_selecting_doctor(): void
    {
        $patient = User::factory()->create(['role' => 'pengguna']);

        $this->actingAs($patient)
            ->get(route('premium.register'))
            ->assertOk()
            ->assertSee('Premium Bulanan')
            ->assertSee('Premium 3 Bulan')
            ->assertSee('Premium Tahunan');

        $this->actingAs($patient)
            ->post(route('premium.activate'), ['plan' => 'quarterly'])
            ->assertRedirect(route('doctors.index'));

        $premiumUser = $patient->fresh();
        $this->assertTrue($premiumUser->hasActivePremium());
        $this->assertTrue($premiumUser->premium_until->isAfter(now()->addDays(89)));

        $this->actingAs($premiumUser)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Premium');
    }

    public function test_premium_user_still_opens_registration_page_from_dashboard_button(): void
    {
        $patient = User::factory()->create([
            'role' => 'pengguna',
            'premium_until' => now()->addMonth(),
        ]);

        $this->actingAs($patient)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Buat Akun')
            ->assertDontSee('>Pilih dokter<', false);

        $this->actingAs($patient)
            ->get(route('premium.register'))
            ->assertOk()
            ->assertSee('Buat dan Aktifkan Akun Premium')
            ->assertSee('Akun Anda sudah Premium.');
    }

    public function test_inactive_premium_account_does_not_show_active_consultation_actions(): void
    {
        $patient = User::factory()->create([
            'role' => 'pengguna',
            'premium_until' => now()->subDay(),
        ]);
        $doctor = User::factory()->create([
            'role' => 'dokter',
            'doctor_verification_status' => 'approved',
            'doctor_verified_at' => now(),
        ]);
        Keluhan::create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'jenis' => 'premium',
            'judul' => 'Konsultasi lama',
            'isi' => 'Riwayat konsultasi',
            'status' => 'menunggu',
            'premium_status' => 'active',
            'premium_started_at' => now()->subWeek(),
        ]);

        $this->actingAs($patient)
            ->get(route('doctors.index'))
            ->assertOk()
            ->assertSee('Aktifkan Premium untuk konsultasi')
            ->assertDontSee('Buka konsultasi aktif');

        $this->actingAs($patient)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Buat Akun')
            ->assertDontSee('Buka konsultasi aktif');
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

        $otherPatient = User::factory()->create(['role' => 'pengguna']);
        $this->actingAs($otherPatient)
            ->get(route('premium.conversations.show', $keluhan))
            ->assertForbidden();
    }
}
