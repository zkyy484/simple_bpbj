<?php

namespace App\Exports;

use App\Models\Pertanyaan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveiTamuExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected Collection $respons;
    protected Collection $pertanyaanRating;
    private int $rowNumber = 0;

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

        // Mengubah header 'Average' menjadi 'Total'
        $headings[] = 'Total';

        return $headings;
    }

    /**
     * @param \App\Models\Respon $respon
     */
    public function map($respon): array
    {
        $this->rowNumber++;

        // Index jawaban berdasarkan id_pertanyaan agar lookup O(1)
        $jawabanByPertanyaan = $respon->jawaban->keyBy('id_pertanyaan');

        // Cari jawaban untuk pertanyaan pilihan_ganda "Pekerjaan" (Case-Insensitive)
        $jawabanPekerjaan = $respon->jawaban->first(function ($j) {
            $pertanyaan = $j->pertanyaan;
            return $pertanyaan
                && $pertanyaan->tipe_pertanyaan === 'pilihan_ganda'
                && mb_strtolower(trim($pertanyaan->pertanyaan)) === 'pekerjaan';
        });

        $pekerjaan = optional(optional($jawabanPekerjaan)->opsi)->opsi ?? '-';

        // Cari jawaban untuk pertanyaan pilihan_ganda "Pilih jenis layanan yang diterima" (Case-Insensitive)
        $jawabanJenisLayanan = $respon->jawaban->first(function ($j) {
            $pertanyaan = $j->pertanyaan;
            return $pertanyaan
                && $pertanyaan->tipe_pertanyaan === 'pilihan_ganda'
                && mb_strtolower(trim($pertanyaan->pertanyaan)) === 'pilih jenis layanan yang diterima';
        });

        $jenisLayanan = optional(optional($jawabanJenisLayanan)->opsi)->opsi ?? '-';

        $row = [
            $this->rowNumber,
            $pekerjaan,
            $jenisLayanan,
        ];

        $totalRating = 0;
        $hasRating = false;

        // Kolom U1, U2, dst — urut sesuai urutan pertanyaan tipe rating
        foreach ($this->pertanyaanRating as $pertanyaan) {
            $jawaban = $jawabanByPertanyaan->get($pertanyaan->id_pertanyaan);
            $rating = $jawaban->rating ?? null;

            $row[] = $rating ?? '-';

            // Hitung total nilai rating
            if ($rating !== null) {
                $totalRating += (float) $rating;
                $hasRating = true;
            }
        }

        // Kolom Total
        $row[] = $hasRating ? $totalRating : '-';

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Data Survei Tamu';
    }
}