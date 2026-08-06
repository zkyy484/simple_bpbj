<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Tujuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TujuanController extends Controller
{
    public function index(Request $request)
    {
        // return view('super-admin.tujuan.index');
        $search = $request->search;
        $admins = Auth::user();

        $Tujuans = Tujuan::where('status', 'aktif') // Filter hanya data aktif
            ->when($search, function ($query) use ($search) {
                $query->where('nama_tujuan', 'like', "%{$search}%");
            })
            ->latest('id_tujuan')
            ->paginate(5)
            ->withQueryString();

        return view('super-admin.tujuan.index', compact(
            'search',
            'admins',
            'Tujuans',
            'search'
        ));
    }

    public function arsip(Request $request)
    {
        // return view('super-admin.tujuan.index');
        $search = $request->search;
        $admins = Auth::user();

        $Tujuans = Tujuan::where('status', 'nonaktif') // Filter hanya data aktif
            ->when($search, function ($query) use ($search) {
                $query->where('nama_tujuan', 'like', "%{$search}%");
            })
            ->latest('id_tujuan')
            ->paginate(5)
            ->withQueryString();

        return view('super-admin.tujuan.arsip', compact(
            'Tujuans',
            'search',
            'admins'
        ));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_tujuan' => 'required|max:50|unique:tujuans,nama_tujuan',
        ]);

        Tujuan::create([
            'nama_tujuan' => $request->nama_tujuan,
            'status' => 'aktif',
        ]);

        ActivityLog::catat(
            'Tambah Tujuan',
            "Menambahkan tujuan kunjungan \"{$request->nama_tujuan}\"."
        );

        return back()->with('success', 'Tujuan berhasil ditambahkan');
    }

    public function update(Request $request) {
        // Cari model berdasarkan ID yang dikirim dari input hidden
        $Tujuans = Tujuan::findOrFail($request->id_tujuan);

        $request->validate([
            'nama_tujuan' => 'required|max:50|unique:tujuans,nama_tujuan,' . $Tujuans->id_tujuan. ',id_tujuan',
        ]);

        $Tujuans->update([
            'nama_tujuan' => $request->nama_tujuan,
        ]);

        ActivityLog::catat(
            'Ubah Tujuan',
            "Memperbarui tujuan kunjungan menjadi \"{$request->nama_tujuan}\"."
        );

        return back()->with('success', 'Data berhasil diperbarui');
    }

    public function softdelete(Request $request)
    {
        // Validasi agar ID wajib ada
        $request->validate([
            'id_tujuan' => 'required'
        ]);

        // Cari data berdasarkan ID yang dikirim dari input hidden
        $Tujuans = Tujuan::where('id_tujuan', $request->id_tujuan)->firstOrFail();

        // Ubah status menjadi nonaktif (soft delete)
        $Tujuans->update([
            'status' => 'nonaktif'
        ]);

        ActivityLog::catat(
            'Arsipkan Tujuan',
            "Mengarsipkan tujuan kunjungan \"{$Tujuans->nama_tujuan}\"."
        );

        return back()->with('success', 'Tujuan berhasil dihapus');
    }

    public function pulihkan(Request $request)
    {
        // Validasi agar ID wajib ada
        $request->validate([
            'id_tujuan' => 'required'
        ]);

        // Cari data berdasarkan ID yang dikirim dari input hidden
        $Tujuans = Tujuan::where('id_tujuan', $request->id_tujuan)->firstOrFail();

        // Ubah status menjadi nonaktif (soft delete)
        $Tujuans->update([
            'status' => 'aktif'
        ]);

        ActivityLog::catat(
            'Pulihkan Tujuan',
            "Memulihkan tujuan kunjungan \"{$Tujuans->nama_tujuan}\" dari arsip."
        );

        return back()->with('success', 'Tujuan berhasil dipulihkan');
    }
}
