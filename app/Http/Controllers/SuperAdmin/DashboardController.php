<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\SubBagian;
use App\Models\Tamu;
use App\Models\Respon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $admins = Auth::guard('web')->user();

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // ===== KPI 1: Total Kunjungan Tamu (aktif) + perbandingan bulan ini vs bulan lalu =====
        $totalKunjungan = Tamu::where('status_aktif', 'aktif')->count();

        $kunjunganBulanIni = Tamu::where('status_aktif', 'aktif')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        $kunjunganBulanLalu = Tamu::where('status_aktif', 'aktif')
            ->whereMonth('created_at', $today->copy()->subMonth()->month)
            ->whereYear('created_at', $today->copy()->subMonth()->year)
            ->count();

        $persenBulanan = $this->hitungPersentase($kunjunganBulanIni, $kunjunganBulanLalu);

        // ===== KPI 2: Kunjungan Hari Ini vs Kemarin =====
        $kunjunganHariIni = Tamu::where('status_aktif', 'aktif')
            ->whereDate('created_at', $today)
            ->count();

        $kunjunganKemarin = Tamu::where('status_aktif', 'aktif')
            ->whereDate('created_at', $yesterday)
            ->count();

        $persenHarian = $this->hitungPersentase($kunjunganHariIni, $kunjunganKemarin);

        // ===== KPI 3: Total Survei Masuk bulan ini =====
        $totalSurvei = Respon::whereMonth('tanggal_respon', $today->month)
            ->whereYear('tanggal_respon', $today->year)
            ->count();

        // ===== Distribusi Kunjungan per Sub Bagian =====
        $distribusiSubBagian = SubBagian::withCount(['tamus' => function ($q) {
                $q->where('status_aktif', 'aktif');
            }])
            ->orderByDesc('tamus_count')
            ->get();

        $totalDistribusi = max($distribusiSubBagian->sum('tamus_count'), 1);

        // ===== Aktivitas Kunjungan 7 hari terakhir (untuk line chart) =====
        $aktivitasMingguan = collect(range(6, 0))->map(function ($i) use ($today) {
            $tanggal = $today->copy()->subDays($i);

            return [
                'label' => $tanggal->translatedFormat('d M'),
                'total' => Tamu::where('status_aktif', 'aktif')
                    ->whereDate('created_at', $tanggal)
                    ->count(),
            ];
        });

        // ===== Log Aktivitas / Kunjungan Terbaru =====
        $kunjunganTerbaru = Tamu::with(['subBagian', 'tujuan'])
            ->where('status_aktif', 'aktif')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('super-admin.dashboard', compact(
            'admins',
            'totalKunjungan',
            'persenBulanan',
            'kunjunganHariIni',
            'persenHarian',
            'totalSurvei',
            'distribusiSubBagian',
            'totalDistribusi',
            'aktivitasMingguan',
            'kunjunganTerbaru'
        ));
    }

    /**
     * Menghitung persentase perubahan dari nilai lama ke nilai baru.
     */
    private function hitungPersentase(int $baru, int $lama): float
    {
        if ($lama <= 0) {
            return $baru > 0 ? 100.0 : 0.0;
        }

        return round((($baru - $lama) / $lama) * 100, 1);
    }
}