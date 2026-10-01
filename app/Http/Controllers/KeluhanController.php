<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\KeluhanPremium;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class KeluhanController extends Controller
{

    public function indexUser()
    {
        $user = auth()->user();

        $keluhans = Keluhan::where('user_id', $user->id)
            ->where('jenis', 'gratis')
            ->latest()
            ->limit(30)
            ->get();

        $premiumKeluhans = Keluhan::with([
                'doctor:id,name,specialty,workplace',
                'pesanPremium' => fn ($query) => $query
                    ->with('sender:id,name,role')
                    ->latest()
                    ->limit(100),
            ])
            ->where('user_id', $user->id)
            ->where('jenis', 'premium')
            ->latest()
            ->limit(20)
            ->get();

        KeluhanPremium::whereIn('keluhan_id', $premiumKeluhans->pluck('id'))
            ->where('sender_id', '!=', $user->id)
            ->whereNull('dibaca_at')
            ->update(['dibaca_at' => now()]);

        return view('keluhan.user', compact('keluhans', 'premiumKeluhans', 'user'))
            ->with('pageMode', 'consultations');
    }

    public function indexDoctors(Request $request)
    {
        $user = $request->user();
        $doctors = User::query()
            ->where('role', 'dokter')
            ->where('doctor_verification_status', 'approved')
            ->select('id', 'name', 'specialty', 'workplace', 'about')
            ->orderBy('name')
            ->get();

        $hasActivePremium = Keluhan::where('user_id', $user->id)
            ->where('jenis', 'premium')
            ->whereIn('premium_status', ['requested', 'active'])
            ->exists();

        return view('keluhan.user', [
            'user' => $user,
            'doctors' => $doctors,
            'keluhans' => collect(),
            'premiumKeluhans' => collect(),
            'hasActivePremium' => $hasActivePremium,
            'pageMode' => 'doctors',
            'selectedDoctorId' => $request->integer('doctor'),
        ]);
    }

    public function premiumRegistration(Request $request)
    {
        $user = $request->user();

        if ($user->hasActivePremium()) {
            return redirect()->route('doctors.index', array_filter([
                'doctor' => $request->integer('doctor') ?: null,
            ]));
        }

        $selectedDoctor = User::query()
            ->where('role', 'dokter')
            ->where('doctor_verification_status', 'approved')
            ->findOrFail($request->integer('doctor'));

        return view('keluhan.user', [
            'user' => $user,
            'selectedDoctor' => $selectedDoctor,
            'keluhans' => collect(),
            'premiumKeluhans' => collect(),
            'pageMode' => 'premium-registration',
        ]);
    }

    public function activatePremium(Request $request)
    {
        $data = $request->validate([
            'doctor_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $doctor = User::query()
            ->where('role', 'dokter')
            ->where('doctor_verification_status', 'approved')
            ->findOrFail($data['doctor_id']);

        $request->user()->update([
            'premium_until' => now()->addDays(30)->endOfDay(),
        ]);

        return redirect()
            ->route('doctors.index', ['doctor' => $doctor->id])
            ->with('ok', 'Akun Premium berhasil diaktifkan selama 30 hari. Silakan mulai konsultasi dengan dokter pilihan Anda.');
    }

   public function store(Request $r)
{
    $r->validate([
        'judul'  => 'required|string|max:255',
        'isi'    => 'required|string|max:5000',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    $path = null;

    if ($r->hasFile('gambar')) {
        $path = $r->file('gambar')->store('keluhan', 'public');
    }

    Keluhan::create([
        'user_id' => auth()->id(),
        'judul'   => $r->judul,
        'isi'     => $r->isi,
        'gambar'  => $path,
        'status'  => 'menunggu'
    ]);

    return back()->with('ok', 'Keluhan dikirim');
}

    public function indexDokter()
    {
        $doctor = auth()->user();

        $keluhans = Keluhan::with('user')
            ->where('jenis', 'gratis')
            ->latest()
            ->limit(30)
            ->get();

        $premiumKeluhans = Keluhan::with([
                'user:id,name,email',
                'pesanPremium' => fn ($query) => $query
                    ->with('sender:id,name,role')
                    ->latest()
                    ->limit(100),
            ])
            ->where('doctor_id', $doctor->id)
            ->where('jenis', 'premium')
            ->latest()
            ->limit(20)
            ->get();

        KeluhanPremium::whereIn('keluhan_id', $premiumKeluhans->pluck('id'))
            ->where('sender_id', '!=', $doctor->id)
            ->whereNull('dibaca_at')
            ->update(['dibaca_at' => now()]);

        return view('doctor.keluhan', compact('keluhans', 'premiumKeluhans'));
    }

    public function jawab(Request $r, Keluhan $keluhan)
    {
        $r->validate([
            'jawaban' => 'required|string|max:5000'
        ]);

        abort_unless(! $keluhan->isPremium(), 404);

        DB::transaction(function () use ($r, $keluhan) {
            $lockedKeluhan = Keluhan::lockForUpdate()->findOrFail($keluhan->id);
            abort_if($lockedKeluhan->jawaban, 409, 'Keluhan ini sudah dijawab dokter lain.');

            $lockedKeluhan->update([
                'doctor_id' => auth()->id(),
                'jawaban' => $r->jawaban,
                'status' => 'dijawab',
            ]);
        });

        return back()->with('ok', 'Jawaban disimpan');
    }

    public function destroy(Request $r, Keluhan $keluhan)
{
    $user = $r->user();
    if (!$user || $user->id !== $keluhan->user_id || $keluhan->isPremium()) {
        abort(403);
    }

    if ($keluhan->gambar) {
        Storage::disk('public')->delete($keluhan->gambar);
    }

    $keluhan->delete();

    return back()->with('ok', 'Keluhan dihapus');
}

    public function storePremium(Request $request, User $doctor)
    {
        $user = $request->user();
        abort_unless($user && $user->role === 'pengguna', 403);
        abort_unless($doctor->isApprovedDoctor(), 404);

        if (! $user->hasActivePremium()) {
            return back()->withErrors(['premium' => 'Paket premium Anda belum aktif atau sudah berakhir.']);
        }

        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'pesan' => ['required', 'string', 'max:5000'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $hasActiveConsultation = Keluhan::where('user_id', $user->id)
            ->where('jenis', 'premium')
            ->whereIn('premium_status', ['requested', 'active'])
            ->exists();

        if ($hasActiveConsultation) {
            return back()->withErrors(['premium' => 'Selesaikan konsultasi premium yang aktif sebelum memulai konsultasi baru.']);
        }

        $imagePath = $request->file('gambar')?->store('keluhan-premium', 'local');

        try {
            DB::transaction(function () use ($data, $doctor, $user, $imagePath) {
                User::lockForUpdate()->findOrFail($user->id);

                if (Keluhan::where('user_id', $user->id)
                    ->where('jenis', 'premium')
                    ->whereIn('premium_status', ['requested', 'active'])
                    ->exists()) {
                    throw ValidationException::withMessages([
                        'premium' => 'Selesaikan konsultasi premium yang aktif sebelum memulai konsultasi baru.',
                    ]);
                }

                $keluhan = Keluhan::create([
                    'user_id' => $user->id,
                    'doctor_id' => $doctor->id,
                    'jenis' => 'premium',
                    'judul' => $data['judul'],
                    'isi' => $data['pesan'],
                    'status' => 'menunggu',
                    'premium_status' => 'requested',
                ]);

                $keluhan->pesanPremium()->create([
                    'sender_id' => $user->id,
                    'pesan' => $data['pesan'],
                    'gambar' => $imagePath,
                ]);
            });
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('local')->delete($imagePath);
            }

            throw $exception;
        }

        return redirect()->route('keluhan.index')->with('ok', 'Permintaan Keluhan Premium berhasil dikirim ke dokter.');
    }

    public function acceptPremium(Request $request, Keluhan $keluhan)
    {
        $this->authorizePremiumDoctor($request, $keluhan);

        DB::transaction(function () use ($keluhan) {
            $lockedKeluhan = Keluhan::lockForUpdate()->findOrFail($keluhan->id);
            abort_unless($lockedKeluhan->premium_status === 'requested', 409, 'Permintaan ini sudah diproses.');
            $lockedKeluhan->update([
                'premium_status' => 'active',
                'premium_started_at' => now(),
            ]);
        });

        return back()->with('ok', 'Keluhan Premium diterima. Ruang percakapan sudah aktif.');
    }

    public function rejectPremium(Request $request, Keluhan $keluhan)
    {
        $this->authorizePremiumDoctor($request, $keluhan);

        DB::transaction(function () use ($keluhan) {
            $lockedKeluhan = Keluhan::lockForUpdate()->findOrFail($keluhan->id);
            abort_unless($lockedKeluhan->premium_status === 'requested', 409, 'Permintaan ini sudah diproses.');
            $lockedKeluhan->update([
                'premium_status' => 'rejected',
                'premium_ended_at' => now(),
            ]);
        });

        return back()->with('ok', 'Permintaan Keluhan Premium ditolak.');
    }

    public function sendPremiumMessage(Request $request, Keluhan $keluhan)
    {
        $this->authorizePremiumParticipant($request, $keluhan);
        abort_unless($keluhan->premium_status === 'active', 409, 'Percakapan ini tidak sedang aktif.');

        $data = $request->validate([
            'pesan' => ['nullable', 'required_without:gambar', 'string', 'max:5000'],
            'gambar' => ['nullable', 'required_without:pesan', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = $request->file('gambar')?->store('keluhan-premium', 'local');

        try {
            $keluhan->pesanPremium()->create([
                'sender_id' => $request->user()->id,
                'pesan' => $data['pesan'] ?? '',
                'gambar' => $imagePath,
            ]);
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('local')->delete($imagePath);
            }

            throw $exception;
        }

        return back()->with('ok', 'Pesan berhasil dikirim.');
    }

    public function premiumAttachment(Request $request, Keluhan $keluhan, KeluhanPremium $message)
    {
        $this->authorizePremiumParticipant($request, $keluhan);
        abort_unless($message->keluhan_id === $keluhan->id && $message->gambar, 404);
        abort_unless(Storage::disk('local')->exists($message->gambar), 404);

        return Storage::disk('local')->response($message->gambar);
    }

    public function closePremium(Request $request, Keluhan $keluhan)
    {
        $this->authorizePremiumParticipant($request, $keluhan);

        DB::transaction(function () use ($keluhan) {
            $lockedKeluhan = Keluhan::lockForUpdate()->findOrFail($keluhan->id);
            abort_unless($lockedKeluhan->premium_status === 'active', 409, 'Konsultasi ini sudah tidak aktif.');
            $lockedKeluhan->update([
                'premium_status' => 'closed',
                'premium_ended_at' => now(),
            ]);
        });

        return back()->with('ok', 'Keluhan Premium telah diselesaikan. Riwayat percakapan tetap tersimpan.');
    }

    private function authorizePremiumDoctor(Request $request, Keluhan $keluhan): void
    {
        abort_unless(
            $keluhan->isPremium()
            && $request->user()->isApprovedDoctor()
            && $keluhan->doctor_id === $request->user()->id,
            403
        );
    }

    private function authorizePremiumParticipant(Request $request, Keluhan $keluhan): void
    {
        $userId = $request->user()->id;
        abort_unless(
            $keluhan->isPremium()
            && ($keluhan->user_id === $userId || $keluhan->doctor_id === $userId),
            403
        );
    }

    public function indexAdmin()
    {
        $query = Keluhan::with('user')->where('jenis', 'gratis')->latest();

        $keluhans = (clone $query)->paginate(10);
        $totalKeluhan = Keluhan::where('jenis', 'gratis')->count();
        $menunggu = Keluhan::where('jenis', 'gratis')->where('status', 'menunggu')->count();
        $dijawab = Keluhan::where('jenis', 'gratis')->where('status', 'dijawab')->count();

        return view('admin.keluhan', compact('keluhans', 'totalKeluhan', 'menunggu', 'dijawab'));
    }

    public function showAdmin(Keluhan $keluhan)
    {
        $keluhan->load('user');

        return view('admin.keluhan-show', compact('keluhan'));
    }

    public function editAdmin(Keluhan $keluhan)
    {
        $keluhan->load('user');

        return view('admin.keluhan-edit', compact('keluhan'));
    }

    public function updateAdmin(Request $r, Keluhan $keluhan)
    {
        $data = $r->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'jawaban' => 'nullable|string',
            'status' => 'required|in:menunggu,dijawab',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_gambar' => 'nullable|boolean',
        ]);

        if ($r->boolean('hapus_gambar') && $keluhan->gambar) {
            Storage::disk('public')->delete($keluhan->gambar);
            $data['gambar'] = null;
        }

        if ($r->hasFile('gambar')) {
            if ($keluhan->gambar) {
                Storage::disk('public')->delete($keluhan->gambar);
            }

            $data['gambar'] = $r->file('gambar')->store('keluhan', 'public');
        }

        if (blank($data['jawaban'])) {
            $data['jawaban'] = null;
            $data['status'] = 'menunggu';
        }

        $keluhan->update($data);

        return redirect()
            ->route('admin.keluhan.show', $keluhan)
            ->with('success', 'Keluhan berhasil diperbarui.');
    }

    public function destroyAdmin(Keluhan $keluhan)
    {
        if ($keluhan->gambar) {
            Storage::disk('public')->delete($keluhan->gambar);
        }

        $keluhan->delete();

        return redirect()
            ->route('admin.keluhan')
            ->with('success', 'Keluhan berhasil dihapus.');
    }
}
