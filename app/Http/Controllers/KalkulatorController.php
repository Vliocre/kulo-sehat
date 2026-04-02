<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KalkulatorController extends Controller
{
    public function index()
    {
        return view('kalkulator');
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

        [$label, $badgeClass, $note] = $this->bmiCategory($bmi);

        $resultHtml = sprintf(
            '<div class="d-flex flex-column gap-2">
                <div class="fw-bold fs-5">BMI Anda: %s</div>
                <div class="d-inline-flex align-items-center gap-2">
                    <span class="badge %s">%s</span>
                    <span class="text-muted">(%s)</span>
                </div>
             </div>',
            $bmiRounded,
            $badgeClass,
            $label,
            $note
        );

        return back()->withInput()->with('hasil', $resultHtml);
    }

    private function bmiCategory(float $bmi): array
    {
        if ($bmi < 18.5) {
            return ['Berat Badan Rendah', 'bg-info', 'Perlu meningkatkan asupan gizi'];
        }

        if ($bmi < 25) {
            return ['Normal', 'bg-success', 'Pertahankan pola hidup sehat'];
        }

        if ($bmi < 30) {
            return ['Berat Badan Berlebih', 'bg-warning text-dark', 'Mulai atur pola makan dan aktivitas'];
        }

        return ['Obesitas', 'bg-danger', 'Disarankan konsultasi dengan ahli'];
    }
}
