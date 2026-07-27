<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    /**
     * Daftar akun aktif (bukan arsip)
     */
public function index(Request $request)
{
    $search = $request->query('search');
    $admins = auth()->user();

    $accounts = User::with('subBagian')
        ->where('status', 'aktif')
        ->whereIn('role', ['admin', 'pegawai']) // Hanya admin & pegawai
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        })
        ->orderBy('nama_lengkap')
        ->paginate(10)
        ->withQueryString();

    $subBagians = SubBagian::all();

    return view('super-admin.akun.index', compact('accounts', 'subBagians', 'admins'));
}
    /**
     * Daftar akun yang diarsipkan (status nonaktif)
     */
    public function arsip(Request $request)
    {
        $search = $request->query('search');
        $admins = auth()->user();

        $accounts = User::with('subBagian')
            ->where('status', 'nonaktif')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_lengkap')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.akun.arsip', compact('accounts', 'admins'));
    }

    public function create()
    {
        $subBagians = SubBagian::all();
        $admins = auth()->user();

        return view('super-admin.akun.create', compact('subBagians', 'admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'  => ['required', 'string', 'max:50'],
            'nip'           => ['required', 'string', 'max:30', 'unique:users,nip'],
            'email'         => ['required', 'email', 'max:50', 'unique:users,email'],
            'no_telepon'    => ['required', 'string', 'max:20'],
            'id_sub_bagian' => ['required', 'exists:sub_bagians,id_sub_bagian'],
            'alamat'        => ['required', 'string'],
            'username'      => ['required', 'string', 'max:50', 'unique:users,username'],
            'password'      => ['required', 'string', 'min:8'],
            'role'          => ['required', Rule::in(['super_admin', 'admin', 'pegawai'])],
        ], [
            // Pesan Error Kustom
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'nip.required'           => 'NIP wajib diisi.',
            'nip.unique'             => 'NIP sudah digunakan oleh pengguna lain.',
            'email.required'         => 'Alamat email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah digunakan oleh pengguna lain.',
            'no_telepon.required'    => 'Nomor telepon wajib diisi.',
            'id_sub_bagian.required' => 'Sub bagian wajib dipilih.',
            'id_sub_bagian.exists'   => 'Sub bagian yang dipilih tidak valid.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'username.required'      => 'Username wajib diisi.',
            'username.unique'        => 'Username sudah digunakan oleh pengguna lain.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal harus 8 karakter.',
            'role.required'          => 'Role wajib dipilih.',
            'role.in'                => 'Role yang dipilih tidak valid.',
        ]);

        User::create([
            'nama_lengkap'  => $validated['nama_lengkap'],
            'nip'           => $validated['nip'],
            'email'         => $validated['email'],
            'username'      => $validated['username'],
            'password'      => Hash::make($validated['password']),
            'no_telepon'    => $validated['no_telepon'],
            'alamat'        => $validated['alamat'],
            'id_sub_bagian' => $validated['id_sub_bagian'],
            'role'          => $validated['role'],
            'status'        => 'aktif',
        ]);

        return redirect()
            ->route('index.akun')
            ->with('success', 'Akun pegawai berhasil ditambahkan.');
    }

    /**
     * Update lewat modal — ID dikirim via hidden input (id_user)
     */
    public function update(Request $request)
    {
        $user = User::findOrFail($request->id_user);

        $validated = $request->validate([
            'id_user'       => ['required', 'exists:users,id_user'],
            'nama_lengkap'  => ['required', 'string', 'max:50'],
            'nip'           => ['required', 'string', 'max:30', Rule::unique('users', 'nip')->ignore($user->id_user, 'id_user')],
            'email'         => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($user->id_user, 'id_user')],
            'no_telepon'    => ['required', 'string', 'max:20'],
            'id_sub_bagian' => ['required', 'exists:sub_bagians,id_sub_bagian'],
            'alamat'        => ['required', 'string'],
            'role'          => ['required', Rule::in(['super_admin', 'admin', 'pegawai'])],
        ], [
            'nip.unique'   => 'NIP sudah digunakan oleh pengguna lain.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
        ]);

        $user->update([
            'nama_lengkap'  => $validated['nama_lengkap'],
            'nip'           => $validated['nip'],
            'email'         => $validated['email'],
            'no_telepon'    => $validated['no_telepon'],
            'id_sub_bagian' => $validated['id_sub_bagian'],
            'alamat'        => $validated['alamat'],
            'role'          => $validated['role'],
        ]);

        return redirect()
            ->route('index.akun')
            ->with('success', 'Data akun berhasil diperbarui.');
    }

    /**
     * "Hapus" = arsipkan (status -> nonaktif). ID dikirim via hidden input.
     */
    public function destroy(Request $request)
    {
        $request->validate(['id_user' => ['required', 'exists:users,id_user']]);

        $user = User::findOrFail($request->id_user);
        $user->status = 'nonaktif';
        $user->save();

        return redirect()
            ->route('index.akun')
            ->with('success', 'Akun berhasil diarsipkan.');
    }

    /**
     * Pulihkan dari arsip. ID dikirim via hidden input.
     */
    public function pulihkan(Request $request)
    {
        $request->validate(['id_user' => ['required', 'exists:users,id_user']]);

        $user = User::findOrFail($request->id_user);
        $user->status = 'aktif';
        $user->save();

        return redirect()
            ->route('akun.arsip')
            ->with('success', 'Akun berhasil dipulihkan.');
    }
}