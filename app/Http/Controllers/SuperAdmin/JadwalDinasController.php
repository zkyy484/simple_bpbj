<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JadwalDinas;
use App\Models\Respon;
use App\Models\Tamu;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalDinasController extends Controller
{
    // List Jadwal Dinas (Admin/Pegawai)
    // Kolom: NO | Bidang/Sekretariat | Acara | Surat Dari | Hari/Tanggal | Waktu | Tempat/Zoom | Yang Hadir | Keterangan
    public function index(Request $request)
    {
        $search = $request->search;
        $admins = Auth::user();

        $jadwals = JadwalDinas::with('pegawais')
            ->when($search, function ($q) use ($search) {
                $q->where('acara', 'like', "%{$search}%")
                    ->orWhere('surat_dari', 'like', "%{$search}%")
                    ->orWhere('bidang_sekretariat', 'like', "%{$search}%")
                    ->orWhere('tempat_zoom', 'like', "%{$search}%");
            })
            ->latest('hari_tanggal')
            ->paginate(10);

        $users = User::orderBy('nama_lengkap', 'asc')->get();

        return view('super-admin.jadwal-dinas.index', compact('jadwals', 'users', 'admins'));
    }

    // Simpan Data Jadwal & Penugasan
    public function store(Request $request)
    {
        $request->validate([
            'bidang_sekretariat' => 'nullable|string|max:150',
            'acara' => 'required|string',
            'surat_dari' => 'required|string|max:150',
            'hari_tanggal' => 'required|date',
            'waktu' => 'nullable|date_format:H:i',
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

        // Sinkronisasi Yang Hadir / Pegawai yang Ditugaskan (Bisa Kosong / Nullable)
        if ($request->has('pegawai_ids')) {
            $jadwal->pegawais()->sync($request->pegawai_ids);
        }

        ActivityLog::catat(
            'Input Jadwal Dinas',
            "Menambahkan agenda dinas luar acara: {$jadwal->acara} pada tanggal {$jadwal->hari_tanggal}."
        );

        return redirect()->back()->with('success', 'Jadwal dinas luar berhasil ditambahkan.');
    }

    // Tampilan Publik Khusus Layar Monitor TV (Full Day 00:01 - 23:59)
    public function displayTV()
    {
        $today = Carbon::today('Asia/Makassar');
        $now   = Carbon::now('Asia/Makassar');

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

        // ==== Jadwal Dinas Hari Ini ====
        $jadwalHariIni = JadwalDinas::with(['pegawais.subBagian'])
            ->whereDate('hari_tanggal', $today)
            ->orderBy('waktu')
            ->get();

        return view('display', compact(
            'today',
            'now',
            'totalKunjungan',
            'kunjunganHariIni',
            'persenHariIni',
            'nilaiSkm',
            'totalResponden',
            'jadwalHariIni'
        ));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'bidang_sekretariat' => 'nullable|string|max:150',
            'acara' => 'required|string',
            'surat_dari' => 'required|string|max:150',
            'hari_tanggal' => 'required|date',
            'waktu' => 'nullable|date_format:H:i',
            'tempat_zoom' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'pegawai_ids' => 'nullable|array',
            'pegawai_ids.*' => 'exists:users,id_user',
        ], [
            'acara.required' => 'Kolom Acara wajib diisi.',
            'surat_dari.required' => 'Kolom Surat Dari wajib diisi.',
            'hari_tanggal.required' => 'Kolom Hari/Tanggal wajib diisi.',
        ]);

        // Jika Primary Key kustom di model adalah 'id_jadwal_dinas'
        $jadwal = JadwalDinas::where('id_jadwal_dinas', $id)->firstOrFail();

        // Update data utama
        $jadwal->update([
            'bidang_sekretariat' => $request->bidang_sekretariat,
            'acara' => $request->acara,
            'surat_dari' => $request->surat_dari,
            'hari_tanggal' => $request->hari_tanggal,
            'waktu' => $request->waktu,
            'tempat_zoom' => $request->tempat_zoom,
            'keterangan' => $request->keterangan,
        ]);

        // Sync relasi pegawai (Yang Hadir)
        $jadwal->pegawais()->sync($request->input('pegawai_ids', []));

        ActivityLog::catat(
            'Edit Jadwal Dinas',
            "Memperbarui data jadwal dinas acara: {$jadwal->acara} (Surat Dari: {$jadwal->surat_dari})."
        );

        return redirect()
            ->back()
            ->with('success', 'Jadwal dinas berhasil diperbarui.');
    }
}