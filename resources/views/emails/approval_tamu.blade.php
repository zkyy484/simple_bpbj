<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; color: #333; margin: 0; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 30px; border: 1px solid #e5e7eb; }
        .header { text-align: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #173860; margin: 0; }
        .badge { display: inline-block; background: #d1fae5; color: #065f46; font-weight: bold; padding: 6px 12px; rounded: 20px; font-size: 14px; }
        .info-table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .info-table td { padding: 8px 0; font-size: 14px; }
        .info-table td.label { font-weight: bold; color: #555; width: 35%; }
        .btn-container { text-align: center; margin: 30px 0 10px; }
        .btn { background-color: #173860; color: #ffffff !important; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block; }
        .footer { font-size: 12px; color: #888; text-align: center; margin-top: 30px; border-t: 1px solid #eee; pt: 15px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>Buku Tamu Digital</h2>
            <p style="margin-top: 5px; color: #666; font-size: 14px;">Konfirmasi Persetujuan Kunjungan</p>
        </div>

        <p>Halo, <strong>{{ $tamu->nama_lengkap }}</strong></p>
        <p>Kunjungan Anda telah disetujui. Berikut adalah detail tiket kunjungan Anda:</p>

        <table class="info-table">
            <tr>
                <td class="label">Kode Tiket</td>
                <td>: <strong>{{ $tamu->kode_tiket }}</strong></td>
            </tr>
            <tr>
                <td class="label">Sub Bagian</td>
                <td>: {{ $tamu->subBagian->nama_sub_bagian ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tujuan</td>
                <td>: {{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status Persetujuan</td>
                <td>: <span class="badge">Disetujui</span></td>
            </tr>
        </table>

        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">

        <p style="font-size: 14px; text-align: center;">Mohon kesediaan Anda untuk mengisi survei kepuasan layanan kami melalui tombol di bawah ini:</p>

        <div class="btn-container">
            <a href="{{ $surveiUrl }}" class="btn">Isi Survei Kepuasan</a>
        </div>

        <div class="footer">
            <p>Email ini dikirimkan secara otomatis oleh Sistem Buku Tamu Digital.<br>Harap tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>