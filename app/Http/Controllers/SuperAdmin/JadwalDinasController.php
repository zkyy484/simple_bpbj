<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JadwalDinas;
use App\Models\Respon;
use App\Models\Tamu;
use App\Models\User;
use App\Models\ActivityLog;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalDinasController extends Controller
{
    // List Jadwal Dinas (Admin/Pegawai)
    public function index(Request $request)
    {
        $search = $request->search;
        $admins = Auth::user();

        $jadwals = JadwalDinas::with('pegawais')
            ->when($search, function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('surat_dari', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            })
            ->latest('tanggal_kegiatan')
            ->paginate(10);

        $users = User::orderBy('nama_lengkap', 'asc')->get();

        return view('super-admin.jadwal-dinas.index', compact('jadwals', 'users', 'admins'));
    }

    // Simpan Data Jadwal & Penugasan
    public function store(Request $request)
    {
        $request->validate([
            'surat_dari' => 'required|string|max:150',
            'nomor_surat' => 'required|string|max:100',
            'perihal' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_kegiatan' => 'required|date',
            'pegawai_ids' => 'nullable|array',
            'pegawai_ids.*' => 'exists:users,id_user',
        ]);

        $jadwal = JadwalDinas::create([
            'nomor_agenda' => $request->nomor_agenda,
            'surat_dari' => $request->surat_dari,
            'nomor_surat' => $request->nomor_surat,
            'perihal' => $request->perihal,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'keterangan' => $request->keterangan,
        ]);

        // Sinkronisasi Pegawai yang Ditugaskan (Bisa Kosong / Nullable)
        if ($request->has('pegawai_ids')) {
            $jadwal->pegawais()->sync($request->pegawai_ids);
        }

        ActivityLog::catat(
            'Input Jadwal Dinas',
            "Menambahkan agenda dinas luar perihal: {$jadwal->perihal} pada tanggal {$jadwal->tanggal_kegiatan}."
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
            ->whereDate('tanggal_kegiatan', $today)
            ->orderBy('tanggal_kegiatan')
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
            'surat_dari' => 'required|string|max:150',
            'nomor_surat' => 'required|string|max:100',
            'perihal' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_kegiatan' => 'required|date',
            'pegawai_ids' => 'nullable|array',
            'pegawai_ids.*' => 'exists:users,id_user',
        ], [
            'surat_dari.required' => 'Kolom Surat Dari wajib diisi.',
            'nomor_surat.required' => 'Nomor Surat wajib diisi.',
            'perihal.required' => 'Perihal wajib diisi.',
            'tanggal_surat.required' => 'Tanggal Surat wajib diisi.',
            'tanggal_kegiatan.required' => 'Tanggal Kegiatan wajib diisi.',
        ]);

        // Jika Primary Key kustom di model adalah 'id_jadwal_dinas'
        $jadwal = JadwalDinas::where('id_jadwal_dinas', $id)->firstOrFail();
        // Jika menggunakan PK standar 'id', tetap gunakan: JadwalDinas::findOrFail($id);

        // Update data utama
        $jadwal->update([
            'nomor_agenda' => $request->nomor_agenda,
            'surat_dari' => $request->surat_dari,
            'nomor_surat' => $request->nomor_surat,
            'perihal' => $request->perihal,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'keterangan' => $request->keterangan,
        ]);

        // Sync relasi pegawai
        $jadwal->pegawais()->sync($request->input('pegawai_ids', []));

        ActivityLog::catat(
            'Edit Jadwal Dinas',
            "Memperbarui data jadwal dinas perihal: {$jadwal->perihal} (Nomor Surat: {$jadwal->nomor_surat})."
        );

        return redirect()
            ->back()
            ->with('success', 'Jadwal dinas berhasil diperbarui.');
    }
}