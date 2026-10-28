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

    // Halaman 1: tamu memilih nama (hanya tamu yang mengisi buku tamu hari ini)
    public function pilihTamu()
    {
        $tamus = Tamu::hariIni()->orderBy('nama_lengkap')->get();

        $sudahMengisi = Respon::whereIn('id_tamu', $tamus->pluck('id_tamu'))
            ->whereHas('jawaban')
            ->pluck('id_tamu')
            ->all();

        return view('tamu.survei-pilih', compact('tamus', 'sudahMengisi'));
    }

    public function simpanPilihTamu(Request $request)
    {
        $request->validate([
            'id_tamu' => ['required', 'integer', 'exists:tamus,id_tamu'],
        ], [
            'id_tamu.required' => 'Silakan pilih nama Anda terlebih dahulu.',
        ]);

        $tamu = Tamu::hariIni()->find($request->id_tamu);

        if (!$tamu) {
            return back()->withErrors(['id_tamu' => 'Nama yang dipilih tidak terdaftar pada buku tamu hari ini.']);
        }

        if (Respon::tamuSudahMengisi($tamu->id_tamu)) {
            return back()->withErrors(['id_tamu' => 'Anda sudah mengisi survei kepuasan.']);
        }

        session(['survei_id_tamu' => $tamu->id_tamu]);

        return redirect()->route('survei.create');
    }

    // Halaman 2: form survei (/survei), identitas terisi otomatis dari data tamu
    public function create()
    {
        $tamu = $this->tamuDariSession();

        if (!$tamu) {
            return redirect()->route('survei.pilih')
                ->with('error', 'Silakan pilih nama Anda terlebih dahulu.');
        }

        $pertanyaans = Pertanyaan::with('opsi')
            ->where('status', 'aktif')
            ->get();

        return view('tamu.survei', compact('pertanyaans', 'tamu'));
    }

    // Ambil tamu dari session; null jika tidak valid / bukan hari ini / sudah mengisi
    private function tamuDariSession(): ?Tamu
    {
        $id = session('survei_id_tamu');

        if (!$id) {
            return null;
        }

        $tamu = Tamu::hariIni()->find($id);

        if (!$tamu || Respon::tamuSudahMengisi($tamu->id_tamu)) {
            session()->forget('survei_id_tamu');
            return null;
        }

        return $tamu;
    }

    public function storeSurvei(Request $request)
    {
        $tamu = $this->tamuDariSession();

        if (!$tamu) {
            return redirect()->route('survei.pilih')
                ->with('error', 'Sesi survei tidak valid atau Anda sudah mengisi survei. Silakan pilih nama kembali.');
        }

        // Eager load 'opsi' agar $p->opsi tidak error / query berulang
        $pertanyaans = Pertanyaan::with('opsi')->where('status', 'aktif')->get();

        // Nama, email, instansi TIDAK diterima dari input: diambil dari data tamu
        $rules = [
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
            DB::transaction(function () use ($validated, $pertanyaans, $durasi, $waktuSelesai, $tamu) {

                // Pakai respon dari ceklis (jika ada), kalau tidak buat baru
                $respon = Respon::where('id_tamu', $tamu->id_tamu)->first() ?? new Respon();

                $respon->fill([
                    'id_tamu' => $tamu->id_tamu,
                    'nama_lengkap' => mb_substr($tamu->nama_lengkap, 0, 50),
                    'email' => $tamu->email ? mb_substr($tamu->email, 0, 50) : null,
                    'instansi' => $tamu->nama_perusahaan ? mb_substr($tamu->nama_perusahaan, 0, 50) : null,
                    'durasi_pengisian' => $durasi,
                    'tanggal_respon' => $waktuSelesai->toDateTimeString(),
                ]);
                $respon->save();

                $urutanOpsiRating = [];

                // Simpan Setiap Jawaban
                foreach ($pertanyaans as $p) {
                    $jawabanInput = $validated['jawaban'][$p->id_pertanyaan] ?? null;

                    if (!$jawabanInput) {
                        continue;
                    }

                    if (in_array($p->tipe_pertanyaan, ['rating', 'pilihan_ganda'])) {
                        $opsi = Opsi::find($jawabanInput);

                        // Urutan opsi (1, 2, 3, dst.) untuk rating
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

                // Hitung skor rata-rata rating
                $this->hitungSkorDanPola($respon, $urutanOpsiRating);

                ActivityLog::catat(
                    'Isi Survei',
                    "Tamu atas nama {$respon->nama_lengkap} mengisi survei kepuasan."
                );
            });

            session()->forget('survei_id_tamu');

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