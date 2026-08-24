<?php

namespace App\Exports;

use App\Models\Pertanyaan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JenisKelaminSheetExport implements FromView, WithTitle, ShouldAutoSize
{
    protected Collection $respons;

    public function __construct(Collection $respons)
    {
        $this->respons = $respons;
    }

    public function view(): View
    {
        // 1. Ambil semua ID jawaban yang dipilih oleh responden
        $semuaJawaban = $this->respons->flatMap(fn($respon) => $respon->jawaban);
        $countsByOpsiId = $semuaJawaban->pluck('id_opsi')->countBy();

        // 2. Olah data rekap per pertanyaan
        $rekapJenisKelamin = $this->getRekapByPertanyaan('jenis kelamin', $countsByOpsiId);
        $rekapPendidikan    = $this->getRekapByPertanyaan('pendidikan', $countsByOpsiId);
        $rekapPekerjaan     = $this->getRekapByPertanyaan('pekerjaan', $countsByOpsiId);
        $rekapLayanan       = $this->getRekapByPertanyaan('pilih jenis layanan yang diterima', $countsByOpsiId);

        return view('exports.rekap_survei', compact(
            'rekapJenisKelamin',
            'rekapPendidikan',
            'rekapPekerjaan',
            'rekapLayanan'
        ));
    }

    /**
     * Helper reusable untuk menghitung opsi per pertanyaan
     */
    private function getRekapByPertanyaan(string $namaPertanyaan, Collection $countsByOpsiId): array
    {
        $pertanyaan = Pertanyaan::with('opsi')
            ->where('tipe_pertanyaan', 'pilihan_ganda')
            ->whereRaw('LOWER(TRIM(pertanyaan)) = ?', [strtolower(trim($namaPertanyaan))])
            ->first();

        if (!$pertanyaan) {
            return ['data' => [], 'total' => 0];
        }

        $data = $pertanyaan->opsi->map(function ($opsi) use ($countsByOpsiId) {
            return [
                'label'  => $opsi->opsi,
                'jumlah' => $countsByOpsiId->get($opsi->id_opsi, 0),
            ];
        });

        return [
            'data'  => $data,
            'total' => $data->sum('jumlah'),
        ];
    }

    public function title(): string
    {
        return 'Rekap Demografi & Layanan';
    }
}