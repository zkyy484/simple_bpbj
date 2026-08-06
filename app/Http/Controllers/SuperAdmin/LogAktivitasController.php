<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAktivitasController extends Controller
{
    /**
     * Menampilkan daftar log aktivitas seluruh role (super_admin, admin, pegawai).
     */
    public function index(Request $request)
    {
        $admins = Auth::user();

        $search = $request->query('search');
        $role = $request->query('role');
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $logs = ActivityLog::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_user', 'like', "%{$search}%")
                        ->orWhere('aktivitas', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->when($role, fn ($query) => $query->where('role', $role))
            ->when($tanggalMulai, fn ($query) => $query->whereDate('created_at', '>=', $tanggalMulai))
            ->when($tanggalSelesai, fn ($query) => $query->whereDate('created_at', '<=', $tanggalSelesai))
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        // Ringkasan kecil: jumlah aktivitas hari ini per role
        $ringkasanHariIni = ActivityLog::whereDate('created_at', today())
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('super-admin.log-aktivitas.index', compact(
            'logs',
            'admins',
            'search',
            'role',
            'tanggalMulai',
            'tanggalSelesai',
            'ringkasanHariIni'
        ));
    }
}