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

        $activePremiumConsultation = Keluhan::with('doctor:id,name')
            ->where('user_id', $user->id)
            ->where('jenis', 'premium')
            ->whereIn('premium_status', ['requested', 'active'])
            ->latest()
            ->first();

        return view('keluhan.user', [
            'user' => $user,
            'doctors' => $doctors,
            'keluhans' => collect(),
            'premiumKeluhans' => collect(),
            'hasActivePremium' => $activePremiumConsultation !== null,
            'activePremiumConsultation' => $activePremiumConsultation,
            'pageMode' => 'doctors',
            'selectedDoctorId' => $request->integer('doctor'),
        ]);
    }

    public function premiumRegistration(Request $request)
    {
        $user = $request->user();

        $selectedDoctor = null;
        if ($request->filled('doctor')) {
            $selectedDoctor = User::query()
                ->where('role', 'dokter')
                ->where('doctor_verification_status', 'approved')
                ->findOrFail($request->integer('doctor'));
        }

        return view('premium.index', [
            'user' => $user,
            'selectedDoctor' => $selectedDoctor,
            'plans' => $this->premiumPlans(),
        ]);
    }

    public function activatePremium(Request $request)
    {
        $data = $request->validate([
            'plan' => ['nullable', 'string', 'in:monthly,quarterly,yearly'],
            'doctor_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $doctor = null;
        if (! empty($data['doctor_id'])) {
            $doctor = User::query()
                ->where('role', 'dokter')
                ->where('doctor_verification_status', 'approved')
                ->findOrFail($data['doctor_id']);
        }

        $planKey = $data['plan'] ?? 'monthly';
        $plan = $this->premiumPlans()[$planKey];

        $user = $request->user();
        $wasPremiumActive = $user->hasActivePremium();

        if (! $wasPremiumActive) {
            Keluhan::where('user_id', $user->id)
                ->where('jenis', 'premium')
                ->whereIn('premium_status', ['requested', 'active'])
                ->update([
                    'premium_status' => 'paused',
                    'premium_ended_at' => now(),
                ]);
        }

        $premiumStartsAt = $wasPremiumActive
            ? $user->premium_until->copy()
            : now();

        $user->update([
            'premium_until' => $premiumStartsAt->addDays($plan['days'])->endOfDay(),
        ]);

        return redirect()
            ->route('doctors.index', array_filter(['doctor' => $doctor?->id]))
            ->with('ok', 'Akun Premium berhasil diaktifkan melalui paket '.$plan['name'].'. Silakan pilih dokter untuk memulai konsultasi.');
    }

    private function premiumPlans(): array
    {
        return [
            'monthly' => [
                'name' => 'Premium Bulanan',
                'duration' => '30 hari',
                'days' => 30,
                'price' => 'Rp49.000',
                'description' => 'Cocok untuk konsultasi kesehatan rutin dalam satu bulan.',
            ],
            'quarterly' => [
                'name' => 'Premium 3 Bulan',
                'duration' => '90 hari',
                'days' => 90,
                'price' => 'Rp129.000',
                'description' => 'Lebih hemat untuk pendampingan kesehatan berkelanjutan.',
                'recommended' => true,
            ],
            'yearly' => [
                'name' => 'Premium Tahunan',
                'duration' => '365 hari',
                'days' => 365,
                'price' => 'Rp399.000',
                'description' => 'Akses paling lengkap untuk kebutuhan keluarga sepanjang tahun.',
            ],
        ];
    }

    public function premiumConversations(Request $request, ?Keluhan $keluhan = null)
    {
        $user = $request->user();

        if ($keluhan) {
            abort_unless($keluhan->isPremium() && $keluhan->user_id === $user->id, 403);
        }

        $conversations = Keluhan::with([
                'doctor:id,name,specialty,workplace,about',
                'pesanPremium.sender:id,name,role',
            ])
            ->where('user_id', $user->id)
            ->where('jenis', 'premium')
            ->latest()
            ->limit(30)
            ->get();

        $hasCurrentConversation = $conversations->contains(
            fn ($item) => in_array($item->premium_status, ['requested', 'active'], true)
        );
        $draftDoctor = null;
        if ($user->hasActivePremium() && ! $hasCurrentConversation && $request->filled('draft_doctor')) {
            $draftDoctor = User::query()
                ->where('role', 'dokter')
                ->where('doctor_verification_status', 'approved')
                ->find($request->integer('draft_doctor'));
        }

        $conversation = $keluhan
            ? $conversations->firstWhere('id', $keluhan->id)
            : $conversations->first(fn ($item) => in_array($item->premium_status, ['requested', 'active'], true))
                ?? $conversations->first();

        if ($conversation) {
            KeluhanPremium::where('keluhan_id', $conversation->id)
                ->where('sender_id', '!=', $user->id)
                ->whereNull('dibaca_at')
                ->update(['dibaca_at' => now()]);
        }

        return view('premium.conversations', compact('user', 'conversations', 'conversation', 'draftDoctor'))
            ->with('showDraft', false);
    }

    public function startPremiumConversation(Request $request, User $doctor)
    {
        $user = $request->user();
        abort_unless($doctor->isApprovedDoctor(), 404);

        if (! $user->hasActivePremium()) {
            return redirect()->route('premium.register', ['doctor' => $doctor->id])
                ->withErrors(['premium' => 'Aktifkan akun Premium sebelum memulai percakapan.']);
        }

        $activeConversation = Keluhan::where('user_id', $user->id)
            ->where('jenis', 'premium')
            ->whereIn('premium_status', ['requested', 'active'])
            ->latest()
            ->first();

        if ($activeConversation) {
            if ($activeConversation->doctor_id === $doctor->id) {
                return redirect()->route('premium.conversations.show', $activeConversation);
            }

            return redirect()->route('doctors.index')
                ->withErrors([
                    'premium' => 'Premium Anda sedang terhubung dengan '.$activeConversation->doctor->name.'. Nonaktifkan Premium atau selesaikan periodenya untuk memilih dokter lain.',
                ]);
        }

        $conversations = Keluhan::with('doctor:id,name,specialty,workplace')
            ->where('user_id', $user->id)
            ->where('jenis', 'premium')
            ->latest()
            ->limit(30)
            ->get();

        return view('premium.conversations', [
            'user' => $user,
            'conversations' => $conversations,
            'conversation' => null,
            'draftDoctor' => $doctor,
            'showDraft' => true,
        ]);
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
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
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
            $keluhan = DB::transaction(function () use ($data, $doctor, $user, $imagePath) {
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

                return $keluhan;
            });
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('local')->delete($imagePath);
            }

            throw $exception;
        }

        return redirect()->route('premium.conversations.show', $keluhan)
            ->with('ok', 'Permintaan konsultasi berhasil dikirim. Ruang percakapan Anda telah dibuat.');
    }

    public function acceptPremium(Request $request, Keluhan $keluhan)
    {
        $this->authorizePremiumDoctor($request, $keluhan);
        abort_unless($keluhan->user?->hasActivePremium(), 409, 'Akun Premium pasien sedang tidak aktif.');

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
        abort_unless(
            $keluhan->user?->hasActivePremium(),
            403,
            'Akun Premium pasien tidak aktif. Percakapan hanya dapat dibaca.'
        );
        $canSendWhileWaiting = $keluhan->premium_status === 'requested'
            && $keluhan->user_id === $request->user()->id;
        abort_unless(
            $keluhan->premium_status === 'active' || $canSendWhileWaiting,
            409,
            'Percakapan ini tidak sedang aktif.'
        );

        $data = $request->validate([
            'pesan' => ['nullable', 'required_without:gambar', 'string', 'max:5000'],
            'gambar' => ['nullable', 'required_without:pesan', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
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
