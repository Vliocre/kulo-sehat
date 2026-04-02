<section x-data="{ editMode: false }" class="space-y-6">

    <header class="space-y-2">
        <p class="text-xs uppercase tracking-[0.3em] text-green-500 font-semibold">Profil</p>
        <h2 class="text-2xl font-semibold text-gray-900">Informasi Pribadi</h2>
        <p class="text-sm text-gray-600">
            Lihat informasi akun Anda. Klik tombol edit untuk mengubah data.
        </p>
    </header>

    <div>
        <button @click="editMode = !editMode"
            class="inline-flex items-center gap-2 rounded-full bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 transition">
            <span x-text="editMode ? 'Tutup Edit' : 'Edit Profil'"></span>
        </button>
    </div>

    <div x-show="!editMode" x-transition class="grid md:grid-cols-2 gap-4">

        <div class="bg-gray-50 rounded-2xl p-4">
            <p class="text-xs text-gray-500">Nama</p>
            <p class="font-semibold text-gray-900">{{ $user->name }}</p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-4">
            <p class="text-xs text-gray-500">Email</p>
            <p class="font-semibold text-gray-900">{{ $user->email }}</p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-4">
            <p class="text-xs text-gray-500">Umur</p>
            <p class="font-semibold text-gray-900">{{ $user->age ?? '-' }}</p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-4">
            <p class="text-xs text-gray-500">Tinggi</p>
            <p class="font-semibold text-gray-900">
                {{ $user->height ? $user->height . ' cm' : '-' }}
            </p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-4">
            <p class="text-xs text-gray-500">Berat</p>
            <p class="font-semibold text-gray-900">
                {{ $user->weight ? $user->weight . ' kg' : '-' }}
            </p>
        </div>

    </div>

    <form x-show="editMode" x-transition method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-800">Nama Lengkap</label>
            <input type="text" name="name"
                value="{{ old('name', $user->name) }}"
                class="w-full rounded-2xl border border-gray-200 px-4 py-3">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-800">Email</label>
            <input type="email" name="email"
                value="{{ old('email', $user->email) }}"
                class="w-full rounded-2xl border border-gray-200 px-4 py-3">
        </div>

        <div class="grid md:grid-cols-3 gap-4">

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-800">Umur</label>
                <input type="number" name="age"
                    value="{{ old('age', $user->age) }}"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-800">Tinggi (cm)</label>
                <input type="number" name="height"
                    value="{{ old('height', $user->height) }}"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-800">Berat (kg)</label>
                <input type="number" name="weight"
                    value="{{ old('weight', $user->weight) }}"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3">
            </div>

        </div>

       
        <div class="flex items-center gap-4">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-full bg-green-500 px-6 py-3 font-semibold text-white hover:bg-green-600 transition">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm font-semibold text-green-600">
                    Perubahan tersimpan.
                </p>
            @endif
        </div>
    </form>

</section>