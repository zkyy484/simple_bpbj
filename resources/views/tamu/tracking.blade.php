<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <title>Tracking Buku Tamu</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="max-w-5xl mx-auto py-10 px-5">

    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

        <div class="bg-[#173860] text-white px-8 py-6">

            <h1 class="text-3xl font-bold">
                Tracking Buku Tamu
            </h1>

            <p class="text-blue-100 mt-2">
                Pantau perkembangan permohonan Anda secara real-time.
            </p>

        </div>

        <div class="p-8">

            <div class="grid md:grid-cols-2 gap-8">

                <div>

                    <h3 class="font-bold text-lg mb-4">
                        Informasi Tamu
                    </h3>

                    <table class="w-full">

                        <tr>

                            <td class="font-semibold py-2">Kode Tiket</td>

                            <td>{{ $tamu->kode_tiket }}</td>

                        </tr>

                        <tr>

                            <td class="font-semibold py-2">Nama</td>

                            <td>{{ $tamu->nama_lengkap }}</td>

                        </tr>

                        <tr>

                            <td class="font-semibold py-2">Email</td>

                            <td>{{ $tamu->email }}</td>

                        </tr>

                        <tr>

                            <td class="font-semibold py-2">Sub Bagian</td>

                            <td>{{ $tamu->subBagian->nama_sub_bagian ?? '-' }}</td>

                        </tr>

                        <tr>

                            <td class="font-semibold py-2">Tujuan</td>

                            <td>{{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>

                        </tr>

                        <tr>

                            <td class="font-semibold py-2">Petugas</td>

                            <td>{{ $tamu->pegawai->nama_lengkap ?? '-' }}</td>

                        </tr>

                    </table>

                </div>

                <div>

                    <h3 class="font-bold text-lg mb-4">

                        Status Tindak Lanjut

                    </h3>

                    @php

                        $warna = match($tamu->status_tindak_lanjut){

                            'selesai'=>'bg-green-100 text-green-700',

                            'eskalasi'=>'bg-yellow-100 text-yellow-700',

                            default=>'bg-gray-100 text-gray-700'

                        };

                    @endphp

                    <span class="inline-block px-5 py-2 rounded-full font-bold {{ $warna }}">

                        {{ ucfirst(str_replace('_',' ',$tamu->status_tindak_lanjut)) }}

                    </span>

                    <div class="mt-8">

                        <h4 class="font-semibold mb-3">

                            Solusi

                        </h4>

                        <div class="bg-gray-50 rounded-xl border p-5">

                            {{ $tamu->solusi ?? 'Belum ada solusi.' }}

                        </div>

                    </div>

                </div>

            </div>

            <hr class="my-8">

            <h3 class="font-bold text-lg mb-6">

                Progress

            </h3>

            <div class="space-y-5">

                <div class="flex items-center">

                    <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center">

                        ✓

                    </div>

                    <div class="ml-4">

                        <p class="font-semibold">

                            Tamu berhasil mengisi Buku Tamu

                        </p>

                        <small>

                            {{ $tamu->created_at->format('d M Y H:i') }}

                        </small>

                    </div>

                </div>

                <div class="flex items-center">

                    <div class="w-10 h-10 rounded-full {{ $tamu->approval=='approve' ? 'bg-green-500 text-white':'bg-gray-300' }} flex items-center justify-center">

                        ✓

                    </div>

                    <div class="ml-4">

                        <p class="font-semibold">

                            Disetujui Admin

                        </p>

                    </div>

                </div>

                <div class="flex items-center">

                    <div class="w-10 h-10 rounded-full {{ $tamu->id_user ? 'bg-green-500 text-white':'bg-gray-300' }} flex items-center justify-center">

                        ✓

                    </div>

                    <div class="ml-4">

                        <p class="font-semibold">

                            Diproses Pegawai

                        </p>

                    </div>

                </div>

                <div class="flex items-center">

                    <div class="w-10 h-10 rounded-full {{ $tamu->status_tindak_lanjut=='selesai' ? 'bg-green-500 text-white':'bg-gray-300' }} flex items-center justify-center">

                        ✓

                    </div>

                    <div class="ml-4">

                        <p class="font-semibold">

                            Permohonan Selesai

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>