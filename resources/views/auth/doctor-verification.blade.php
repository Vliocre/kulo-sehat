<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verifikasi Dokter</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full grid md:grid-cols-2 items-center gap-8">
        <div class="text-white">
            <p class="text-lg">Tahap Lanjutan Pendaftaran Dokter</p>
            <h1 class="text-5xl font-bold mt-2 leading-tight">
                Lengkapi Verifikasi Profesi Anda
            </h1>
            <p class="mt-4 text-white/90">
                Unggah data profesi agar admin dapat memverifikasi bahwa akun ini benar milik dokter sebelum akses dashboard diberikan.
            </p>

            <div class="mt-8 rounded-3xl bg-white/10 p-5 ring-1 ring-white/20">
                <p class="text-xs uppercase tracking-[0.25em] text-emerald-100 font-semibold">Data Akun</p>
                <p class="mt-3 text-lg font-semibold">{{ $registration['name'] }}</p>
                <p class="text-white/80">{{ $registration['email'] }}</p>
            </div>
        </div>

        <div class="bg-white/95 backdrop-blur-sm p-8 sm:p-12 rounded-2xl shadow-2xl">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Verifikasi Dokter</h2>
                <p class="text-gray-500">Lengkapi Nomor IDI dan unggah dokumen STR serta SIP</p>
            </div>

            <form method="POST" action="{{ route('register.doctor.verification.store') }}" enctype="multipart/form-data">
                @csrf

                <div>
                    <input type="text"
                           name="doctor_idi_number"
                           value="{{ old('doctor_idi_number') }}"
                           required
                           placeholder="Nomor IDI"
                           class="w-full border-gray-300 rounded-full px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                    <x-input-error :messages="$errors->get('doctor_idi_number')" class="mt-2"/>
                </div>

                <div class="mt-4">
                    <label for="doctor_str_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Surat Tanda Registrasi (STR)
                    </label>
                    <input id="doctor_str_file"
                           type="file"
                           name="doctor_str_file"
                           accept=".pdf,.jpg,.jpeg,.png"
                           required
                           class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="mt-2 text-xs text-gray-500">Format PDF/JPG/PNG, maksimal 4MB.</p>
                    <x-input-error :messages="$errors->get('doctor_str_file')" class="mt-2"/>
                </div>

                <div class="mt-4">
                    <label for="doctor_sip_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Surat Izin Praktik (SIP)
                    </label>
                    <input id="doctor_sip_file"
                           type="file"
                           name="doctor_sip_file"
                           accept=".pdf,.jpg,.jpeg,.png"
                           required
                           class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="mt-2 text-xs text-gray-500">Format PDF/JPG/PNG, maksimal 4MB.</p>
                    <x-input-error :messages="$errors->get('doctor_sip_file')" class="mt-2"/>
                </div>

                <div class="flex items-center justify-between mt-6 gap-4">
                    <a href="{{ route('register') }}"
                       class="text-sm text-gray-600 underline">
                        Kembali ke daftar
                    </a>

                    <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-full">
                        Daftar Sebagai Dokter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
