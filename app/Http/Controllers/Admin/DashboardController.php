<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index() {
        $admins = Auth::user();

        return view('admin.dashboard', compact('admins'));
    }

    public function profile() {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
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
        $user->save();

        ActivityLog::catat('Ubah Profil', 'Memperbarui informasi profil.');

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Informasi profil berhasil disimpan.');
    }

    public function UpdatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
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
            ActivityLog::catat('Gagal Ubah Password', 'Percobaan ubah password gagal: password saat ini tidak sesuai.');

            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->with('error', 'Password saat ini tidak sesuai!')
                ->withInput()
                ->withFragment('password-section');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Catatan: deskripsi log SENGAJA tidak menyertakan password lama/baru
        // dalam bentuk apapun (termasuk hash-nya) demi keamanan.
        ActivityLog::catat('Ubah Password', 'Berhasil memperbarui password akun.');

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Password berhasil diperbarui.')
            ->withFragment('password-section');
    }

}