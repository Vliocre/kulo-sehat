<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;
use Illuminate\Support\Facades\Storage;

class KeluhanController extends Controller
{

    public function indexUser()
    {
        $keluhans = Keluhan::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('keluhan.user', compact('keluhans'));
    }

   public function store(Request $r)
{
    $r->validate([
        'judul'  => 'required',
        'isi'    => 'required',
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
        $keluhans = Keluhan::with('user')->latest()->get();

        return view('doctor.keluhan', compact('keluhans'));
    }

    public function jawab(Request $r, Keluhan $keluhan)
    {
        $r->validate([
            'jawaban' => 'required'
        ]);

        $keluhan->update([
            'jawaban' => $r->jawaban,
            'status'  => 'dijawab'
        ]);

        return back()->with('ok', 'Jawaban disimpan');
    }

   public function destroy(Request $r, Keluhan $keluhan)
{
    $user = $r->user();
    if (!$user || $user->id !== $keluhan->user_id) {
        abort(403);
    }

    if ($keluhan->gambar) {
        Storage::disk('public')->delete($keluhan->gambar);
    }

    $keluhan->delete();

    return back()->with('ok', 'Keluhan dihapus');
}

    public function indexAdmin()
    {
        $keluhans = Keluhan::with('user')->latest()->get();

        return view('admin.keluhan', compact('keluhans'));
    }
}
