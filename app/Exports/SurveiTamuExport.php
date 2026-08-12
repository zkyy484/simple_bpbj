<?php

namespace App\Exports;

use App\Models\Pertanyaan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveiTamuExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected Collection $respons;
    protected Collection $pertanyaanRating;

    public function __construct(Collection $respons, Collection $pertanyaanRating)
    {
        $this->respons = $respons;
        $this->pertanyaanRating = $pertanyaanRating;
    }

    public function collection()
    {
        return $this->respons;
    }

    public function headings(): array
    {
        $headings = ['No', 'Instansi', 'Pilih Jenis Layanan yang Diterima'];

        foreach ($this->pertanyaanRating as $index => $pertanyaan) {
            $headings[] = 'U' . ($index + 1);
        }

        $headings[] = 'Average';

        return $headings;
    }

    /**
     * @param \App\Models\Respon $respon
     */
    public function map($respon): array
    {
        // Index jawaban berdasarkan id_pertanyaan agar lookup O(1)
        $jawabanByPertanyaan = $respon->jawaban->keyBy('id_pertanyaan');

        // Cari jawaban untuk pertanyaan pilihan_ganda "Pekerjaan"
        $jawabanPekerjaan = $respon->jawaban->first(function ($j) {
            $pertanyaan = $j->pertanyaan;
            return $pertanyaan
                && $pertanyaan->tipe_pertanyaan === 'pilihan_ganda'
                && trim($pertanyaan->pertanyaan) === 'Pekerjaan';
        });

        $pekerjaan = optional(optional($jawabanPekerjaan)->opsi)->opsi ?? '-';

        // Cari jawaban untuk pertanyaan pilihan_ganda "Pilih jenis layanan yang diterima"
        $jawabanJenisLayanan = $respon->jawaban->first(function ($j) {
            $pertanyaan = $j->pertanyaan;
            return $pertanyaan
                && $pertanyaan->tipe_pertanyaan === 'pilihan_ganda'
                && trim($pertanyaan->pertanyaan) === 'Pilih jenis layanan yang diterima';
        });

        $jenisLayanan = optional(optional($jawabanJenisLayanan)->opsi)->opsi ?? '-';

        $row = [
            $respon->id_respon,
            $pekerjaan,
            $jenisLayanan,
        ];

        // Kolom U1, U2, dst — urut sesuai urutan pertanyaan tipe rating
        foreach ($this->pertanyaanRating as $pertanyaan) {
            $jawaban = $jawabanByPertanyaan->get($pertanyaan->id_pertanyaan);
            $row[] = $jawaban->rating ?? '-';
        }

        // Kolom average, diambil dari field rata_rating
        $row[] = $respon->rata_rating !== null ? (float) $respon->rata_rating : '-';

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}