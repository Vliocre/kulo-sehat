<?php

namespace App\Http\Controllers;

use App\Models\TopicGuide;
use Illuminate\Support\Str;

class TopicLandingController extends Controller
{
    public function show(string $categorySlug, string $topicSlug)
    {
        $categorySlug = strtolower($categorySlug);
        $topicSlug = strtolower($topicSlug);

        $categories = [
            'bayi' => 'Bayi',
            'remaja' => 'Remaja',
            'dewasa' => 'Dewasa',
            'lansia' => 'Lansia',
        ];

        if (!isset($categories[$categorySlug])) {
            abort(404);
        }

        $palettes = [
            'flu' => 'from-emerald-50 via-green-50 to-emerald-100',
            'demam' => 'from-lime-50 via-green-50 to-emerald-100',
            'diare' => 'from-emerald-50 via-emerald-50 to-teal-100',
            'ruam-popok' => 'from-green-50 via-emerald-50 to-emerald-100',
            'akne' => 'from-emerald-50 via-emerald-50 to-emerald-100',
            'anemia' => 'from-emerald-50 via-green-50 to-lime-100',
            'migrain' => 'from-teal-50 via-emerald-50 to-teal-100',
            'skoliosis' => 'from-emerald-50 via-teal-50 to-emerald-100',
            'depresi' => 'from-slate-50 via-slate-50 to-emerald-50',
            'ansietas' => 'from-teal-50 via-emerald-50 to-emerald-100',
            'insomnia' => 'from-emerald-50 via-emerald-50 to-teal-100',
            'gastritis' => 'from-lime-50 via-green-50 to-emerald-100',
            'hepatitis' => 'from-emerald-50 via-green-50 to-emerald-100',
            'obesitas' => 'from-emerald-50 via-green-50 to-emerald-100',
            'hipertensi' => 'from-emerald-50 via-emerald-50 to-lime-100',
            'diabetes' => 'from-emerald-50 via-emerald-50 to-teal-100',
            'stroke' => 'from-emerald-50 via-emerald-50 to-teal-100',
            'kanker' => 'from-emerald-50 via-green-50 to-emerald-100',
            'asma' => 'from-teal-50 via-emerald-50 to-teal-100',
            'osteoartritis' => 'from-emerald-50 via-emerald-50 to-lime-100',
            'hiperlipidemia' => 'from-lime-50 via-green-50 to-emerald-100',
            'nefritis' => 'from-green-50 via-emerald-50 to-emerald-100',
            'demensia' => 'from-slate-50 via-slate-50 to-emerald-50',
            'alzheimer' => 'from-slate-50 via-emerald-50 to-emerald-100',
            'osteoporosis' => 'from-teal-50 via-emerald-50 to-emerald-100',
            'katarak' => 'from-lime-50 via-emerald-50 to-emerald-100',
            'parkinson' => 'from-emerald-50 via-teal-50 to-emerald-100',
        ];

        $guide = TopicGuide::where('category_slug', $categorySlug)
            ->where('topic_slug', $topicSlug)
            ->first();

        $topics = $this->topicLibrary();
        $topic = data_get($topics, "{$categorySlug}.{$topicSlug}");

        if ($guide) {
            $topic = [
                'title' => $guide->title,
                'summary' => $guide->summary,
                'symptoms' => $guide->symptoms ?? [],
                'care' => $guide->care ?? [],
                'prevention' => $guide->prevention ?? [],
                'danger_signs' => $guide->danger_signs ?? [],
            ];
        } elseif (!$topic) {
            $prettyName = Str::headline(str_replace('-', ' ', $topicSlug));
            $topic = $this->defaultTopic($prettyName, $categories[$categorySlug]);
        }

        return view('articles.topic-landing', [
            'categorySlug' => $categorySlug,
            'categoryName' => $categories[$categorySlug],
            'topicSlug' => $topicSlug,
            'topic' => $topic,
            'palette' => $guide->palette ?? $palettes[$topicSlug] ?? 'from-emerald-50 via-white to-emerald-100',
        ]);
    }

    public function topicLibrary(): array
    {
        $categories = [
            'bayi' => 'Bayi',
            'remaja' => 'Remaja',
            'dewasa' => 'Dewasa',
            'lansia' => 'Lansia',
        ];

        $library = [
            'bayi' => [
                'flu' => [
                    'title' => 'Flu pada Bayi',
                    'summary' => 'Kenali gejala pilek dan flu, cara meredakan hidung tersumbat, serta tanda bahaya yang perlu segera ditangani.',
                    'symptoms' => [
                        'Pilek atau hidung tersumbat lebih dari 2 hari.',
                        'Demam di atas 38 C disertai rewel atau tampak lelah.',
                        'Batuk ringan, napas cepat, atau bunyi grok-grok.',
                        'Mata berair atau kemerahan, sering bersin.',
                        'Asupan minum menurun atau tidur terganggu.',
                    ],
                    'care' => [
                        'Seringkan ASI/susu, jaga cairan tubuh bayi.',
                        'Bersihkan hidung dengan cairan saline lalu gunakan aspirator lembut.',
                        'Gunakan humidifier atau uap hangat, posisikan kepala sedikit lebih tinggi saat tidur.',
                        'Pantau suhu; obat penurun panas hanya sesuai anjuran dokter.',
                        'Periksa napas: jika dada tertarik saat bernapas, segera cari bantuan medis.',
                    ],
                    'prevention' => [
                        'Cuci tangan sebelum menyentuh bayi dan peralatan makan.',
                        'Jauhkan dari orang yang sedang batuk/pilek; gunakan masker saat merawat.',
                        'Rutin bersihkan mainan, dot, dan permukaan yang sering disentuh.',
                        'Pastikan ventilasi kamar baik dan bayi cukup istirahat.',
                        'Ikuti jadwal imunisasi sesuai anjuran tenaga kesehatan.',
                    ],
                    'danger_signs' => [
                        'Demam lebih dari 38.5 C lebih dari 3 hari atau kejang.',
                        'Napas cepat, tarikan dinding dada, atau bibir tampak kebiruan.',
                        'Menolak minum, muntah terus-menerus, atau sangat lemas/mengantuk.',
                    ],
                ],
                'demam' => [
                    'title' => 'Demam pada Bayi',
                    'summary' => 'Langkah aman memantau suhu, menjaga kenyamanan bayi, dan menentukan kapan perlu ke dokter.',
                    'symptoms' => [
                        'Suhu tubuh 38 C atau lebih dengan termometer digital.',
                        'Bayi tampak rewel atau justru lemas dan mengantuk.',
                        'Nafsu minum turun atau frekuensi pipis berkurang.',
                        'Kulit terasa lebih hangat dan pipi memerah.',
                    ],
                    'care' => [
                        'Gunakan pakaian tipis, ruangan sejuk namun tidak dingin.',
                        'Berikan ASI/susu lebih sering untuk mencegah dehidrasi.',
                        'Kompres hangat di dahi/ketiak; hindari kompres alkohol.',
                        'Parasetamol hanya sesuai dosis dan anjuran tenaga medis.',
                        'Catat waktu dan suhu setiap 4-6 jam untuk pemantauan.',
                    ],
                    'prevention' => [
                        'Jaga kebersihan tangan pengasuh dan peralatan makan.',
                        'Hindari kontak dekat dengan orang sakit, gunakan masker.',
                        'Lengkapi imunisasi sesuai jadwal.',
                        'Pastikan bayi cukup istirahat dan asupan gizinya terpenuhi.',
                    ],
                    'danger_signs' => [
                        'Bayi di bawah 3 bulan dengan suhu 38 C atau lebih.',
                        'Kejang, leher kaku, atau menangis melengking.',
                        'Sulit bernapas, bibir/kuku kebiruan.',
                        'Muntah berulang, diare berdarah, atau tanda dehidrasi berat.',
                    ],
                ],
                'diare' => [
                    'title' => 'Diare pada Bayi',
                    'summary' => 'Cara menjaga hidrasi, kebersihan, serta tanda bahaya yang membutuhkan pertolongan segera.',
                    'symptoms' => [
                        'BAB cair lebih sering dari biasanya.',
                        'Perut tampak kembung atau bayi sering kentut.',
                        'Ruam di area popok, rewel saat BAB.',
                        'Tanda dehidrasi: bibir kering, jarang pipis, mata cekung.',
                    ],
                    'care' => [
                        'Teruskan ASI/susu; berikan sedikit tapi sering.',
                        'Gunakan oralit sesuai anjuran jika direkomendasikan tenaga medis.',
                        'Jaga kebersihan popok, segera ganti bila kotor.',
                        'Catat frekuensi BAB dan pipis untuk memantau hidrasi.',
                        'Hindari obat antidiare bebas untuk bayi tanpa anjuran dokter.',
                    ],
                    'prevention' => [
                        'Cuci tangan sebelum menyiapkan makanan/ASI perah.',
                        'Sterilkan botol, dot, dan pompa ASI secara rutin.',
                        'Pastikan air minum dan MPASI higienis.',
                        'Jaga kebersihan lingkungan dan permukaan bermain.',
                    ],
                    'danger_signs' => [
                        'BAB cair lebih dari 6 kali/hari dengan darah atau lendir.',
                        'Tidak pipis 6 jam atau lebih, mata cekung, ubun-ubun cekung.',
                        'Muntah terus-menerus atau demam tinggi.',
                    ],
                ],
                'ruam-popok' => [
                    'title' => 'Ruam Popok',
                    'summary' => 'Menangani kemerahan di area popok agar cepat pulih dan mencegah iritasi berulang.',
                    'symptoms' => [
                        'Kulit merah, hangat, atau bersisik di area popok.',
                        'Bayi rewel saat dibersihkan atau disentuh.',
                        'Muncul bintik kecil atau kulit mengelupas ringan.',
                    ],
                    'care' => [
                        'Ganti popok segera setelah basah/kotor; biarkan kulit kering sebelum dipasang lagi.',
                        'Cuci area dengan air hangat; hindari tisu beralkohol atau berpewangi.',
                        'Gunakan krim penghalang (zinc oxide) setelah setiap ganti popok.',
                        'Biarkan bayi tanpa popok beberapa kali sehari agar kulit bernapas.',
                        'Pilih popok yang tidak terlalu ketat dan menyerap baik.',
                    ],
                    'prevention' => [
                        'Jadwalkan ganti popok teratur, jangan menunggu terlalu penuh.',
                        'Gunakan pembersih lembut tanpa parfum.',
                        'Pastikan area popok benar-benar kering sebelum memakai krim/pembalut.',
                        'Evaluasi merek popok bila iritasi berulang.',
                    ],
                    'danger_signs' => [
                        'Ruam meluas, bernanah, atau disertai demam.',
                        'Timbul vesikel/lepuh atau bau tidak sedap.',
                    ],
                ],
            ],
        ];

        $defaults = [
            'remaja' => ['akne', 'anemia', 'migrain', 'skoliosis', 'depresi', 'ansietas', 'insomnia', 'gastritis', 'hepatitis', 'obesitas'],
            'dewasa' => ['hipertensi', 'diabetes', 'stroke', 'gastritis', 'hepatitis', 'kanker', 'asma', 'osteoartritis', 'hiperlipidemia', 'nefritis'],
            'lansia' => ['demensia', 'alzheimer', 'hipertensi', 'diabetes', 'osteoporosis', 'osteoartritis', 'katarak', 'stroke', 'parkinson', 'insomnia'],
        ];

        foreach ($defaults as $categorySlug => $topics) {
            foreach ($topics as $slug) {
                if (!isset($library[$categorySlug][$slug])) {
                    $pretty = Str::headline(str_replace('-', ' ', $slug));
                    $library[$categorySlug][$slug] = $this->defaultTopic($pretty, $categories[$categorySlug] ?? $categorySlug);
                }
            }
        }

        return $library;
    }

    protected function defaultTopic(string $topicName, string $categoryName): array
    {
        return [
            'title' => $topicName,
            'summary' => "Panduan singkat untuk {$topicName} pada {$categoryName}. Kenali gejala umum, tindakan awal, dan langkah pencegahan.",
            'symptoms' => [
                "Ciri-ciri umum {$topicName} yang kerap muncul.",
                'Perubahan pola makan, tidur, atau aktivitas.',
                'Keluhan tambahan seperti demam, nyeri, atau perubahan warna kulit.',
            ],
            'care' => [
                'Catat kapan keluhan muncul dan apa pemicunya.',
                'Jaga hidrasi dan kenyamanan; pastikan istirahat cukup.',
                'Hubungi tenaga kesehatan bila gejala tidak membaik.',
            ],
            'prevention' => [
                'Jaga kebersihan tangan dan lingkungan sehari-hari.',
                'Perhatikan nutrisi seimbang dan jadwal imunisasi bila relevan.',
                'Batasi paparan terhadap pemicu atau kerumunan saat sakit.',
            ],
            'danger_signs' => [
                'Gejala makin berat atau muncul demam tinggi.',
                'Sulit bernapas, tidak mau minum, atau tampak sangat lemas.',
            ],
        ];
    }
}
