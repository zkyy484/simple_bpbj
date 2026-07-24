<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubBagian;
use Illuminate\Http\Request;

class SubBagianController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $subBagians = SubBagian::where('status', 'aktif') // Filter hanya data aktif
            ->when($search, function ($query) use ($search) {
                $query->where('nama_sub_bagian', 'like', "%{$search}%");
            })
            ->latest('id_sub_bagian')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.sub_bagian.index', compact(
            'subBagians',
            'search'
        ));
    }

    public function arsip(Request $request)
    {
        $search = $request->search;

        $subBagians = SubBagian::where('status', 'nonaktif') // Filter hanya data aktif
            ->when($search, function ($query) use ($search) {
                $query->where('nama_sub_bagian', 'like', "%{$search}%");
            })
            ->latest('id_sub_bagian')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.sub_bagian.arsip', compact(
            'subBagians',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sub_bagian' => 'required|max:50|unique:sub_bagians,nama_sub_bagian',
        ]);

        SubBagian::create([
            'nama_sub_bagian' => $request->nama_sub_bagian,
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Sub Bagian berhasil ditambahkan');

    }

    public function update(Request $request)
    {
        // Cari model berdasarkan ID yang dikirim dari input hidden
        $subBagian = SubBagian::findOrFail($request->id_sub_bagian);

        $request->validate([
            'nama_sub_bagian' => 'required|max:50|unique:sub_bagians,nama_sub_bagian,' . $subBagian->id_sub_bagian . ',id_sub_bagian',
        ]);

        $subBagian->update([
            'nama_sub_bagian' => $request->nama_sub_bagian,
        ]);

        return back()->with('success', 'Data berhasil diperbarui');
    }

    public function softdelete(Request $request)
    {
        // Validasi agar ID wajib ada
        $request->validate([
            'id_sub_bagian' => 'required'
        ]);

        // Cari data berdasarkan ID yang dikirim dari input hidden
        $subBagian = SubBagian::where('id_sub_bagian', $request->id_sub_bagian)->firstOrFail();

        // Ubah status menjadi nonaktif (soft delete)
        $subBagian->update([
            'status' => 'nonaktif'
        ]);

        return back()->with('success', 'Sub Bagian berhasil dihapus');
    }

    public function pulihkan(Request $request) {
        $request->validate([
            'id_sub_bagian' => 'required'
        ]);

        // Cari data berdasarkan ID yang dikirim dari input hidden
        $subBagian = SubBagian::where('id_sub_bagian', $request->id_sub_bagian)->firstOrFail();

        // Ubah status menjadi nonaktif (soft delete)
        $subBagian->update([
            'status' => 'aktif'
        ]);

        return back()->with('success', 'Sub Bagian berhasil dipulihkan');
    }
}
