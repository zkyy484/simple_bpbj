<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Informasi Tindak Lanjut Buku Tamu</title>
</head>
<body style="margin:0; padding:0; background-color:#eef1f5; font-family: 'Segoe UI', Arial, sans-serif;">

    <!-- Wrapper -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#eef1f5; padding:32px 0;">
        <tr>
            <td align="center">

                <!-- Card -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:600px; background-color:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 2px 10px rgba(15,23,42,0.08);">

                    <!-- Header instansi -->
                    <tr>
                        <td style="background-color:#173860; background-image:linear-gradient(135deg,#173860 0%,#0d1b2a 100%); padding:28px 32px; text-align:center; border-bottom:4px solid #d4af37;">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Instansi" style="width:56px; height:auto; margin-bottom:12px;">
                            <div style="color:#ffffff; font-size:20px; font-weight:700; letter-spacing:0.3px;">
                                Buku Tamu Digital
                            </div>
                            <div style="color:#c9d3e0; font-size:12px; letter-spacing:1.5px; text-transform:uppercase; margin-top:4px;">
                                Layanan Resmi Instansi Pemerintah
                            </div>
                        </td>
                    </tr>

                    <!-- Isi -->
                    <tr>
                        <td style="padding:36px 40px 8px 40px;">
                            <p style="color:#1F2937; font-size:15px; line-height:1.6; margin:0 0 4px 0;">
                                Yth. <strong>{{ $tamu->nama_lengkap }}</strong>,
                            </p>
                            <p style="color:#4B5563; font-size:14px; line-height:1.7; margin:0;">
                                Terima kasih telah menggunakan layanan Buku Tamu Digital. Permohonan yang Anda ajukan telah diproses oleh petugas kami. Berikut kami sampaikan rincian dan hasil tindak lanjutnya.
                            </p>
                        </td>
                    </tr>

                    <!-- Detail Tiket -->
                    <tr>
                        <td style="padding:20px 40px 0 40px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #E5E7EB; border-radius:8px; overflow:hidden; font-size:13.5px;">
                                <tr style="background-color:#f4efe6;">
                                    <td colspan="2" style="padding:10px 16px; font-weight:700; color:#173860; font-size:12px; letter-spacing:0.6px; text-transform:uppercase;">
                                        Rincian Permohonan
                                    </td>
                                </tr>
                                <tr style="border-top:1px solid #E5E7EB;">
                                    <td style="padding:12px 16px; font-weight:600; color:#4B5563; width:38%; background-color:#FAFAFA;">Kode Tiket</td>
                                    <td style="padding:12px 16px; font-weight:700; color:#111827; font-family:'Courier New', monospace; font-size:14.5px;">
                                        {{ $tamu->kode_tiket }}
                                    </td>
                                </tr>
                                <tr style="border-top:1px solid #E5E7EB;">
                                    <td style="padding:12px 16px; font-weight:600; color:#4B5563; background-color:#FAFAFA;">Sub Bagian</td>
                                    <td style="padding:12px 16px; color:#1F2937;">{{ $tamu->subBagian->nama_sub_bagian ?? '-' }}</td>
                                </tr>
                                <tr style="border-top:1px solid #E5E7EB;">
                                    <td style="padding:12px 16px; font-weight:600; color:#4B5563; background-color:#FAFAFA;">Tujuan</td>
                                    <td style="padding:12px 16px; color:#1F2937;">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>
                                </tr>
                                <tr style="border-top:1px solid #E5E7EB;">
                                    <td style="padding:12px 16px; font-weight:600; color:#4B5563; background-color:#FAFAFA;">Status</td>
                                    <td style="padding:12px 16px;">
                                        <span style="display:inline-block; padding:4px 12px; background-color:#EFF6FF; color:#1D4ED8; font-size:11.5px; font-weight:700; border-radius:9999px; text-transform:capitalize; letter-spacing:0.3px;">
                                            {{ ucfirst(str_replace('_', ' ', $tamu->status_tindak_lanjut)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr style="border-top:1px solid #E5E7EB;">
                                    <td style="padding:12px 16px; font-weight:600; color:#4B5563; background-color:#FAFAFA;">Petugas</td>
                                    <td style="padding:12px 16px; color:#1F2937;">{{ $tamu->pegawai->nama_lengkap ?? '-' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Solusi -->
                    <tr>
                        <td style="padding:24px 40px 0 40px;">
                            <div style="color:#1F2937; font-size:14px; font-weight:700; margin:0 0 8px 0;">
                                Solusi / Tindak Lanjut
                            </div>
                            <div style="background-color:#F0F4F8; padding:16px 18px; border-left:4px solid #173860; border-radius:0 6px 6px 0; color:#334155; font-size:13.5px; line-height:1.7;">
                                {{ $tamu->solusi }}
                            </div>
                        </td>
                    </tr>

                    <!-- Tombol -->
                    <tr>
                        <td style="padding:32px 40px 8px 40px; text-align:center;">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto;">
                                <tr>
                                    <td style="border-radius:8px; background-color:#173860;">
                                        <a href="{{ route('tracking.tamu', $tamu->kode_tiket) }}"
                                            style="display:inline-block; padding:13px 32px; color:#ffffff; font-size:13.5px; font-weight:700; text-decoration:none; letter-spacing:0.3px; border-radius:8px;">
                                            Lihat Status Tiket
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Catatan kode manual -->
                    <tr>
                        <td style="padding:16px 40px 32px 40px;">
                            <div style="margin-top:8px; padding-top:16px; border-top:1px dashed #E5E7EB; color:#6B7280; font-size:12.5px; line-height:1.6; text-align:center;">
                                Jika tombol di atas tidak dapat dibuka, silakan lakukan pengecekan status secara manual menggunakan kode tiket berikut:<br>
                                <strong style="color:#111827; font-family:'Courier New', monospace; font-size:13.5px;">{{ $tamu->kode_tiket }}</strong>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#0d1b2a; padding:22px 40px; text-align:center;">
                            <div style="color:#e5e9f0; font-size:12.5px; line-height:1.6;">
                                Terima kasih atas kepercayaan Anda terhadap layanan kami.
                            </div>
                            <div style="color:#ffffff; font-size:13px; font-weight:700; margin-top:6px;">
                                {{ config('app.name') }}
                            </div>
                            <div style="color:#8a94a6; font-size:11px; margin-top:10px;">
                                Email ini dikirim secara otomatis, mohon tidak membalas email ini.
                            </div>
                        </td>
                    </tr>

                </table>
                <!-- End Card -->

                <div style="color:#9CA3AF; font-size:11px; margin-top:16px; text-align:center;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi.
                </div>

            </td>
        </tr>
    </table>

</body>
</html>