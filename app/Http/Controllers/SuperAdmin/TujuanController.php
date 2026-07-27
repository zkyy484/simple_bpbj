<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubBagian;
use Illuminate\Http\Request;

class TujuanController extends Controller
{
    public function index(Request $request)
    {
        // return view('super-admin.tujuan.index');
        $search = $request->search;
        $admins = auth()->user();

        $subBagians = SubBagian::where('status', 'aktif') // Filter hanya data aktif
            ->when($search, function ($query) use ($search) {
                $query->where('nama_sub_bagian', 'like', "%{$search}%");
            })
            ->latest('id_sub_bagian')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.tujuan.index', compact(
            'subBagians',
            'search',
            'admins'
        ));
    }
}
