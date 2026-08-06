<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Respon;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    protected function filteredQuery(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $status = $request->status;

        return Tamu::with(['subBagian', 'tujuan', 'pegawai'])
            ->where('status_aktif', 'aktif')
            ->when($tanggalAwal, fn ($q) => $q->whereDate('created_at', '>=', $tanggalAwal))
            ->when($tanggalAkhir, fn ($q) => $q->whereDate('created_at', '<=', $tanggalAkhir))
            ->when($status, fn ($q) => $q->where('status_tindak_lanjut', $status))
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
            ->when($tanggalAwal, fn ($q) => $q->whereDate('created_at', '>=', $tanggalAwal))
            ->when($tanggalAkhir, fn ($q) => $q->whereDate('created_at', '<=', $tanggalAkhir))
            ->when($pelakuUsaha, fn ($q) => $q->where('jenis_permohonan', $pelakuUsaha))
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

    /**
     * Query dasar untuk Laporan Survei Tamu.
     * Hanya respon yang sudah di-approve (cek = 'approve') yang boleh muncul
     * di Laporan Survei, baik di halaman index maupun export PDF.
     * Filter "Deteksi" yang tersisa untuk user adalah normal / anomali
     * (dihitung dari pola jawaban, lihat isAnomaliSurvei()). Karena
     * normal/anomali tidak bisa difilter langsung lewat SQL, filter itu
     * ditangani terpisah di method surveiTamu() dan exportSurveiTamuPdf().
     */
    protected function filteredQuerySurveiTamu(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        return Respon::query()
            ->with(['jawaban.pertanyaan', 'jawaban.opsi'])
            ->where('status', 'aktif')
            ->where('cek', 'approve')
            ->when($tanggalAwal, fn ($q) => $q->whereDate('tanggal_respon', '>=', $tanggalAwal))
            ->when($tanggalAkhir, fn ($q) => $q->whereDate('tanggal_respon', '<=', $tanggalAkhir))
            ->latest('tanggal_respon');
    }

    /**
     * Deteksi respon anomali: jawaban tipe pilihan_ganda/rating yang
     * nilainya seragam terus (indikasi asal pilih / tidak serius mengisi).
     */
    protected function isAnomaliSurvei(Respon $respon): bool
    {
        $jawabanRelevan = $respon->jawaban->filter(function ($j) {
            $tipe = $j->pertanyaan->tipe_pertanyaan ?? null;
            return in_array($tipe, ['pilihan_ganda', 'rating']);
        });

        if ($jawabanRelevan->count() < 2) {
            return false;
        }

        $nilaiUnik = $jawabanRelevan
            ->pluck('opsi.nilai')
            ->filter(fn ($v) => !is_null($v))
            ->unique();

        return $nilaiUnik->count() <= 1;
    }

    public function surveiTamu(Request $request)
    {
        $admins = Auth::user();

        // Permintaan AJAX untuk memuat detail satu respon survei (dipakai oleh
        // modal "Detail" di halaman Laporan Survei). Tampilan detailnya memakai
        // partial yang sama dengan halaman super-admin/survei/data agar konsisten.
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

        $deteksi = $request->deteksi;

        if (in_array($deteksi, ['normal', 'anomali'])) {
            // Filter normal/anomali dihitung dari collection, jadi paginasi dibuat manual.
            // is_anomali dihitung sekali per respon lalu dipakai ulang untuk filter,
            // supaya isAnomaliSurvei() tidak dipanggil dua kali untuk data yang sama.
            $filtered = $this->filteredQuerySurveiTamu($request)->get()
                ->map(function ($respon) {
                    $respon->is_anomali = $this->isAnomaliSurvei($respon);
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
                $respon->is_anomali = $this->isAnomaliSurvei($respon);
                return $respon;
            });
        }

        return view('super-admin.laporan.surve-tamu', compact('respons', 'admins'));
    }

    public function exportSurveiTamuPdf(Request $request)
    {
        $deteksi = $request->deteksi;
        $respons = $this->filteredQuerySurveiTamu($request)->get();

        if (in_array($deteksi, ['normal', 'anomali'])) {
            $respons = $respons->filter(function ($respon) use ($deteksi) {
                $isAnomali = $this->isAnomaliSurvei($respon);
                return $deteksi === 'anomali' ? $isAnomali : !$isAnomali;
            })->values();
        }

        $periode = trim(
            ($request->tanggal_awal ?? '-') . ' s/d ' . ($request->tanggal_akhir ?? '-')
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('super-admin.laporan.surve-tamu-pdf', [
            'respons' => $respons,
            'periode' => $periode,
            'deteksi' => $deteksi,
        ])->setPaper('a4', 'landscape');

        ActivityLog::catat(
            'Export Laporan Survei Tamu',
            "Mengekspor laporan survei tamu periode {$periode} ke PDF."
        );

        return $pdf->download('laporan-survei-tamu-' . now()->format('Ymd-His') . '.pdf');
    }
}