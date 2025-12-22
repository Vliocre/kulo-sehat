<section class="space-y-6">
    <header class="space-y-2">
        <p class="text-xs uppercase tracking-[0.3em] text-red-500 font-semibold">Bahaya</p>
        <h2 class="text-2xl font-semibold text-gray-900">Hapus Akun</h2>
        <p class="text-sm text-gray-600">
            Menghapus akun akan menghilangkan seluruh data dan riwayat aktivitas Anda secara permanen. Pastikan Anda telah mengunduh informasi penting sebelum melanjutkan.
        </p>
    </header>

    <button
        type="button"
        class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 font-semibold text-red-600 border border-red-200 hover:bg-red-50 transition shadow-sm"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-6">
            @csrf
            @method('delete')

            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    Yakin ingin menghapus akun?
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan permanen akun beserta semua data terkait.
                </p>
            </div>

            <div class="space-y-2">
                <label for="password" class="text-sm font-semibold text-gray-800">Kata Sandi</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition"
                    placeholder="Masukkan kata sandi Anda"
                />
                @if ($errors->userDeletion->has('password'))
                    <p class="text-sm text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <button type="button" class="px-5 py-2.5 text-sm font-semibold text-gray-600 rounded-full border border-gray-200 hover:bg-gray-50 transition" x-on:click="$dispatch('close')">
                    Batalkan
                </button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-500/20 hover:bg-red-600 transition">
                    Hapus Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
