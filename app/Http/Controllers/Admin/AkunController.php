<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubBagian;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $admins = Auth::user();

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

        return view('admin.akun.index', compact('accounts', 'subBagians', 'admins'));
    }
}
