<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;
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

        return $pdf->download('laporan-pengunjung-' . now()->format('Ymd-His') . '.pdf');
    }
}