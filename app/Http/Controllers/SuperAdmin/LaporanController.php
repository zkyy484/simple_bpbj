<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\SurveiTamuExport;
use App\Http\Controllers\Controller;
use App\Models\Pertanyaan;
use App\Models\ActivityLog;
use App\Models\Respon;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
class LaporanController extends Controller
{
    protected function filteredQuery(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $status = $request->status;

        return Tamu::with(['subBagian', 'tujuan', 'pegawai'])
            ->where('status_aktif', 'aktif')
            ->when($tanggalAwal, fn($q) => $q->whereDate('created_at', '>=', $tanggalAwal))
            ->when($tanggalAkhir, fn($q) => $q->whereDate('created_at', '<=', $tanggalAkhir))
            ->when($status, fn($q) => $q->where('status_tindak_lanjut', $status))
            ->latest('id_tamu');
    }

    public function bukuTamu(Request $request)
    {
        $admins = Auth::user();

        $tamus = $this->filteredQuery($request)
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.laporan.buku-tamu', compact('tamus', 'admins'));
    }

    public function exportBukuTamuPdf(Request $request)
    {
        $tamus = $this->filteredQuery($request)->get();

        $periode = trim(
            ($request->tanggal_awal ?? '-') . ' s/d ' . ($request->tanggal_akhir ?? '-')
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('super-admin.laporan.buku-tamu-pdf', [
            'tamus' => $tamus,
            'periode' => $periode,
            'status' => $request->status,
        ])->setPaper('a4', 'landscape');

        ActivityLog::catat(
            'Export Laporan Buku Tamu',
            "Mengekspor laporan buku tamu periode {$periode} ke PDF."
        );

        return $pdf->download('laporan-buku-tamu-' . now()->format('Ymd-His') . '.pdf');
    }

    /**
     * Query dasar untuk Laporan Pengunjung.
     * Filter ketiga di sini adalah "Pelaku Usaha" (kolom jenis_permohonan),
     * bukan status tindak lanjut seperti pada Laporan Buku Tamu.
     */
    protected function filteredQueryPengunjung(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $pelakuUsaha = $request->pelaku_usaha;

        return Tamu::query()
            ->where('status_aktif', 'aktif')
            ->when($tanggalAwal, fn($q) => $q->whereDate('created_at', '>=', $tanggalAwal))
            ->when($tanggalAkhir, fn($q) => $q->whereDate('created_at', '<=', $tanggalAkhir))
            ->when($pelakuUsaha, fn($q) => $q->where('jenis_permohonan', $pelakuUsaha))
            ->latest('id_tamu');
    }

    public function pengunjung(Request $request)
    {
        $admins = Auth::user();

        $pengunjungs = $this->filteredQueryPengunjung($request)
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.laporan.pengunjung', compact('pengunjungs', 'admins'));
    }

    public function exportPengunjungPdf(Request $request)
    {
        $pengunjungs = $this->filteredQueryPengunjung($request)->get();

        $periode = trim(
            ($request->tanggal_awal ?? '-') . ' s/d ' . ($request->tanggal_akhir ?? '-')
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('super-admin.laporan.pengunjung-pdf', [
            'pengunjungs' => $pengunjungs,
            'periode' => $periode,
            'pelakuUsaha' => $request->pelaku_usaha,
        ])->setPaper('a4', 'landscape');

        ActivityLog::catat(
            'Export Laporan Pengunjung',
            "Mengekspor laporan pengunjung periode {$periode} ke PDF."
        );

        return $pdf->download('laporan-pengunjung-' . now()->format('Ymd-His') . '.pdf');
    }

    // LAPORAN SURVEI
    /**
     * Query dasar untuk Laporan Survei Tamu.
     * Hanya respon yang sudah di-approve (cek = 'approve') yang boleh muncul
     * di Laporan Survei, baik di halaman index maupun export PDF.
     * Filter "Deteksi" yang tersisa untuk user adalah normal / anomali
     * (dihitung dari pola jawaban, lihat isAnomaliSurvei()). Karena
     * normal/anomali tidak bisa difilter langsung lewat SQL, filter itu
     * ditangani terpisah di method surveiTamu() dan exportSurveiTamuPdf().
     */
    /**
     * Label pola jawaban untuk ditampilkan di UI.
     */
    public const POLA_LABEL = [
        'rata_kiri' => 'Rata Kiri',
        'rata_kanan' => 'Rata Kanan',
        'rata_tengah' => 'Rata Tengah',
        'menaik' => 'Menaik',
        'menurun' => 'Menurun',
        'zigzag' => 'Zigzag',
        'normal' => 'Normal',
    ];

    protected function filteredQuerySurveiTamu(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $search = $request->search;

        return Respon::query()
            ->with(['jawaban.pertanyaan.opsi', 'jawaban.opsi'])
            ->where('status', 'aktif')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('instansi', 'like', "%{$search}%");
                });
            })
            ->when($tanggalAwal, fn($q) => $q->whereDate('tanggal_respon', '>=', $tanggalAwal))
            ->when($tanggalAkhir, fn($q) => $q->whereDate('tanggal_respon', '<=', $tanggalAkhir))
            ->latest('tanggal_respon');
    }

    /**
     * Deteksi pola jawaban survei (indikasi asal isi / tidak serius mengisi).
     *
     * @return array{pola: string, anomali: bool}
     */
    private function analisaPolaSurvei(Respon $respon): array
    {
        $jawabanRelevan = $respon->jawaban
            ->filter(function ($j) {
                $tipe = $j->pertanyaan->tipe_pertanyaan ?? null;
                return in_array($tipe, ['pilihan_ganda', 'rating']) && $j->id_opsi;
            })
            ->sortBy(fn($j) => $j->pertanyaan->urutan ?? 0)
            ->values();

        if ($jawabanRelevan->count() < 3) {
            return ['pola' => 'normal', 'anomali' => false];
        }

        $posisi = $jawabanRelevan->map(function ($j) {
            $daftarOpsi = $j->pertanyaan->opsi;
            $jumlahOpsi = $daftarOpsi->count();
            $index = $daftarOpsi->pluck('id_opsi')->search($j->id_opsi);
            $index = $index === false ? 0 : $index;

            return $jumlahOpsi > 1 ? $index / ($jumlahOpsi - 1) : 0.0;
        })->values();

        $n = $posisi->count();
        $toleransi = 0.05;

        // 1. Rata kiri
        if ($posisi->every(fn($p) => $p <= $toleransi)) {
            return ['pola' => 'rata_kiri', 'anomali' => true];
        }

        // 2. Rata kanan
        if ($posisi->every(fn($p) => $p >= 1 - $toleransi)) {
            return ['pola' => 'rata_kanan', 'anomali' => true];
        }

        // 3. Rata tengah
        if ($posisi->unique()->count() <= 1) {
            return ['pola' => 'rata_tengah', 'anomali' => true];
        }

        // 4. Menaik / Menurun
        $menaik = true;
        $menurun = true;
        for ($i = 1; $i < $n; $i++) {
            if ($posisi[$i] < $posisi[$i - 1] - 0.001)
                $menaik = false;
            if ($posisi[$i] > $posisi[$i - 1] + 0.001)
                $menurun = false;
        }
        if ($menaik)
            return ['pola' => 'menaik', 'anomali' => true];
        if ($menurun)
            return ['pola' => 'menurun', 'anomali' => true];

        // 5. Zigzag
        $arahSebelumnya = null;
        $zigzag = true;
        $jumlahPerubahanArah = 0;
        for ($i = 1; $i < $n; $i++) {
            $selisih = $posisi[$i] - $posisi[$i - 1];
            if (abs($selisih) < 0.001) {
                $zigzag = false;
                break;
            }
            $arah = $selisih > 0 ? 'naik' : 'turun';
            if ($arahSebelumnya !== null) {
                if ($arah === $arahSebelumnya) {
                    $zigzag = false;
                    break;
                }
                $jumlahPerubahanArah++;
            }
            $arahSebelumnya = $arah;
        }

        if ($zigzag && $jumlahPerubahanArah >= 3) {
            return ['pola' => 'zigzag', 'anomali' => true];
        }

        return ['pola' => 'normal', 'anomali' => false];
    }

    public function surveiTamu(Request $request)
    {
        $admins = Auth::user();

        // AJAX Detail Modal
        if ($request->ajax() && $request->filled('id_respon')) {
            $respon = Respon::with([
                'jawaban.pertanyaan.opsi' => function ($query) {
                    $query->orderBy('nilai', 'asc');
                },
                'jawaban.opsi',
            ])->findOrFail($request->id_respon);

            $priorityMap = [
                'pilihan_ganda' => 1,
                'rating' => 2,
                'textarea' => 3,
            ];

            $jawabans = $respon->jawaban
                ->sortBy(function ($j) use ($priorityMap) {
                    $tipe = $j->pertanyaan->tipe_pertanyaan ?? '';
                    $priority = $priorityMap[$tipe] ?? 99;
                    $urutan = $j->pertanyaan->urutan ?? 0;

                    return sprintf('%02d-%04d', $priority, $urutan);
                })
                ->values();

            return view('super-admin.survei.data.detail-content', compact('respon', 'jawabans'));
        }

        $deteksi = $request->deteksi; // 'normal', 'anomali', atau null (Semua Data)

        if (in_array($deteksi, ['normal', 'anomali'])) {
            $filtered = $this->filteredQuerySurveiTamu($request)->get()
                ->map(function ($respon) {
                    $hasil = $this->analisaPolaSurvei($respon);
                    $respon->is_anomali = $hasil['anomali'];
                    $respon->pola_survei = $hasil['pola'];
                    $respon->pola_survei_label = self::POLA_LABEL[$hasil['pola']];
                    return $respon;
                })
                ->filter(function ($respon) use ($deteksi) {
                    return $deteksi === 'anomali' ? $respon->is_anomali : !$respon->is_anomali;
                })
                ->values();

            $perPage = 10;
            $page = LengthAwarePaginator::resolveCurrentPage();

            $respons = new LengthAwarePaginator(
                $filtered->forPage($page, $perPage)->values(),
                $filtered->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $respons = $this->filteredQuerySurveiTamu($request)
                ->paginate(10)
                ->withQueryString();

            $respons->getCollection()->transform(function ($respon) {
                $hasil = $this->analisaPolaSurvei($respon);
                $respon->is_anomali = $hasil['anomali'];
                $respon->pola_survei = $hasil['pola'];
                $respon->pola_survei_label = self::POLA_LABEL[$hasil['pola']];
                return $respon;
            });
        }

        return view('super-admin.laporan.surve-tamu', compact('respons', 'admins'));
    }

    public function exportSurveiTamuExcel(Request $request)
    {
        $deteksi = $request->deteksi; // 'normal', 'anomali', atau null

        $respons = $this->filteredQuerySurveiTamu($request)->get()
            ->map(function ($respon) {
                $hasil = $this->analisaPolaSurvei($respon);
                $respon->is_anomali = $hasil['anomali'];
                $respon->pola_survei = $hasil['pola'];
                return $respon;
            });

        if (in_array($deteksi, ['normal', 'anomali'])) {
            $respons = $respons
                ->filter(fn($respon) => $deteksi === 'anomali' ? $respon->is_anomali : !$respon->is_anomali)
                ->values();
        }

        // Ambil semua pertanyaan tipe rating, urut sesuai 'urutan', untuk jadi kolom U1..Un
        $pertanyaanRating = Pertanyaan::where('tipe_pertanyaan', 'rating')
            ->where('status', 'aktif')
            ->orderBy('urutan')
            ->get();

        $fileName = 'laporan-survei-tamu-' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new SurveiTamuExport($respons, $pertanyaanRating), $fileName);
    }

    public function arsip(Request $request)
    {
        $admins = Auth::user();

        // 1. Tambahkan eager-loading relasi agar analisaPolaSurvei memiliki data jawaban & opsi
        $query = Respon::query()
            ->where('status', 'nonaktif')
            ->with(['jawaban.pertanyaan.opsi', 'jawaban.opsi']);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('instansi', 'like', "%{$search}%");
            });
        }

        // 2. Ambil data terpaginasi
        $respons = $query->orderByDesc('tanggal_respon')
            ->paginate(10)
            ->withQueryString();

        // 3. Transformasi collection untuk menghitung pola deteksi anomali pada tiap respon
        $respons->getCollection()->transform(function ($respon) {
            $hasil = $this->analisaPolaSurvei($respon);
            $respon->is_anomali = $hasil['anomali'];
            $respon->pola_survei = $hasil['pola'];
            $respon->pola_survei_label = self::POLA_LABEL[$hasil['pola']];
            return $respon;
        });

        return view('super-admin.laporan.arsip', compact('respons', 'admins'));
    }



    // EXPORT PDF
    /**
     * Ambil teks opsi jawaban untuk pertanyaan tipe pilihan_ganda,
     * dicari berdasarkan teks pertanyaan.
     */
    private function getJawabanPilihanGanda($respon, string $teksPertanyaan): string
    {
        $jawaban = $respon->jawaban->first(function ($j) use ($teksPertanyaan) {
            $pertanyaan = $j->pertanyaan;
            return $pertanyaan
                && $pertanyaan->tipe_pertanyaan === 'pilihan_ganda'
                && trim($pertanyaan->pertanyaan) === $teksPertanyaan;
        });

        return optional(optional($jawaban)->opsi)->opsi ?? '-';
    }

    public function exportSurveiTamuPdf(Request $request)
    {
        $deteksi = $request->deteksi;

        $respons = $this->filteredQuerySurveiTamu($request)
            ->get()
            ->map(function ($respon) {
                $hasil = $this->analisaPolaSurvei($respon);

                $respon->is_anomali = $hasil['anomali'];
                $respon->pola_survei = $hasil['pola'];

                return $respon;
            });

        if (in_array($deteksi, ['normal', 'anomali'])) {
            $respons = $respons->filter(function ($respon) use ($deteksi) {
                return $deteksi === 'anomali'
                    ? $respon->is_anomali
                    : !$respon->is_anomali;
            })->values();
        }

        $data = $respons->map(function ($respon, $index) {

            $tanggal = $respon->tanggal_respon ?? $respon->created_at;

            return [
                'nomor' => $index + 1,
                'pekerjaan' => $this->getJawabanPilihanGanda($respon, 'Pekerjaan'),
                'jenis_layanan' => $this->getJawabanPilihanGanda($respon, 'Pilih jenis layanan yang diterima'),
                'tanggal_respon' => $tanggal
                    ? \Carbon\Carbon::parse($tanggal)->format('d-m-Y H:i')
                    : '-',
                'skor' => $respon->rata_rating !== null
                    ? round($respon->rata_rating * 25)
                    : '-',
            ];
        });

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $periode = trim(
            ($tanggalAwal ?? '-') . ' s/d ' . ($tanggalAkhir ?? '-')
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'super-admin.laporan.survei-pdf',
            [
                'data' => $data,
                'tanggalAwal' => $tanggalAwal,
                'tanggalAkhir' => $tanggalAkhir,
                'deteksi' => $deteksi,
            ]
        )->setPaper('a4', 'portrait');

        ActivityLog::catat(
            'Export Laporan Survei Tamu',
            "Mengekspor laporan survei tamu periode {$periode} ke PDF."
        );

        return $pdf->download(
            'laporan-survei-tamu-' . now()->format('Ymd-His') . '.pdf'
        );
    }
}