<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Survei Tamu</title>
    <style>
        /* Pengaturan Ukuran Kertas A4 Portrait & Margin */
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        body { 
            font-family: sans-serif; 
            font-size: 11px; 
            color: #222; 
            margin: 0;
            padding: 0;
        }
        
        h2 { 
            margin-bottom: 2px; 
            margin-top: 0;
        }
        
        .subtitle { 
            font-size: 11px; 
            color: #555; 
            margin-bottom: 14px; 
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        
        th, td { 
            border: 1px solid #ccc; 
            padding: 6px 8px; 
            text-align: left; 
            word-wrap: break-word;
        }
        
        th { 
            background-color: #173860; 
            color: #fff; 
            font-size: 10px; 
            text-transform: uppercase; 
        }
        
        td.center, th.center { 
            text-align: center; 
        }
        
        tr:nth-child(even) { 
            background-color: #f7f8fa; 
        }
    </style>
</head>
<body>
    <h2>Laporan Survei Tamu</h2>
    <div class="subtitle">
        Periode:
        {{ $tanggalAwal ? \Carbon\Carbon::parse($tanggalAwal)->format('d-m-Y') : 'Semua' }}
        s/d
        {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d-m-Y') : 'Semua' }}
        @if($deteksi === 'anomali') &mdash; Hanya Anomali
        @elseif($deteksi === 'normal') &mdash; Hanya Valid
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="center" style="width:6%;">No</th>
                <th style="width:25%;">Pekerjaan</th>
                <th style="width:39%;">Jenis Layanan</th>
                <th class="center" style="width:18%;">Tanggal</th>
                <th class="center" style="width:12%;">Skor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td class="center">{{ $row['nomor'] }}</td>
                    <td>{{ $row['pekerjaan'] }}</td>
                    <td>{{ $row['jenis_layanan'] }}</td>
                    <td class="center">{{ $row['tanggal_respon'] }}</td>
                    <td class="center">{{ $row['skor'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">Belum ada data survei yang sesuai filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>