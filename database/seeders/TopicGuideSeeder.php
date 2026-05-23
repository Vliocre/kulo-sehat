<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TopicGuide;

class TopicGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $samples = [
            'bayi' => [
                'flu' => [
                    'title' => 'Flu pada Bayi',
                    'summary' => 'Pilek dan demam ringan; perhatikan hidrasi dan nafas.',
                    'symptoms' => ['Hidung meler', 'Batuk ringan', 'Demam rendah'],
                    'care' => ['Pastikan bayi minum ASI/air cukup', 'Bersihkan hidung dengan aspirator bila perlu'],
                    'prevention' => ['Cuci tangan sebelum menyentuh bayi', 'Hindari kerumunan saat musim flu'],
                    'danger_signs' => ['Sulit bernapas', 'Demam tinggi > 38.5°C', 'Kurang respon'],
                    'palette' => 'from-emerald-50 via-white to-emerald-100',
                ],
            ],
            'remaja' => [
                'akne' => [
                    'title' => 'Perawatan Akne Remaja',
                    'summary' => 'Perawatan dasar dan tips kebersihan kulit untuk mengurangi jerawat.',
                    'symptoms' => ['Komedo', 'Bintil merah', 'Nodul jika berat'],
                    'care' => ['Cuci muka dua kali sehari', 'Hindari memencet jerawat'],
                    'prevention' => ['Gunakan produk non-komedogenik', 'Jaga pola makan seimbang'],
                    'danger_signs' => ['Bekas luka luas', 'Infeksi Sekunder'],
                    'palette' => 'from-amber-50 via-white to-amber-100',
                ],
            ],
            'dewasa' => [
                'demam' => [
                    'title' => 'Mengelola Demam',
                    'summary' => 'Cara memantau dan menurunkan demam pada orang dewasa.',
                    'symptoms' => ['Kenaikan suhu tubuh', 'Nyeri otot', 'Menggigil'],
                    'care' => ['Istirahat cukup', 'Kompres dingin', 'Obat penurun demam sesuai petunjuk'],
                    'prevention' => ['Vaksinasi bila relevan', 'Hindari kontak dengan penderita penyakit menular'],
                    'danger_signs' => ['Demam > 3 hari', 'Kesulitan bernapas', 'Kesadaran menurun'],
                    'palette' => 'from-sky-50 via-white to-sky-100',
                ],
            ],
            'lansia' => [
                'hipertensi' => [
                    'title' => 'Mengelola Hipertensi pada Lansia',
                    'summary' => 'Kontrol tekanan darah melalui gaya hidup dan obat bila perlu.',
                    'symptoms' => ['Sering sakit kepala', 'Pusing', 'Kelelahan'],
                    'care' => ['Kontrol berat badan', 'Konsumsi obat sesuai resep'],
                    'prevention' => ['Kurangi garam', 'Olahraga ringan teratur'],
                    'danger_signs' => ['Nyeri dada', 'Sesak nafas', 'Kehilangan kesadaran'],
                    'palette' => 'from-rose-50 via-white to-rose-100',
                ],
            ],
        ];

        foreach ($samples as $category => $topics) {
            foreach ($topics as $slug => $data) {
                TopicGuide::updateOrCreate(
                    [
                        'category_slug' => $category,
                        'topic_slug' => $slug,
                    ],
                    [
                        'title' => $data['title'],
                        'summary' => $data['summary'] ?? null,
                        'symptoms' => $data['symptoms'] ?? [],
                        'care' => $data['care'] ?? [],
                        'prevention' => $data['prevention'] ?? [],
                        'danger_signs' => $data['danger_signs'] ?? [],
                        'palette' => $data['palette'] ?? null,
                    ]
                );
            }
        }
    }
}
