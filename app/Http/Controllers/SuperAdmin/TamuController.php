<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Models\Respon;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TamuController extends Controller
{
    // Menampilkan daftar tamu aktif
    public function index(Request $request)
    {
        $search = $request->search;
        $admins = Auth::user();

        $tamus = Tamu::with(['subBagian', 'tujuan', 'pegawai'])
            ->where('status_aktif', 'aktif')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('kode_tiket', 'like', "%{$search}%")
                        ->orWhereHas('subBagian', fn ($sub) => $sub->where('nama_sub_bagian', 'like', "%{$search}%"))
                        ->orWhereHas('tujuan', fn ($tj) => $tj->where('nama_tujuan', 'like', "%{$search}%"));
                });
            })
            ->latest('id_tamu')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.tamu.index', compact('tamus', 'search', 'admins'));
    }

    // Menampilkan daftar tamu non-aktif (arsip)
    public function arsip(Request $request)
    {
        $search = $request->search;
        $admins = Auth::user();

        $tamus = Tamu::with(['subBagian', 'tujuan', 'pegawai'])
            ->where('status_aktif', 'non_aktif')
            ->when($search, function ($query) use ($search) {
                $query->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('kode_tiket', 'like', "%{$search}%");
            })
            ->latest('id_tamu')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.tamu.arsip', compact('tamus', 'search', 'admins'));
    }

    // Memulihkan data tamu dari arsip
    public function pulihkan(Tamu $tamu)
    {
        $tamu->update(['status_aktif' => 'aktif']);

        ActivityLog::catat(
            'Pulihkan Data Tamu',
            "Memulihkan data tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket}) dari arsip."
        );

        return back()->with('success', 'Data tamu berhasil dipulihkan.');
    }

    // Memperbarui solusi, status tindak lanjut, status, dan pegawai penanggung jawab
    public function update(Request $request, Tamu $tamu)
    {
        $validated = $request->validate([
            'solusi' => ['nullable', 'string'],
            'status_tindak_lanjut' => ['required', 'in:belum_eskalasi,eskalasi,selesai'],
            'status' => ['nullable', 'in:menunggu,diproses,selesai'],
            'id_user' => ['nullable', 'exists:users,id_user'],
        ]);

        $tamu->update([
            'solusi' => $validated['solusi'] ?? $tamu->solusi,
            'status_tindak_lanjut' => $validated['status_tindak_lanjut'],
            'id_user' => $validated['id_user'] ?? $tamu->id_user,
        ]);

        ActivityLog::catat(
            'Ubah Data Tamu',
            "Memperbarui data tindak lanjut tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket})."
        );

        return back()->with('success', 'Data tamu berhasil diperbarui.');
    }

    // Mengubah status approval + upload paraf admin/pegawai
    public function approval(Request $request, Tamu $tamu)
    {
        $approvalBaru = $tamu->approval === 'approve' ? 'menunggu' : 'approve';

        $data = [
            'approval' => $approvalBaru,
        ];

        if ($request->hasFile('paraf')) {
            if ($tamu->paraf) {
                Storage::disk('public')->delete($tamu->paraf);
            }
            $data['paraf'] = $request->file('paraf')->store('paraf', 'public');
        }

        $tamu->update($data);

        ActivityLog::catat(
            $approvalBaru === 'approve' ? 'Approve Tamu' : 'Batalkan Approval Tamu',
            "Mengubah status approval tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket}) menjadi {$approvalBaru}."
        );

        return back()->with('success', 'Status approval berhasil diperbarui.');
    }

    // Ceklis validasi survei tamu.
    // Dicentang: jika respon survei tamu belum ada, dibuat otomatis (nama, email,
    // instansi, tanggal respon; sisanya null). Jika respon sudah ada (kosong
    // maupun sudah diisi tamu), data respon tidak diubah.
    public function cek(Tamu $tamu)
    {
        $cekBaru = !$tamu->cek_survei;

        $tamu->update(['cek_survei' => $cekBaru]);

        if (!$cekBaru) {
            ActivityLog::catat(
                'Batalkan Ceklis Survei Tamu',
                "Membatalkan ceklis survei tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket})."
            );

            return back()->with('success', 'Ceklis survei dibatalkan.');
        }

        $dibuat = Respon::buatKosongDariTamu($tamu);

        ActivityLog::catat(
            'Ceklis Survei Tamu',
            "Mencentang validasi survei tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket})."
            . ($dibuat ? ' Data respon survei dibuat otomatis.' : '')
        );

        return back()->with('success', $dibuat
            ? 'Ceklis disimpan. Data survei tamu dibuat otomatis karena belum diisi.'
            : 'Ceklis disimpan. Survei tamu sudah ada, data tidak diubah.');
    }

    public function destroy(Tamu $tamu)
    {
        $tamu->update(['status_aktif' => 'non_aktif']);

        ActivityLog::catat(
            'Arsipkan Data Tamu',
            "Mengarsipkan data tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket})."
        );

        return back()->with('success', 'Data tamu berhasil dipindahkan ke arsip.');
    }
}