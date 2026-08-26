<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SurveiTamuMultiSheetExport implements WithMultipleSheets
{
    protected Collection $respons;
    protected Collection $pertanyaanRating;

    public function __construct(Collection $respons, Collection $pertanyaanRating)
    {
        $this->respons = $respons;
        $this->pertanyaanRating = $pertanyaanRating;
    }

    public function sheets(): array
    {
        return [
            // Sheet 1: Data Utama Survei Tamu
            new SurveiTamuExport($this->respons, $this->pertanyaanRating),
            
            // Sheet 2: Rekapitulasi Jenis Kelamin
            new JenisKelaminSheetExport($this->respons),
        ];
    }
}