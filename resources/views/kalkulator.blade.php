@extends('layouts.app')

@section('title', 'Kalkulator BMI')

@section('content')
    <style>
        body > div.min-h-screen > nav {
            display: none;
        }

        .bmi-page {
            background:
                radial-gradient(circle at 15% 10%, rgba(34, 197, 94, 0.14), transparent 40%),
                radial-gradient(circle at 85% 0%, rgba(14, 165, 233, 0.12), transparent 45%),
                linear-gradient(135deg, #f6fffb 0%, #f0f7ff 45%, #eefcf6 100%);
        }

        .bmi-card {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.12);
            border-radius: 24px;
        }

        .bmi-hero {
            background: linear-gradient(135deg, rgba(52, 211, 153, 0.22), rgba(56, 189, 248, 0.2));
            border: 1px solid rgba(255, 255, 255, 0.6);
            position: relative;
            overflow: hidden;
        }

        .bmi-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 15% 15%, rgba(16, 185, 129, 0.2), transparent 45%),
                radial-gradient(circle at 85% 0%, rgba(56, 189, 248, 0.2), transparent 45%);
            pointer-events: none;
        }

        .bmi-fade-up {
            animation: bmiFadeUp 700ms ease-out both;
        }

        .bmi-fade-up.delay-1 { animation-delay: 120ms; }
        .bmi-fade-up.delay-2 { animation-delay: 220ms; }
        .bmi-fade-up.delay-3 { animation-delay: 320ms; }

        @keyframes bmiFadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .bmi-pulse {
            animation: bmiPulse 3s ease-in-out infinite;
        }

        @keyframes bmiPulse {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        @media (prefers-reduced-motion: reduce) {
            .bmi-fade-up,
            .bmi-pulse {
                animation: none !important;
            }
        }
    </style>

    <div class="bmi-page min-h-screen px-4 py-8">
        <x-public-navbar />
        <div class="max-w-6xl mx-auto space-y-6 pt-24">
            <div class="bmi-card bmi-hero px-6 py-6 text-center bmi-fade-up delay-1">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 text-emerald-700 text-sm font-semibold uppercase tracking-[0.2em]">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 bmi-pulse"></span>
                        BMI Calculator
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mt-2">Kalkulator BMI</h1>
                    <p class="text-slate-600 mt-2 max-w-2xl mx-auto">
                        Hitung indeks massa tubuh dengan cepat dan dapatkan ringkasan kategori kesehatan secara jelas.
                    </p>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <div class="bmi-card p-6 bmi-fade-up delay-2">
                    <div class="flex items-center justify-between flex-wrap gap-3 mb-5">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold tracking-[0.2em]">
                            BMI CALC
                        </span>
                        <span class="text-slate-500 text-sm">Kalkulasi cepat dan akurat</span>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-slate-800">Input Data Anda</h2>
                        <p class="text-sm text-slate-500">Masukkan informasi untuk menghitung BMI secara akurat.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Jenis Kelamin</label>
                            <select id="gender" class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-300 focus:outline-none bg-white">
                                <option>Pria</option>
                                <option>Wanita</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Tinggi Badan (cm)</label>
                            <input type="number" id="tinggi" placeholder="Contoh: 170"
                                class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-300 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Berat Badan (kg)</label>
                            <input type="number" id="berat" placeholder="Contoh: 65"
                                class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-300 focus:outline-none">
                        </div>

                        <button onclick="hitungBMI()"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-3 rounded-xl font-semibold transition duration-300 transform hover:-translate-y-0.5">
                            Hitung BMI
                        </button>
                    </div>
                </div>

                <div class="space-y-6 bmi-fade-up delay-3">
                    <div class="bmi-card p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-2">Tentang BMI</h3>
                        <p class="text-sm text-slate-600 mb-4">
                            Body Mass Index (BMI) membantu mengukur berat badan ideal berdasarkan tinggi dan berat badan Anda.
                        </p>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-sky-400"></span>&lt; 18.5 : Kurus</div>
                            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>18.5 - 24.9 : Ideal</div>
                            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-yellow-400"></span>25 - 29.9 : Gemuk</div>
                            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-rose-400"></span>&gt; 30 : Obesitas</div>
                        </div>
                    </div>

                    <div id="resultCard" class="bmi-card p-6 text-center opacity-0 scale-95 transition-all duration-500">
                        <h2 class="text-lg font-semibold mb-2 text-slate-700">Hasil Anda</h2>
                        <div id="bmiValue" class="text-4xl font-bold text-emerald-600 mb-1">0</div>
                        <div id="kategori" class="text-sm font-medium text-slate-500 mb-4">-</div>

                        <div class="w-full bg-slate-200 rounded-full h-3">
                            <div id="progressBar"
                                class="h-3 rounded-full bg-emerald-500 transition-all duration-700"
                                style="width:0%">
                            </div>
                        </div>

                        <div class="mt-5 rounded-2xl bg-slate-50 px-4 py-4 text-left">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Rekomendasi Artikel</p>
                            <p id="messageText" class="mt-2 text-sm text-slate-600">
                                Hitung BMI Anda terlebih dahulu untuk melihat rekomendasi artikel yang sesuai.
                            </p>
                            <a id="articleCta"
                               href="{{ route('articles.public.index') }}"
                               class="mt-4 inline-flex items-center rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                Lihat Artikel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function hitungBMI() {
            let tinggi = document.getElementById('tinggi').value;
            let berat = document.getElementById('berat').value;

            if (tinggi === "" || berat === "") {
                alert("Tinggi dan Berat harus diisi!");
                return;
            }

            tinggi = tinggi / 100;
            let bmi = berat / (tinggi * tinggi);
            bmi = bmi.toFixed(1);

            let kategori = "";
            let warna = "bg-emerald-500";
            let progress = Math.min((bmi / 40) * 100, 100);
            let articleSlug = "ideal";
            let message = "";

            if (bmi < 18.5) {
                kategori = "Kurus";
                warna = "bg-sky-400";
                articleSlug = "kurus";
                message = "BMI Anda masuk kategori kurus. Baca artikel kategori kurus untuk memahami cara meningkatkan berat badan dan asupan gizi secara sehat.";
            } else if (bmi >= 18.5 && bmi <= 24.9) {
                kategori = "Ideal";
                warna = "bg-emerald-500";
                articleSlug = "ideal";
                message = "BMI Anda berada di kategori ideal. Baca artikel kategori ideal untuk menjaga pola makan, aktivitas, dan kebiasaan sehat Anda.";
            } else if (bmi >= 25 && bmi <= 29.9) {
                kategori = "Gemuk";
                warna = "bg-yellow-400";
                articleSlug = "gemuk";
                message = "BMI Anda masuk kategori gemuk. Baca artikel kategori gemuk untuk mulai memperbaiki pola makan dan aktivitas fisik secara bertahap.";
            } else {
                kategori = "Obesitas";
                warna = "bg-rose-400";
                articleSlug = "obesitas";
                message = "BMI Anda masuk kategori obesitas. Baca artikel kategori obesitas untuk memahami risiko kesehatan dan langkah penanganan yang perlu diprioritaskan.";
            }

            document.getElementById("bmiValue").innerText = bmi;
            document.getElementById("kategori").innerText = kategori;
            document.getElementById("messageText").innerText = message;
            document.getElementById("articleCta").href = "{{ route('articles.public.index') }}" + "?category=" + articleSlug + "&from=kalkulator";
            document.getElementById("articleCta").innerText = "Baca Artikel " + kategori;

            let progressBar = document.getElementById("progressBar");
            progressBar.style.width = progress + "%";
            progressBar.className = "h-3 rounded-full transition-all duration-700 " + warna;

            let resultCard = document.getElementById("resultCard");
            resultCard.classList.remove("opacity-0", "scale-95");
            resultCard.classList.add("opacity-100", "scale-100");
        }
    </script>
@endsection
