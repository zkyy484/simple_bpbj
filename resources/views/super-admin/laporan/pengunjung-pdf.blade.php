<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengunjung</title>
    <style>
        * { font-family: 'Helvetica', Arial, sans-serif; box-sizing: border-box; }
        body { font-size: 11px; color: #1f2937; margin: 24px; }
        .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #173860; padding-bottom: 12px; }
        .header h1 { font-size: 16px; margin: 0 0 2px; color: #173860; }
        .header p { margin: 0; font-size: 10px; color: #6b7280; }
        .meta { margin-bottom: 12px; font-size: 10px; color: #374151; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #173860; color: #fff; font-size: 9px; text-transform: uppercase; letter-spacing: 0.03em; }
        tr:nth-child(even) td { background: #f9fafb; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 8px; font-weight: bold; color: #fff; }
        .badge-belum { background: #9ca3af; color: #1f2937; }
        .badge-eskalasi { background: #a3e635; color: #365314; }
        .badge-selesai { background: #10b981; }
        .footer { margin-top: 16px; font-size: 9px; color: #9ca3af; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pengunjung</h1>
        <p>Bagian Pengadaan Barang dan Jasa &mdash; Sekretariat Daerah Kota Denpasar</p>
    </div>

    <div class="meta">
        Periode: {{ $periode }} &nbsp;|&nbsp;
        Pelaku Usaha: {{ $pelakuUsaha ?: 'Semua' }} &nbsp;|&nbsp;
        Dicetak: {{ now()->format('d-m-Y H:i') }} WITA
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Nomor</th>
                <th>Perusahaan</th>
                <th>Pelaku Usaha</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengunjungs as $pengunjung)
                @php
                    $statusMap = [
                        'belum_eskalasi' => ['label' => 'Belum Ditindak', 'class' => 'badge-belum'],
                        'eskalasi' => ['label' => 'Ditindak', 'class' => 'badge-eskalasi'],
                        'selesai' => ['label' => 'Selesai', 'class' => 'badge-selesai'],
                    ];
                    $badge = $statusMap[$pengunjung->status_tindak_lanjut] ?? ['label' => '-', 'class' => ''];
                @endphp
                <tr>
                    <td>{{ $pengunjung->id_tamu }}</td>
                    <td>{{ $pengunjung->nama_lengkap }}</td>
                    <td>{{ $pengunjung->email ?? '-' }}</td>
                    <td>{{ $pengunjung->nomor_telepon ?? '-' }}</td>
                    <td>{{ $pengunjung->nama_perusahaan ?? '-' }}</td>
                    <td>{{ $pengunjung->jenis_permohonan ?? '-' }}</td>
                    <td><span class="badge {{ $badge['class'] }}">{{ strtoupper($badge['label']) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#9ca3af;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total data: {{ $pengunjungs->count() }} entri
    </div>
</body>
</html>