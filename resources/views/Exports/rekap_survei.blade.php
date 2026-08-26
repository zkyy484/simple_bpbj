<table>
    <!-- TABEL 1: JENIS KELAMIN -->
    <thead>
        <tr>
            <th style="font-weight: bold;">Jenis Kelamin</th>
            <th style="font-weight: bold;">Jumlah Responden</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapJenisKelamin['data'] as $item)
            <tr>
                <td>{{ $item['label'] }}</td>
                <td>{{ $item['jumlah'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;">Total</td>
            <td style="font-weight: bold;">{{ $rekapJenisKelamin['total'] }}</td>
        </tr>
    </tbody>

    <!-- BARIS KOSONG SEBAGAI PEMISAH -->
    <tr><td colspan="2"></td></tr>

    <!-- TABEL 2: PENDIDIKAN -->
    <thead>
        <tr>
            <th style="font-weight: bold;">Pendidikan</th>
            <th style="font-weight: bold;">Jumlah Responden</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapPendidikan['data'] as $item)
            <tr>
                <td>{{ $item['label'] }}</td>
                <td>{{ $item['jumlah'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;">Total</td>
            <td style="font-weight: bold;">{{ $rekapPendidikan['total'] }}</td>
        </tr>
    </tbody>

    <!-- BARIS KOSONG SEBAGAI PEMISAH -->
    <tr><td colspan="2"></td></tr>

    <!-- TABEL 3: PEKERJAAN -->
    <thead>
        <tr>
            <th style="font-weight: bold;">Pekerjaan</th>
            <th style="font-weight: bold;">Jumlah Responden</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapPekerjaan['data'] as $item)
            <tr>
                <td>{{ $item['label'] }}</td>
                <td>{{ $item['jumlah'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;">Total</td>
            <td style="font-weight: bold;">{{ $rekapPekerjaan['total'] }}</td>
        </tr>
    </tbody>

    <!-- BARIS KOSONG SEBAGAI PEMISAH -->
    <tr><td colspan="2"></td></tr>

    <!-- TABEL 4: JENIS LAYANAN -->
    <thead>
        <tr>
            <th style="font-weight: bold;">Jenis Layanan</th>
            <th style="font-weight: bold;">Jumlah Responden</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapLayanan['data'] as $item)
            <tr>
                <td>{{ $item['label'] }}</td>
                <td>{{ $item['jumlah'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;">Total</td>
            <td style="font-weight: bold;">{{ $rekapLayanan['total'] }}</td>
        </tr>
    </tbody>
</table>