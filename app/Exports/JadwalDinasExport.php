<?php

namespace App\Exports;

use App\Models\JadwalDinas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class JadwalDinasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $search;
    private $rowNumber = 0;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = JadwalDinas::with('pegawais')
            ->orderBy('hari_tanggal', 'desc')
            ->orderBy('waktu', 'desc');

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('acara', 'like', "%{$search}%")
                    ->orWhere('surat_dari', 'like', "%{$search}%")
                    ->orWhere('bidang_sekretariat', 'like', "%{$search}%")
                    ->orWhere('tempat_zoom', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'Bidang/Sekretariat',
            'Acara',
            'Surat Dari',
            'Hari/Tanggal',
            'Waktu',
            'Tempat/Zoom',
            'Yang Hadir',
            'Keterangan',
        ];
    }

    public function map($jadwal): array
    {
        $this->rowNumber++;

        // Menggabungkan nama-nama pegawai yang hadir disepakati dengan koma
        $yangHadir = $jadwal->pegawais->pluck('nama_lengkap')->implode(', ');

        return [
            $this->rowNumber,
            $jadwal->bidang_sekretariat ?? '-',
            $jadwal->acara,
            $jadwal->surat_dari,
            Carbon::parse($jadwal->hari_tanggal)->translatedFormat('l, d M Y'),
            $jadwal->waktu ? Carbon::parse($jadwal->waktu)->format('H:i') . ' WITA' : '-',
            $jadwal->tempat_zoom ?? '-',
            !empty($yangHadir) ? $yangHadir : 'Belum ditentukan',
            $jadwal->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}