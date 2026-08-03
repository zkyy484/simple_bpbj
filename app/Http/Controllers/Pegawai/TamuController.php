<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TindakLanjutMail;
use Illuminate\Support\Facades\Auth;

class TamuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $pegawai = Auth::user();

        $tamus = Tamu::with(['subBagian', 'tujuan', 'pegawai'])
            ->where('status_aktif', 'aktif')
            ->where('approval', 'approve')
            ->where('id_sub_bagian', $pegawai->id_sub_bagian)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('kode_tiket', 'like', "%{$search}%");
                });
            })
            ->latest('id_tamu')
            ->paginate(10)
            ->withQueryString();

        return view('pegawai.tamu.index', compact('tamus', 'search', 'pegawai'));
    }

    public function updateTindakLanjut(Request $request, int $id)
    {
        $tamu = Tamu::findOrFail($id);

        // Jika belum ada yang menangani, pegawai yang login menjadi penanggung jawab
        if (is_null($tamu->id_user)) {
            $tamu->id_user = Auth::user()->id_user;
        }
        // Jika sudah ditangani pegawai lain
        elseif ($tamu->id_user != Auth::user()->id_user) {
            return redirect()
                ->route('pegawai.tamu.index')
                ->with('error', 'Data sudah ditangani oleh pegawai lain.');
        }

        $tamu->solusi = $request->solusi;
        $tamu->status_tindak_lanjut = $request->status_tindak_lanjut;
        $tamu->save();

        return redirect()
            ->route('pegawai.tamu.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function kirimEmail(Request $request, int $id)
    {
        $tamu = Tamu::findOrFail($id);

        // Jika belum ada yang menangani, pegawai yang login menjadi penanggung jawab
        if (is_null($tamu->id_user)) {
            $tamu->id_user = Auth::user()->id_user;
            $tamu->save();
        }
        // Jika sudah ditangani pegawai lain
        elseif ($tamu->id_user != Auth::user()->id_user) {
            return redirect()
                ->back()
                ->with('error', 'Data sudah ditangani oleh pegawai lain.');
        }

        // ===== proses kirim email =====
        $tamu->load(['pegawai', 'tujuan', 'subBagian']);

        Mail::to($tamu->email)->send(new TindakLanjutMail($tamu));

        return redirect()
            ->back()
            ->with('success', 'Email berhasil dikirim.');
    }
}