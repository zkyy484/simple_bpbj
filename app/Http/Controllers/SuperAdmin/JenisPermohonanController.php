<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\JenisPermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisPermohonanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $admins = Auth::user();

        $jenisPermohonans = JenisPermohonan::where('status', 'aktif') // Filter hanya data aktif
            ->when($search, function ($query) use ($search) {
                $query->where('nama_jenis_permohonan', 'like', "%{$search}%");
            })
            ->latest('id_jenis_permohonan')
            ->paginate(5)
            ->withQueryString();

        return view('super-admin.jenis_permohonan.index', compact(
            'jenisPermohonans',
            'search',
            'admins'
        ));
    }

    public function arsip(Request $request)
    {
        $search = $request->search;
        $admins = Auth::user();

        $jenisPermohonans = JenisPermohonan::where('status', 'nonaktif') // Filter hanya data arsip
            ->when($search, function ($query) use ($search) {
                $query->where('nama_jenis_permohonan', 'like', "%{$search}%");
            })
            ->latest('id_jenis_permohonan')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.jenis_permohonan.arsip', compact(
            'jenisPermohonans',
            'search',
            'admins'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis_permohonan' => 'required|max:50|unique:jenis_permohonans,nama_jenis_permohonan',
        ]);

        $jenisPermohonan = JenisPermohonan::create([
            'nama_jenis_permohonan' => $request->nama_jenis_permohonan,
            'status' => 'aktif',
        ]);

        ActivityLog::catat(
            'Tambah Jenis Permohonan',
            "Menambahkan jenis permohonan \"{$jenisPermohonan->nama_jenis_permohonan}\"."
        );

        return back()->with('success', 'Jenis Permohonan berhasil ditambahkan');
    }

    public function update(Request $request)
    {
        // Cari model berdasarkan ID yang dikirim dari input hidden
        $jenisPermohonan = JenisPermohonan::findOrFail($request->id_jenis_permohonan);

        $request->validate([
            'nama_jenis_permohonan' => 'required|max:50|unique:jenis_permohonans,nama_jenis_permohonan,' . $jenisPermohonan->id_jenis_permohonan . ',id_jenis_permohonan',
        ]);

        $namaLama = $jenisPermohonan->nama_jenis_permohonan;

        $jenisPermohonan->update([
            'nama_jenis_permohonan' => $request->nama_jenis_permohonan,
        ]);

        ActivityLog::catat(
            'Ubah Jenis Permohonan',
            "Mengubah nama jenis permohonan dari \"{$namaLama}\" menjadi \"{$jenisPermohonan->nama_jenis_permohonan}\"."
        );

        return back()->with('success', 'Data berhasil diperbarui');
    }

    public function softdelete(Request $request)
    {
        // Validasi agar ID wajib ada
        $request->validate([
            'id_jenis_permohonan' => 'required'
        ]);

        // Cari data berdasarkan ID yang dikirim dari input hidden
        $jenisPermohonan = JenisPermohonan::where('id_jenis_permohonan', $request->id_jenis_permohonan)->firstOrFail();

        // Ubah status menjadi nonaktif (soft delete)
        $jenisPermohonan->update([
            'status' => 'nonaktif'
        ]);

        ActivityLog::catat(
            'Arsipkan Jenis Permohonan',
            "Mengarsipkan jenis permohonan \"{$jenisPermohonan->nama_jenis_permohonan}\"."
        );

        return back()->with('success', 'Jenis Permohonan berhasil dihapus');
    }

    public function pulihkan(Request $request)
    {
        $request->validate([
            'id_jenis_permohonan' => 'required'
        ]);

        // Cari data berdasarkan ID yang dikirim dari input hidden
        $jenisPermohonan = JenisPermohonan::where('id_jenis_permohonan', $request->id_jenis_permohonan)->firstOrFail();

        // Ubah status menjadi aktif kembali
        $jenisPermohonan->update([
            'status' => 'aktif'
        ]);

        ActivityLog::catat(
            'Pulihkan Jenis Permohonan',
            "Memulihkan jenis permohonan \"{$jenisPermohonan->nama_jenis_permohonan}\" dari arsip."
        );

        return back()->with('success', 'Jenis Permohonan berhasil dipulihkan');
    }
}