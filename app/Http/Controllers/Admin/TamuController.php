<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ApprovalTamuMail;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Models\SubBagian;
use App\Models\Tamu;
use App\Models\Tujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

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
                        ->orWhereHas('subBagian', fn($sub) => $sub->where('nama_sub_bagian', 'like', "%{$search}%"))
                        ->orWhereHas('tujuan', fn($tj) => $tj->where('nama_tujuan', 'like', "%{$search}%"));
                });
            })
            ->latest('id_tamu')
            ->paginate(10)
            ->appends($request->except('ajax'));

        // Daftar Sub Bagian aktif untuk dropdown edit "Sub Bagian" pada modal Detail.
        $subBagianList = SubBagian::where('status', 'aktif')
            ->orderBy('nama_sub_bagian')
            ->get();

        // Daftar Tujuan aktif untuk dropdown edit "Tujuan" pada modal Detail.
        $tujuanList = Tujuan::where('status', 'aktif')
            ->orderBy('nama_tujuan')
            ->get();

        if ($request->ajax()) {
            return view('admin.tamu.partials.tabel-tamu', compact('tamus'));
        }

        return view('admin.tamu.index', compact('tamus', 'search', 'admins', 'subBagianList', 'tujuanList'));
    }

    // Memperbarui data tamu. Khusus role Admin FO, data yang boleh diubah
    // adalah Sub Bagian dan Tujuan. Solusi dan Status Tindak Lanjut TIDAK
    // ikut divalidasi/disimpan di sini agar tidak bisa diubah dari sisi Admin FO.
    public function update(Request $request, Tamu $tamu)
    {
        $validated = $request->validate([
            'id_sub_bagian' => ['nullable', 'exists:sub_bagians,id_sub_bagian'],
            'id_tujuan' => ['nullable', 'exists:tujuans,id_tujuan'],
        ]);

        $tamu->update([
            'id_sub_bagian' => $validated['id_sub_bagian'] ?? $tamu->id_sub_bagian,
            'id_tujuan' => $validated['id_tujuan'] ?? $tamu->id_tujuan,
        ]);

        ActivityLog::catat(
            'Ubah Data Tamu',
            "Memperbarui Sub Bagian/Tujuan tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket})."
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

        // Kirim email jika status disetujui dan email tamu tersedia
        if ($approvalBaru === 'approve' && !empty($tamu->email)) {
            $tamu->load(['subBagian', 'tujuan']);

            try {
                Mail::to($tamu->email)->send(new ApprovalTamuMail($tamu));
            } catch (\Exception $e) {
                // Tetap catat log jika gagal kirim email agar proses approval tidak terhenti
                logger('Gagal mengirim email approval: ' . $e->getMessage());
            }
        }

        ActivityLog::catat(
            $approvalBaru === 'approve' ? 'Approve Tamu' : 'Batalkan Approval Tamu',
            "Mengubah status approval tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket}) menjadi {$approvalBaru}."
        );

        return back()->with('success', 'Status approval berhasil diperbarui dan email telah dikirim.');
    }

}