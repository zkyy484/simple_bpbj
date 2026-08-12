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
    public function index(Request $request)
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
        // Hanya ambil sub bagian yang statusnya 'aktif' (kolom `status` di tabel sub_bagians)
        // DAN benar-benar punya kunjungan (tamus_count > 0), supaya kartu distribusi
        // tidak menampilkan sub bagian nonaktif atau yang kosong / tidak relevan.
        $distribusiSubBagian = SubBagian::where('status', 'aktif')
            ->withCount(['tamus' => function ($q) {
                $q->where('status_aktif', 'aktif');
            }])
            ->having('tamus_count', '>', 0)
            ->orderByDesc('tamus_count')
            ->get();

        $totalDistribusi = max($distribusiSubBagian->sum('tamus_count'), 1);

        // Hitung persentase kontribusi tiap sub bagian terhadap total kunjungan
        // yang tercakup dalam distribusi (dipakai di view, bukan sekadar dihitung lalu dibuang).
        $distribusiSubBagian->each(function ($sub) use ($totalDistribusi) {
            $sub->persentase = round(($sub->tamus_count / $totalDistribusi) * 100, 1);
        });

        // ===== Aktivitas Kunjungan (untuk line chart) =====
        // Rentang tanggal bisa diatur lewat query string ?tanggal_awal=...&tanggal_akhir=...
        // Default: 7 hari terakhir, jika parameter tidak dikirim / tidak valid.
        try {
            $tanggalAwal = $request->filled('tanggal_awal')
                ? Carbon::parse($request->tanggal_awal)->startOfDay()
                : $today->copy()->subDays(6);

            $tanggalAkhir = $request->filled('tanggal_akhir')
                ? Carbon::parse($request->tanggal_akhir)->startOfDay()
                : $today->copy();
        } catch (\Exception $e) {
            $tanggalAwal = $today->copy()->subDays(6);
            $tanggalAkhir = $today->copy();
        }

        // Tukar otomatis jika tanggal awal lebih baru dari tanggal akhir
        if ($tanggalAwal->gt($tanggalAkhir)) {
            [$tanggalAwal, $tanggalAkhir] = [$tanggalAkhir->copy(), $tanggalAwal->copy()];
        }

        // Tanggal akhir tidak boleh melebihi hari ini
        if ($tanggalAkhir->gt($today)) {
            $tanggalAkhir = $today->copy();
        }

        // Batasi rentang maksimal 90 hari supaya query & chart tidak terlalu berat
        if ($tanggalAwal->diffInDays($tanggalAkhir) > 90) {
            $tanggalAwal = $tanggalAkhir->copy()->subDays(90);
        }

        $jumlahHari = $tanggalAwal->diffInDays($tanggalAkhir);

        $aktivitasMingguan = collect(range(0, $jumlahHari))->map(function ($i) use ($tanggalAwal) {
            $tanggal = $tanggalAwal->copy()->addDays($i);

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
            'kunjunganTerbaru',
            'tanggalAwal',
            'tanggalAkhir'
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