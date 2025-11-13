<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Import model User
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk validasi

class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     * Laravel akan otomatis mencari User berdasarkan ID dari URL (Route Model Binding).
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna di dalam database.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan email unik, KECUALI untuk user ini sendiri
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|in:admin,dokter,pengguna', // Pastikan role-nya valid
        ]);

        // 2. Update data pengguna
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // 3. Redirect kembali ke halaman daftar pengguna dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // Method create, store, dan destroy bisa Anda kembangkan selanjutnya.
    public function create()
    {
        // Tampilkan view untuk form tambah user
    }
}
