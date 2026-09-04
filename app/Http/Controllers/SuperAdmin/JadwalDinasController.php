<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JadwalDinas;
use App\Models\Respon;
use App\Models\Tamu;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\JadwalDinasExport;
use Maatwebsite\Excel\Facades\Excel;

class JadwalDinasController extends Controller
{
    // List Jadwal Dinas (Admin/Pegawai)
    public function index(Request $request)
    {
        $admins = Auth::user();
        $query = JadwalDinas::with(['pegawais.subBagian'])
            ->orderBy('hari_tanggal', 'desc')
            ->orderBy('waktu', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('acara', 'like', "%{$search}%")
                    ->orWhere('surat_dari', 'like', "%{$search}%")
                    ->orWhere('bidang_sekretariat', 'like', "%{$search}%")
                    ->orWhere('tempat_zoom', 'like', "%{$search}%");
            });
        }

        $jadwalDinas = $query->paginate(10)->withQueryString();
        $pegawaiList = User::orderBy('nama_lengkap')->get();

        return view('super-admin.jadwal-dinas.index', compact('jadwalDinas', 'pegawaiList', 'admins'));
    }

    // Simpan Jadwal Dinas Baru
    public function store(Request $request)
    {
        $request->validate([
            'bidang_sekretariat' => 'nullable|string|max:150',
            'acara' => 'required|string',
            'surat_dari' => 'required|string|max:150',
            'hari_tanggal' => 'required|date',
            'waktu' => 'nullable',
            'tempat_zoom' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'pegawai_ids' => 'nullable|array',
            'pegawai_ids.*' => 'exists:users,id_user',
        ], [
            'acara.required' => 'Kolom Acara wajib diisi.',
            'surat_dari.required' => 'Kolom Surat Dari wajib diisi.',
            'hari_tanggal.required' => 'Kolom Hari/Tanggal wajib diisi.',
        ]);

        $jadwal = JadwalDinas::create([
            'bidang_sekretariat' => $request->bidang_sekretariat,
            'acara' => $request->acara,
            'surat_dari' => $request->surat_dari,
            'hari_tanggal' => $request->hari_tanggal,
            'waktu' => $request->waktu,
            'tempat_zoom' => $request->tempat_zoom,
            'keterangan' => $request->keterangan,
        ]);

        $jadwal->pegawais()->sync($request->input('pegawai_ids', []));

        ActivityLog::catat(
            'Tambah Jadwal Dinas',
            'Menambahkan jadwal dinas baru: ' . $jadwal->acara
        );

        return redirect()
            ->route('super.jadwal_dinas.index')
            ->with('success', 'Jadwal dinas berhasil ditambahkan.');
    }

    // Update Jadwal Dinas
    public function update(Request $request, int $id)
    {
        $jadwal = JadwalDinas::findOrFail($id);

        $request->validate([
            'bidang_sekretariat' => 'nullable|string|max:150',
            'acara' => 'required|string',
            'surat_dari' => 'required|string|max:150',
            'hari_tanggal' => 'required|date',
            'waktu' => 'nullable',
            'tempat_zoom' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'pegawai_ids' => 'nullable|array',
            'pegawai_ids.*' => 'exists:users,id_user',
        ]);

        $jadwal->update([
            'bidang_sekretariat' => $request->bidang_sekretariat,
            'acara' => $request->acara,
            'surat_dari' => $request->surat_dari,
            'hari_tanggal' => $request->hari_tanggal,
            'waktu' => $request->waktu,
            'tempat_zoom' => $request->tempat_zoom,
            'keterangan' => $request->keterangan,
        ]);

        $jadwal->pegawais()->sync($request->input('pegawai_ids', []));

        ActivityLog::catat(
            'Edit Jadwal Dinas',
            'Perbarui jadwal dinas: ' . $jadwal->acara
        );

        return redirect()
            ->route('super.jadwal_dinas.index')
            ->with('success', 'Jadwal dinas berhasil diperbarui.');
    }

    // Hapus Jadwal Dinas
    public function destroy(int $id)
    {
        $jadwal = JadwalDinas::findOrFail($id);
        $acara = $jadwal->acara;

        $jadwal->pegawais()->detach();
        $jadwal->delete();

        ActivityLog::catat(
            'Hapus Jadwal Dinas',
            'Menghapus jadwal dinas: ' . $acara
        );

        return redirect()
            ->route('super.jadwal_dinas.index')
            ->with('success', 'Jadwal dinas berhasil dihapus.');
    }

    // Tampilan Publik Khusus Layar Monitor TV (Full Day 00:01 - 23:59)
    public function displayTV()
    {
        $today = Carbon::today('Asia/Makassar');
        $now = Carbon::now('Asia/Makassar');

        // ==== Statistik Kunjungan & SKM ====
        $stats = $this->hitungStatistikDisplay($today);

        // ==== Jadwal Dinas Hari Ini ====
        $jadwalHariIni = JadwalDinas::with(['pegawais.subBagian'])
            ->whereDate('hari_tanggal', $today)
            ->orderBy('waktu')
            ->get();

        // ==== Daftar Link Video Display (YouTube) ====
        // Diambil dari Pengaturan > Display Online (bisa lebih dari 1 video, sudah terurut
        // sesuai urutan yang diatur admin), lalu dikonversi ke URL embed agar bisa ditampilkan
        // lewat <iframe> saat tidak ada jadwal dinas hari ini. Jika hanya 1 video, video di-loop
        // terus menerus. Jika lebih dari 1 video, TV Display akan memutar video secara bergantian
        // sesuai urutannya (lihat script di resources/views/display.blade.php).
        $linkVideoEmbeds = Pengaturan::displayVideoEmbeds(loopSingle: true);

        return view('display', array_merge($stats, compact(
            'today',
            'now',
            'jadwalHariIni',
            'linkVideoEmbeds'
        )));
    }

    // Endpoint AJAX: dipanggil berkala oleh display.blade.php untuk refresh
    // statistik kartu (Total Kunjungan, Kunjungan Hari Ini, Nilai SKM) tanpa
    // reload seluruh halaman, supaya video Display Online tidak ikut terputus.
    public function displayStats()
    {
        $today = Carbon::today('Asia/Makassar');

        return response()->json($this->hitungStatistikDisplay($today));
    }

    // Endpoint AJAX: dipanggil berkala oleh display.blade.php untuk refresh
    // baris "Jadwal Dinas Hari Ini" tanpa reload seluruh halaman, supaya
    // video Display Online tidak ikut terputus dan jadwal tetap akurat
    // meskipun tanggal berganti hari saat TV menyala terus-menerus.
    public function displayJadwal()
    {
        $today = Carbon::today('Asia/Makassar');

        $jadwalHariIni = JadwalDinas::with(['pegawais.subBagian'])
            ->whereDate('hari_tanggal', $today)
            ->orderBy('waktu')
            ->get();

        $data = $jadwalHariIni->map(function ($jadwal) {
            return [
                'waktu' => $jadwal->waktu ? Carbon::parse($jadwal->waktu)->format('H:i') : null,
                'acara' => $jadwal->acara,
                'bidang_sekretariat' => $jadwal->bidang_sekretariat,
                'tempat_zoom' => $jadwal->tempat_zoom,
                'surat_dari' => $jadwal->surat_dari,
                'nama_pegawai' => $jadwal->pegawais->pluck('nama_lengkap')->filter()->implode(', '),
            ];
        });

        return response()->json(['jadwal' => $data]);
    }

    // Kalkulasi statistik yang dipakai bersama oleh displayTV() dan displayStats()
    private function hitungStatistikDisplay(Carbon $today): array
    {
        // ==== Statistik Kunjungan ====
        $totalKunjungan = Tamu::where('status_aktif', 'aktif')->count();

        $kunjunganHariIni = Tamu::where('status_aktif', 'aktif')
            ->whereDate('created_at', $today)
            ->count();

        // (Opsional) untuk badge "+x% dari kemarin"
        $kunjunganKemarin = Tamu::where('status_aktif', 'aktif')
            ->whereDate('created_at', $today->copy()->subDay())
            ->count();

        $persenHariIni = $kunjunganKemarin > 0
            ? round((($kunjunganHariIni - $kunjunganKemarin) / $kunjunganKemarin) * 100, 1)
            : ($kunjunganHariIni > 0 ? 100 : 0);

        // ==== Statistik SKM ====
        $skmQuery = Respon::where('status', 'aktif');

        $totalResponden = (clone $skmQuery)->count();
        $rataRatingGlobal = (clone $skmQuery)->avg('rata_rating');
        $nilaiSkm = $rataRatingGlobal ? round($rataRatingGlobal * 25, 2) : 0;

        return compact(
            'totalKunjungan',
            'kunjunganHariIni',
            'persenHariIni',
            'nilaiSkm',
            'totalResponden'
        );
    }

    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        $fileName = 'Jadwal_Dinas_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new JadwalDinasExport($search), $fileName);
    }
}