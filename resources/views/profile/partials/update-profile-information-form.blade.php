<section class="space-y-6">
    <header class="space-y-2">
        <p class="text-xs uppercase tracking-[0.3em] text-green-500 font-semibold">Profil</p>
        <h2 class="text-2xl font-semibold text-gray-900">Informasi Pribadi</h2>
        <p class="text-sm text-gray-600">
            @if ($user->role === 'dokter')
                Perbarui nama, email, dan lengkapi info praktik agar pasien mengenal Anda.
            @else
                Perbarui nama dan email Anda untuk menjaga akun tetap aman.
            @endif
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-5">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <label for="name" class="text-sm font-semibold text-gray-800">Nama Lengkap</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
                class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
            />
            @error('name')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="email" class="text-sm font-semibold text-gray-800">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
                class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
            />
            @error('email')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <p>{{ __('Email Anda belum terverifikasi.') }}</p>
                    <button
                        form="send-verification"
                        class="mt-2 inline-flex items-center gap-2 text-amber-700 font-semibold hover:text-amber-900 transition"
                    >
                        {{ __('Kirim ulang tautan verifikasi') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h8m0 0v8m0-8l-8 8"/>
                        </svg>
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-semibold text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if ($user->role === 'dokter')
            {{-- Info dokter / penulis --}}
            <div class="grid md:grid-cols-2 gap-4 pt-2">
                <div class="space-y-2">
                    <label for="phone" class="text-sm font-semibold text-gray-800">Nomor Telepon</label>
                    <input
                        id="phone"
                        name="phone"
                        type="text"
                        value="{{ old('phone', $user->phone) }}"
                        autocomplete="tel"
                        class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                    />
                    @error('phone')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label for="specialty" class="text-sm font-semibold text-gray-800">Spesialisasi</label>
                    <input
                        id="specialty"
                        name="specialty"
                        type="text"
                        value="{{ old('specialty', $user->specialty) }}"
                        autocomplete="organization-title"
                        class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                    />
                    @error('specialty')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="workplace" class="text-sm font-semibold text-gray-800">Tempat Praktik</label>
                <input
                    id="workplace"
                    name="workplace"
                    type="text"
                    value="{{ old('workplace', $user->workplace) }}"
                    autocomplete="organization"
                    class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                />
                @error('workplace')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="about" class="text-sm font-semibold text-gray-800">Tentang Dokter</label>
                <textarea
                    id="about"
                    name="about"
                    rows="4"
                    class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                    placeholder="Ringkasan pengalaman, ketertarikan klinis, atau jadwal praktik"
                >{{ old('about', $user->about) }}</textarea>
                @error('about')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500">Data ini muncul di profil publik penulis.</p>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-4">
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-green-500 px-6 py-3 font-semibold text-white shadow-lg shadow-green-500/30 hover:bg-green-600 transition">
                Simpan Perubahan
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                </svg>
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-semibold text-green-600"
                >Perubahan tersimpan.</p>
            @endif
        </div>
    </form>
</section>
