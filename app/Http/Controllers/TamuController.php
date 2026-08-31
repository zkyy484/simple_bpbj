<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Jawaban;
use App\Models\JenisPermohonan;
use App\Models\Opsi;
use App\Models\Pertanyaan;
use App\Models\Respon;
use App\Models\Tamu;
use App\Models\SubBagian;
use App\Models\Tujuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    // Menampilkan form kunjungan tamu
    public function FormPage()
    {
        $subBagians = SubBagian::orderBy('nama_sub_bagian')->get();
        $tujuans = Tujuan::orderBy('nama_tujuan')->get();
        $jenisPermohonans = JenisPermohonan::where('status', 'aktif')
            ->orderBy('nama_jenis_permohonan')
            ->get();

        return view('tamu.form', compact('subBagians', 'tujuans', 'jenisPermohonans'));
    }

    // Menyimpan data tamu dari form kunjungan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik_nip' => ['nullable', 'string', 'max:30'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'id_jenis_permohonan' => ['nullable', 'exists:jenis_permohonans,id_jenis_permohonan'],
            'nama_perusahaan' => ['nullable', 'string', 'max:100'],
            'id_sub_bagian' => ['required', 'exists:sub_bagians,id_sub_bagian'],
            'id_tujuan' => ['required', 'exists:tujuans,id_tujuan'],
            'permasalahan' => ['nullable', 'string'],
            'foto' => ['required', 'string'], // Hasil paraf/foto kamera tersimpan di sini
        ], [
            'foto.required' => 'Silakan lengkapi Verifikasi Kehadiran terlebih dahulu (menggunakan Paraf atau Foto Kamera).',
        ]);

        $validated['kode_tiket'] = 'KNS-' . now()->format('Ymd') . str_pad(
            Tamu::whereDate('created_at', now())->count() + 1,
            5,
            '0',
            STR_PAD_LEFT
        );

        $validated['status_tindak_lanjut'] = 'belum_eskalasi';
        $validated['approval'] = 'menunggu';
        $validated['status_aktif'] = 'aktif';

        $tamu = Tamu::create($validated);

        ActivityLog::catat(
            'Isi Buku Tamu',
            "Tamu atas nama {$tamu->nama_lengkap} mengisi buku tamu (Tiket {$tamu->kode_tiket})."
        );

        return redirect()->route('thanks.page', $tamu->id_tamu);
    }

    // Menampilkan halaman terima kasih setelah data tersimpan (buku tamu)
    public function Thanks(int $id)
    {
        $tamu = Tamu::with(['tujuan'])->findOrFail($id);

        return view('tamu.tamu-success', compact('tamu'));
    }


    // TRACKING TIKET
    public function show(string $kode_tiket)
    {
        $tamu = Tamu::with([
            'pegawai',
            'subBagian',
            'tujuan',
            'jenisPermohonan'
        ])->where('kode_tiket', $kode_tiket)
            ->firstOrFail();

        return view('tamu.tracking', compact('tamu'));
    }


    // ========================================
    // SURVEI TAMU
    // ========================================

    public function create()
    {
        $pertanyaans = Pertanyaan::with('opsi')
            ->where('status', 'aktif')
            ->get();

        // Sesuaikan path view dengan lokasi Blade Anda
        return view('tamu.survei', compact('pertanyaans'));
    }

    public function storeSurvei(Request $request)
    {
        // Eager load 'opsi' agar $p->opsi tidak error / query berulang
        $pertanyaans = Pertanyaan::with('opsi')->where('status', 'aktif')->get();

        $rules = [
            'nama_lengkap' => 'required|string|max:50',
            'email' => 'nullable|email|max:50',
            'instansi' => 'nullable|string|max:50',
            'waktu_mulai' => 'required|integer',
        ];

        foreach ($pertanyaans as $p) {
            if (in_array($p->tipe_pertanyaan, ['rating', 'pilihan_ganda'])) {
                $rules["jawaban.{$p->id_pertanyaan}"] = 'required|exists:opsis,id_opsi';
            } else {
                $rules["jawaban.{$p->id_pertanyaan}"] = 'required|string';
            }
        }

        $validated = $request->validate($rules, [
            'jawaban.*.required' => 'Pertanyaan ini wajib dijawab.',
        ]);

        $waktuSelesai = now();
        $durasi = max(1, $waktuSelesai->timestamp - (int) $validated['waktu_mulai']);

        try {
            DB::transaction(function () use ($validated, $pertanyaans, $durasi, $waktuSelesai) {

                // Simpan data Respon Utama
                $respon = Respon::create([
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'email' => $validated['email'] ?? null,
                    'instansi' => $validated['instansi'] ?? null,
                    'durasi_pengisian' => $durasi,
                    'tanggal_respon' => $waktuSelesai->toDateTimeString(),
                ]);

                $urutanOpsiRating = [];

                // Simpan Setiap Jawaban
                foreach ($pertanyaans as $p) {
                    $jawabanInput = $validated['jawaban'][$p->id_pertanyaan] ?? null;

                    if (!$jawabanInput) {
                        continue;
                    }

                    if (in_array($p->tipe_pertanyaan, ['rating', 'pilihan_ganda'])) {
                        $opsi = Opsi::find($jawabanInput);

                        // Urutan opsi (1, 2, 3, dst.) untuk rating -> dipakai untuk deteksi pola
                        $ratingValue = null;
                        if ($p->tipe_pertanyaan === 'rating') {
                            $ratingValue = $p->opsi->pluck('id_opsi')->search($opsi->id_opsi) + 1;
                            $urutanOpsiRating[] = $ratingValue;
                        }

                        Jawaban::create([
                            'id_respon' => $respon->id_respon,
                            'id_pertanyaan' => $p->id_pertanyaan,
                            'id_opsi' => $opsi->id_opsi,
                            'rating' => $ratingValue,
                            'jawaban' => null,
                        ]);
                    } else {
                        Jawaban::create([
                            'id_respon' => $respon->id_respon,
                            'id_pertanyaan' => $p->id_pertanyaan,
                            'id_opsi' => null,
                            'rating' => null,
                            'jawaban' => $jawabanInput,
                        ]);
                    }
                }

                // Hitung skor rata-rata rating, standar deviasi, dan deteksi pola jawaban
                $this->hitungSkorDanPola($respon, $urutanOpsiRating);

                ActivityLog::catat(
                    'Isi Survei',
                    "Tamu atas nama {$respon->nama_lengkap} mengisi survei kepuasan."
                );
            });

            // PERBAIKAN UTAMA: nama route disamakan dengan yang terdaftar di web.php
            return redirect()->route('survei.thanks')->with('success', 'Survei berhasil dikirim!');

        } catch (\Exception $e) {
            // Log pesan error sesungguhnya ke storage/logs/laravel.log
            Log::error('Gagal simpan survei: ' . $e->getMessage());

            // Kembalikan ke halaman form dengan pesan error
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan survei: ' . $e->getMessage());
        }
    }

    private function hitungSkorDanPola(Respon $respon, array $urutanRating): void
    {
        $nilaiArray = Jawaban::where('id_respon', $respon->id_respon)
            ->whereHas('pertanyaan', fn($q) => $q->where('tipe_pertanyaan', 'rating'))
            ->with('opsi')
            ->get()
            ->pluck('opsi.nilai')
            ->filter()
            ->values();

        $rataRating = $nilaiArray->count() ? round($nilaiArray->avg(), 2) : null;

        $respon->update([
            'rata_rating' => $rataRating,
        ]);
    }


    // Halaman sukses setelah survei berhasil dikirim
    public function thankSurvei()
    {
        return view('tamu.survei-success');
    }
}