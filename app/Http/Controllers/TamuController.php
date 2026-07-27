<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\SubBagian;
use App\Models\Tujuan;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    // Menampilkan form kunjungan tamu
    public function FormPage()
    {
        $subBagians = SubBagian::orderBy('nama_sub_bagian')->get();
        $tujuans = Tujuan::orderBy('nama_tujuan')->get();

        return view('tamu.form', compact('subBagians', 'tujuans'));
    }

    // Menyimpan data tamu dari form kunjungan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik_nip' => ['nullable', 'string', 'max:30'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'jenis_permohonan' => ['nullable', 'string', 'max:50'],
            'nama_perusahaan' => ['nullable', 'string', 'max:100'],
            'id_sub_bagian' => ['required', 'exists:sub_bagians,id_sub_bagian'],
            'id_tujuan' => ['required', 'exists:tujuans,id_tujuan'],
            'permasalahan' => ['nullable', 'string'],
        ]);

        $validated['kode_tiket'] = 'KNS-' . now()->format('Ymd') . str_pad(
            Tamu::whereDate('created_at', now())->count() + 1,
            5,
            '0',
            STR_PAD_LEFT
        );

        $validated['status_tindak_lanjut'] = 'belum_eskalasi';
        $validated['status'] = 'menunggu';
        $validated['approval'] = 'menunggu';
        $validated['status_aktif'] = 'aktif';

        $tamu = Tamu::create($validated);

        return redirect()->route('thanks.page', $tamu->id_tamu);
    }

    // Menampilkan halaman terima kasih setelah data tersimpan
    public function Thanks($id)
    {
        $tamu = Tamu::with('tujuan')->findOrFail($id);

        return view('tamu.thanks-survei', compact('tamu'));
    }
}