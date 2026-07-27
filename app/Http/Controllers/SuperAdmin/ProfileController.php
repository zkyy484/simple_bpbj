<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile.
     */
    public function index()
    {
        $user = auth()->user();

        return view('super-admin.profile', compact('user'));
    }

    /**
     * Update informasi personal (TANPA password, TANPA avatar).
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($user->id_user, 'id_user')],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id_user, 'id_user')],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'nip' => ['required', 'string', 'max:30', Rule::unique('users', 'nip')->ignore($user->id_user, 'id_user')],
            'alamat' => ['nullable', 'string'],
        ], [
            'nip.unique' => 'NIP sudah digunakan oleh pengguna lain.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'username.unique' => 'Username sudah digunakan oleh pengguna lain.',
        ]);

        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->no_telepon = $validated['no_telepon'] ?? null;
        $user->nip = $validated['nip'];
        $user->alamat = $validated['alamat'] ?? null;
        $user->save();

        return redirect()
            ->route('super.profile')
            ->with('success', 'Informasi profil berhasil disimpan.');
    }

    /**
     * Update password saja (form & tombol terpisah).
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi Input
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Masukkan password saat ini.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // 2. Cek Password Saat Ini -> Kirim session 'error' untuk SweetAlert
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->with('error', 'Password saat ini tidak sesuai!') // <- Memicu SweetAlert Error
                ->withInput()
                ->withFragment('password-section');
        }

        // 3. Simpan Password Baru -> Kirim session 'success' untuk SweetAlert
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('super.profile')
            ->with('success', 'Password berhasil diperbarui.') // <- Memicu SweetAlert Success
            ->withFragment('password-section');
    }
}