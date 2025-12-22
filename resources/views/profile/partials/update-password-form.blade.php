<section class="space-y-6">
    <header class="space-y-2">
        <p class="text-xs uppercase tracking-[0.3em] text-green-500 font-semibold">Keamanan</p>
        <h2 class="text-2xl font-semibold text-gray-900">Perbarui Kata Sandi</h2>
        <p class="text-sm text-gray-600">
            Gunakan kombinasi kata sandi yang kuat untuk melindungi data kesehatan dan aktivitas Anda.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-5">
        @csrf
        @method('put')

        <div class="space-y-2">
            <label for="update_password_current_password" class="text-sm font-semibold text-gray-800">Kata Sandi Saat Ini</label>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
                class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
            />
            @if ($errors->updatePassword->has('current_password'))
                <p class="text-sm text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div class="space-y-2">
            <label for="update_password_password" class="text-sm font-semibold text-gray-800">Kata Sandi Baru</label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
            />
            @if ($errors->updatePassword->has('password'))
                <p class="text-sm text-red-500">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div class="space-y-2">
            <label for="update_password_password_confirmation" class="text-sm font-semibold text-gray-800">Konfirmasi Kata Sandi</label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
            />
            @if ($errors->updatePassword->has('password_confirmation'))
                <p class="text-sm text-red-500">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gray-900 px-6 py-3 font-semibold text-white shadow-lg shadow-gray-900/20 hover:bg-gray-800 transition">
                Simpan Kata Sandi
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-semibold text-green-600"
                >Kata sandi berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>
