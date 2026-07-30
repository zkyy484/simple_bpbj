<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // <-- WAJIB ditambahkan, tanpa ini Hash::check() error
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pegawai.profile', compact('user'));
    }

    /**
     * Update informasi personal (TANPA password, TANPA avatar).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

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
        $user->Auth::save();

        return redirect()
            ->route('pegawai.profile')
            ->with('success', 'Informasi profil berhasil disimpan.');
    }

    /**
     * Update password saja (form & tombol terpisah).
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Masukkan password saat ini.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->with('error', 'Password saat ini tidak sesuai!')
                ->withInput()
                ->withFragment('password-section');
        }

        $user->password = Hash::make($request->password);
        $user->Auth::save();

        return redirect()
            ->route('pegawai.profile')
            ->with('success', 'Password berhasil diperbarui.')
            ->withFragment('password-section');
    }
}