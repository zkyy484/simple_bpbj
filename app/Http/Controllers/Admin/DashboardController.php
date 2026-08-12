<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SubBagian;
use App\Models\Tamu;
use App\Models\Respon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $admins = auth()->guard('web')->user();

        // ============================================================
        // KPI 1: Total Kunjungan Tamu (all time) + growth vs bulan lalu
        // ============================================================
        $totalKunjungan = Tamu::count();

        $awalBulanIni = now()->startOfMonth();
        $awalBulanLalu = now()->subMonthNoOverflow()->startOfMonth();
        $akhirBulanLalu = now()->subMonthNoOverflow()->endOfMonth();

        $kunjunganBulanIni = Tamu::whereBetween('created_at', [$awalBulanIni, now()])->count();
        $kunjunganBulanLalu = Tamu::whereBetween('created_at', [$awalBulanLalu, $akhirBulanLalu])->count();

        $persenBulan = $kunjunganBulanLalu > 0
            ? round((($kunjunganBulanIni - $kunjunganBulanLalu) / $kunjunganBulanLalu) * 100, 1)
            : ($kunjunganBulanIni > 0 ? 100 : 0);

        // ============================================================
        // KPI 2: Kunjungan Hari Ini + growth vs kemarin
        // ============================================================
        $kunjunganHariIni = Tamu::whereDate('created_at', today())->count();
        $kunjunganKemarin = Tamu::whereDate('created_at', today()->subDay())->count();

        $persenHari = $kunjunganKemarin > 0
            ? round((($kunjunganHariIni - $kunjunganKemarin) / $kunjunganKemarin) * 100, 1)
            : ($kunjunganHariIni > 0 ? 100 : 0);

        // ============================================================
        // KPI 3: Total Survei Masuk (bulan berjalan, sesuai label di UI)
        // ============================================================
        $totalSurvei = Respon::whereBetween('created_at', [$awalBulanIni, now()])->count();

        // ============================================================
        // Distribusi Kunjungan per Sub Bagian (dinamis, bukan hardcode)
        // ============================================================
        $distribusiSubBagian = SubBagian::withCount('tamus')
            ->orderByDesc('tamus_count')
            ->get();

        $totalDistribusi = $distribusiSubBagian->sum('tamus_count');

        // Palet warna dipakai bergantian untuk kartu, legend, & doughnut chart
        $warnaSubBagian = ['#173860', '#38bdf8', '#818cf8', '#0ea5e9', '#6366f1', '#60a5fa'];

        // ============================================================
        // Filter Mingguan (Senin - Jumat) untuk Aktivitas Kunjungan
        // ?minggu=0  -> minggu ini (default)
        // ?minggu=-1 -> minggu lalu, dst
        // ============================================================
        $minggu = (int) $request->get('minggu', 0);

        $awalMinggu = now()->startOfWeek(Carbon::MONDAY)->addWeeks($minggu);
        $akhirMinggu = (clone $awalMinggu)->addDays(4)->endOfDay(); // sampai Jumat

        $labelHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $dataAktivitas = [];

        foreach (range(0, 4) as $i) {
            $tanggal = (clone $awalMinggu)->addDays($i);
            $dataAktivitas[] = Tamu::whereDate('created_at', $tanggal)->count();
        }

        // Opsi dropdown: 8 minggu terakhir (termasuk minggu ini)
        $opsiMinggu = [];
        foreach (range(0, -7) as $w) {
            $s = now()->startOfWeek(Carbon::MONDAY)->addWeeks($w);
            $e = (clone $s)->addDays(4);
            $opsiMinggu[] = [
                'value' => $w,
                'label' => $s->translatedFormat('d M') . ' - ' . $e->translatedFormat('d M Y'),
            ];
        }

        // ============================================================
        // Log Aktivitas Terbaru (diambil dari data tamu terbaru)
        // ============================================================
        $recentTamu = Tamu::with(['tujuan', 'subBagian'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'admins',
            'totalKunjungan',
            'persenBulan',
            'kunjunganHariIni',
            'persenHari',
            'totalSurvei',
            'distribusiSubBagian',
            'totalDistribusi',
            'warnaSubBagian',
            'labelHari',
            'dataAktivitas',
            'opsiMinggu',
            'minggu',
            'awalMinggu',
            'akhirMinggu',
            'recentTamu'
        ));
    }
}