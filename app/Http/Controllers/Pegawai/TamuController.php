<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TindakLanjutMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TamuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $pegawai = Auth::user();

        $tamus = Tamu::with(['subBagian', 'tujuan', 'pegawai'])
            ->where('status_aktif', 'aktif')
            ->where('approval', 'approve')
            ->where('id_sub_bagian', $pegawai->id_sub_bagian)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('kode_tiket', 'like', "%{$search}%");
                });
            })
            ->latest('id_tamu')
            ->paginate(10)
            ->withQueryString();

        return view('pegawai.tamu.index', compact('tamus', 'search', 'pegawai'));
    }

    public function updateTindakLanjut(Request $request, int $id)
    {
        $tamu = Tamu::findOrFail($id);

        // Keamanan: Pastikan hanya penanggung jawab yang bisa mengubah
        if ($tamu->id_user != Auth::user()->id_user) {
            return redirect()
                ->route('pegawai.tamu.index')
                ->with('error', 'Anda bukan penanggung jawab tamu ini.');
        }

        // Validasi input
        $request->validate([
            'solusi' => 'required|string',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120', // Maksimal 5MB
        ], [
            'solusi.required' => 'Kolom solusi wajib diisi.',
            'dokumen_lampiran.mimes' => 'Format file harus berupa PDF, Word, atau Excel.',
            'dokumen_lampiran.max' => 'Ukuran file dokumen maksimal 5 MB.',
        ]);

        // Handle Upload File (jika ada file diunggah) -> disimpan di disk PRIVATE
        if ($request->hasFile('dokumen_lampiran')) {
            // Hapus file lama jika sebelumnya sudah pernah diunggah
            if ($tamu->dokumen_lampiran && Storage::disk('local')->exists($tamu->dokumen_lampiran)) {
                Storage::disk('local')->delete($tamu->dokumen_lampiran);
            }

            $file = $request->file('dokumen_lampiran');

            // Nama unik (ID tamu + kode acak + nama asli yang sudah di-slug)
            // agar file tamu lain dengan nama sama tidak saling menimpa
            $safeFileName = $tamu->getKey() . '-' . Str::random(8) . '-'
                . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . strtolower($file->getClientOriginalExtension());

            // Simpan ke storage/app/private/dokumen_tamu (tidak bisa diakses langsung lewat URL)
            $tamu->dokumen_lampiran = $file->storeAs('dokumen_tamu', $safeFileName, 'local');
        }

        $tamu->solusi = $request->solusi;

        // Otomatis ubah status menjadi selesai saat solusi diisi
        $tamu->status_tindak_lanjut = 'selesai';

        $tamu->save();

        ActivityLog::catat(
            'Ubah Tindak Lanjut Tamu',
            "Memperbarui tindak lanjut tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket})."
        );

        return redirect()
            ->route('pegawai.tamu.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function kirimEmail(Request $request, int $id)
    {
        $tamu = Tamu::findOrFail($id);

        // Jika belum ada yang menangani, pegawai yang login menjadi penanggung jawab
        if (is_null($tamu->id_user)) {
            $tamu->id_user = Auth::user()->id_user;
            $tamu->save();
        }
        // Jika sudah ditangani pegawai lain
        elseif ($tamu->id_user != Auth::user()->id_user) {
            return redirect()
                ->back()
                ->with('error', 'Data sudah ditangani oleh pegawai lain.');
        }

        // ===== proses kirim email =====
        $tamu->load(['pegawai', 'tujuan', 'subBagian']);

        Mail::to($tamu->email)->send(new TindakLanjutMail($tamu));

        ActivityLog::catat(
            'Kirim Email Tindak Lanjut',
            "Mengirim email tindak lanjut kepada tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket})."
        );

        return redirect()
            ->back()
            ->with('success', 'Email berhasil dikirim.');
    }

    // TERIMA TAMU
    public function terimaTamu(int $id)
    {
        $tamu = Tamu::findOrFail($id);

        // Cek apakah sudah ditangani pegawai lain
        if (!is_null($tamu->id_user) && $tamu->id_user != Auth::user()->id_user) {
            return redirect()
                ->route('pegawai.tamu.index')
                ->with('error', 'Tamu ini sudah diterima oleh pegawai lain.');
        }

        // Assign pegawai yang sedang login sebagai penanggung jawab
        $tamu->id_user = Auth::user()->id_user;

        // Jika status masih default, bisa diatur ke 'eskalasi' atau tetap sesuai kebutuhan
        if (empty($tamu->status_tindak_lanjut) || $tamu->status_tindak_lanjut == 'belum_eskalasi') {
            $tamu->status_tindak_lanjut = 'eskalasi';
        }

        $tamu->save();

        ActivityLog::catat(
            'Terima Tamu',
            "Menerima tamu atas nama {$tamu->nama_lengkap} (Tiket {$tamu->kode_tiket})."
        );

        return redirect()
            ->route('pegawai.tamu.index')
            ->with('success', 'Tamu berhasil diterima. Silakan lakukan tindak lanjut.');
    }

    // MENYAJIKAN DOKUMEN LAMPIRAN (diakses lewat signed URL, bukan symlink public/storage)
    public function dokumen(int $id)
    {
        $tamu = Tamu::findOrFail($id);

        abort_unless(
            $tamu->dokumen_lampiran && Storage::disk('local')->exists($tamu->dokumen_lampiran),
            404,
            'Dokumen tidak ditemukan.'
        );

        // PDF dibuka di browser, Word/Excel otomatis terunduh
        return Storage::disk('local')->response(
            $tamu->dokumen_lampiran,
            basename($tamu->dokumen_lampiran)
        );
    }
}