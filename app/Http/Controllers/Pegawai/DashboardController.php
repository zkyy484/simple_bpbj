<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\SubBagian;
use App\Models\Tamu;
use App\Models\Respon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user();

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Pegawai hanya melihat data tamu yang sudah di-approve dan berada
        // di sub bagiannya sendiri, mengikuti scoping yang sama dengan
        // Pegawai\TamuController.
        $baseQuery = fn () => Tamu::where('status_aktif', 'aktif')
            ->where('approval', 'approve')
            ->where('id_sub_bagian', $pegawai->id_sub_bagian);

        // ===== KPI 1: Total Kunjungan Tamu (aktif) + perbandingan bulan ini vs bulan lalu =====
        $totalKunjungan = $baseQuery()->count();

        $kunjunganBulanIni = $baseQuery()
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        $kunjunganBulanLalu = $baseQuery()
            ->whereMonth('created_at', $today->copy()->subMonth()->month)
            ->whereYear('created_at', $today->copy()->subMonth()->year)
            ->count();

        $persenBulanan = $this->hitungPersentase($kunjunganBulanIni, $kunjunganBulanLalu);

        // ===== KPI 2: Kunjungan Hari Ini vs Kemarin =====
        $kunjunganHariIni = $baseQuery()->whereDate('created_at', $today)->count();
        $kunjunganKemarin = $baseQuery()->whereDate('created_at', $yesterday)->count();

        $persenHarian = $this->hitungPersentase($kunjunganHariIni, $kunjunganKemarin);

        // ===== KPI 3: Total Survei Masuk bulan ini (skala organisasi) =====
        $totalSurvei = Respon::whereMonth('tanggal_respon', $today->month)
            ->whereYear('tanggal_respon', $today->year)
            ->count();

        // ===== Distribusi Kunjungan per Sub Bagian (khusus sub bagian pegawai) =====
        $distribusiSubBagian = SubBagian::withCount(['tamus' => function ($q) {
                $q->where('status_aktif', 'aktif')->where('approval', 'approve');
            }])
            ->orderByDesc('tamus_count')
            ->get();

        $totalDistribusi = max($distribusiSubBagian->sum('tamus_count'), 1);

        // ===== Aktivitas Kunjungan 7 hari terakhir (untuk line chart) =====
        $aktivitasMingguan = collect(range(6, 0))->map(function ($i) use ($today, $baseQuery) {
            $tanggal = $today->copy()->subDays($i);

            return [
                'label' => $tanggal->translatedFormat('d M'),
                'total' => $baseQuery()->whereDate('created_at', $tanggal)->count(),
            ];
        });

        // ===== Log Kunjungan Terbaru di sub bagian pegawai =====
        $kunjunganTerbaru = $baseQuery()
            ->with(['subBagian', 'tujuan'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('pegawai.dashboard', compact(
            'pegawai',
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