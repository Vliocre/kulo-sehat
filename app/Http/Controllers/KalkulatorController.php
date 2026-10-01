<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KalkulatorController extends Controller
{
    public function index()
    {
        $weightHistories = auth()->check()
            ? DB::table('weight_histories')
                ->where('user_id', auth()->id())
                ->latest('recorded_at')
                ->take(10)
                ->get()
            : collect();

        return view('kalkulator', compact('weightHistories'));
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'gender' => ['required', 'in:Pria,Wanita'],
            'tinggi' => ['required', 'numeric', 'min:1', 'max:250'],
            'berat' => ['required', 'numeric', 'min:1', 'max:300'],
        ]);

        $heightMeters = $data['tinggi'] / 100;
        $bmi = $data['berat'] / ($heightMeters * $heightMeters);
        $bmiRounded = round($bmi, 1);

        [$label, $badgeClass, $note, $articleSlug] = $this->bmiCategory($bmi);

        if ($request->user()) {
            DB::table('weight_histories')->insert([
                'user_id' => $request->user()->id,
                'height' => $data['tinggi'],
                'weight' => $data['berat'],
                'bmi' => $bmiRounded,
                'bmi_category' => $label,
                'notes' => $note,
                'recorded_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->withInput()->with('bmi_result', [
            'bmi' => $bmiRounded,
            'category' => $label,
            'badge_class' => $badgeClass,
            'note' => $note,
            'article_slug' => $articleSlug,
        ])->with(
            'success',
            $request->user()
                ? 'Riwayat berat badan berhasil disimpan.'
                : 'BMI berhasil dihitung. Login untuk menyimpan riwayat berat badan.'
        );
    }

    private function bmiCategory(float $bmi): array
    {
        if ($bmi < 18.5) {
            return ['Kurus', 'bg-sky-400', 'Perlu meningkatkan asupan gizi secara sehat', 'kurus'];
        }

        if ($bmi < 25) {
            return ['Ideal', 'bg-emerald-500', 'Pertahankan pola hidup sehat', 'ideal'];
        }

        if ($bmi < 30) {
            return ['Gemuk', 'bg-yellow-400', 'Mulai atur pola makan dan aktivitas', 'gemuk'];
        }

        return ['Obesitas', 'bg-rose-400', 'Disarankan konsultasi dengan ahli', 'obesitas'];
    }
}
