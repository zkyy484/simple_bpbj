<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Respon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SurveiController extends Controller
{
    public function index(Request $request)
    {
        $admins = Auth::user();
        if ($request->ajax() && $request->filled('id_respon')) {
            $respon = Respon::with([
                'jawaban.pertanyaan.opsi' => function ($query) {
                    $query->orderBy('nilai', 'asc');
                },
                'jawaban.opsi',
            ])->findOrFail($request->id_respon);

            // Map prioritas untuk tipe pertanyaan
            $priorityMap = [
                'pilihan_ganda' => 1,
                'rating' => 2,
                'textarea' => 3,
            ];

            $jawabans = $respon->jawaban
                ->sortBy(function ($j) use ($priorityMap) {
                    $tipe = $j->pertanyaan->tipe_pertanyaan ?? '';
                    $priority = $priorityMap[$tipe] ?? 99;
                    $urutan = $j->pertanyaan->urutan ?? 0;

                    // Urutkan berdasarkan prioritas tipe dulu, lalu berdasarkan nomor urutan
                    return sprintf('%02d-%04d', $priority, $urutan);
                })
                ->values();

            return view('super-admin.survei.data.detail-content', compact('respon', 'jawabans'));
        }

        $query = Respon::query()
            ->where('status', 'aktif')
            ->with(['jawaban.pertanyaan.opsi', 'jawaban.opsi'])
            ->orderByDesc('tanggal_respon');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('instansi', 'like', "%{$search}%");
            });
        }

        $onlyAnomali = $request->boolean('anomali');

        if ($onlyAnomali) {
            // Deteksi anomali membutuhkan relasi jawaban, sehingga difilter
            // di level collection lalu dipaginate secara manual.
            $filtered = $query->get()
                ->map(function ($respon) {
                    $hasil = $this->analisaPolaSurvei($respon);
                    $respon->is_anomali = $hasil['anomali'];
                    $respon->pola_survei = $hasil['pola'];
                    $respon->pola_survei_label = self::POLA_LABEL[$hasil['pola']];
                    return $respon;
                })
                ->filter(fn ($respon) => $respon->is_anomali)
                ->values();

            $perPage = 5;
            $page = LengthAwarePaginator::resolveCurrentPage();

            $respons = new LengthAwarePaginator(
                $filtered->forPage($page, $perPage)->values(),
                $filtered->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $respons = $query->paginate(5)->withQueryString();

            $respons->getCollection()->transform(function ($respon) {
                $hasil = $this->analisaPolaSurvei($respon);
                $respon->is_anomali = $hasil['anomali'];
                $respon->pola_survei = $hasil['pola'];
                $respon->pola_survei_label = self::POLA_LABEL[$hasil['pola']];
                return $respon;
            });
        }

        return view('super-admin.survei.data.index', compact('respons', 'admins', 'onlyAnomali'));
    }

    /**
     * Label pola jawaban untuk ditampilkan di UI.
     */
    public const POLA_LABEL = [
        'rata_kiri'   => 'Rata Kiri',
        'rata_kanan'  => 'Rata Kanan',
        'rata_tengah' => 'Rata Tengah',
        'menaik'      => 'Menaik',
        'menurun'     => 'Menurun',
        'zigzag'      => 'Zigzag',
        'normal'      => 'Normal',
    ];

    /**
     * Deteksi pola jawaban survei (indikasi asal isi / tidak serius mengisi).
     *
     * Cara kerja:
     * 1. Ambil jawaban tipe pilihan_ganda/rating, urutkan sesuai urutan pertanyaan di form.
     * 2. Hitung posisi opsi yang dipilih (kiri = 0, kanan = 1) relatif terhadap
     *    jumlah opsi pada pertanyaan tersebut, supaya bisa dibandingkan lintas
     *    pertanyaan meski jumlah opsinya berbeda-beda.
     * 3. Cocokkan urutan posisi tersebut ke beberapa pola umum:
     *    - rata_kiri   : selalu pilih opsi paling kiri
     *    - rata_kanan  : selalu pilih opsi paling kanan
     *    - rata_tengah : selalu pilih opsi yang sama persis (bukan di ujung)
     *    - menaik      : posisi terus naik dari kiri ke kanan (1,2,3,4,5,...)
     *    - menurun     : posisi terus turun (5,4,3,2,1,...)
     *    - zigzag      : naik-turun berselang-seling konsisten (mis. 1,5,1,5,1,5)
     *    - normal      : tidak cocok pola manapun di atas
     *
     * @return array{pola: string, anomali: bool}
     */
    private function analisaPolaSurvei(Respon $respon): array
    {
        $jawabanRelevan = $respon->jawaban
            ->filter(function ($j) {
                $tipe = $j->pertanyaan->tipe_pertanyaan ?? null;
                return in_array($tipe, ['pilihan_ganda', 'rating']) && $j->id_opsi;
            })
            ->sortBy(fn ($j) => $j->pertanyaan->urutan ?? 0)
            ->values();

        // Minimal 3 jawaban relevan supaya pola bisa dianggap valid (bukan kebetulan).
        if ($jawabanRelevan->count() < 3) {
            return ['pola' => 'normal', 'anomali' => false];
        }

        // Posisi relatif opsi yang dipilih: 0 = paling kiri, 1 = paling kanan.
        $posisi = $jawabanRelevan->map(function ($j) {
            $daftarOpsi = $j->pertanyaan->opsi; // sudah urut by id_opsi (kiri -> kanan)
            $jumlahOpsi = $daftarOpsi->count();
            $index = $daftarOpsi->pluck('id_opsi')->search($j->id_opsi);
            $index = $index === false ? 0 : $index;

            return $jumlahOpsi > 1 ? $index / ($jumlahOpsi - 1) : 0.0;
        })->values();

        $n = $posisi->count();
        $toleransi = 0.05;

        // 1. Rata kiri: semua jawaban di opsi paling kiri.
        if ($posisi->every(fn ($p) => $p <= $toleransi)) {
            return ['pola' => 'rata_kiri', 'anomali' => true];
        }

        // 2. Rata kanan: semua jawaban di opsi paling kanan.
        if ($posisi->every(fn ($p) => $p >= 1 - $toleransi)) {
            return ['pola' => 'rata_kanan', 'anomali' => true];
        }

        // 3. Rata tengah: semua jawaban di posisi yang sama persis (bukan ujung).
        if ($posisi->unique()->count() <= 1) {
            return ['pola' => 'rata_tengah', 'anomali' => true];
        }

        // 4. Menaik / Menurun: posisi bergerak satu arah terus dari awal ke akhir.
        $menaik = true;
        $menurun = true;
        for ($i = 1; $i < $n; $i++) {
            if ($posisi[$i] < $posisi[$i - 1] - 0.001) {
                $menaik = false;
            }
            if ($posisi[$i] > $posisi[$i - 1] + 0.001) {
                $menurun = false;
            }
        }
        if ($menaik) {
            return ['pola' => 'menaik', 'anomali' => true];
        }
        if ($menurun) {
            return ['pola' => 'menurun', 'anomali' => true];
        }

        // 5. Zigzag: naik-turun berselang-seling secara konsisten (mis. 1,5,1,5,1,5).
        $arahSebelumnya = null;
        $zigzag = true;
        $jumlahPerubahanArah = 0;
        for ($i = 1; $i < $n; $i++) {
            $selisih = $posisi[$i] - $posisi[$i - 1];

            if (abs($selisih) < 0.001) {
                $zigzag = false;
                break;
            }

            $arah = $selisih > 0 ? 'naik' : 'turun';

            if ($arahSebelumnya !== null) {
                if ($arah === $arahSebelumnya) {
                    $zigzag = false;
                    break;
                }
                $jumlahPerubahanArah++;
            }

            $arahSebelumnya = $arah;
        }
        // Minimal 3x ganti arah supaya tidak salah tangkap variasi jawaban wajar.
        if ($zigzag && $jumlahPerubahanArah >= 3) {
            return ['pola' => 'zigzag', 'anomali' => true];
        }

        return ['pola' => 'normal', 'anomali' => false];
    }

    public function arsip(Request $request)
    {
        $admins = Auth::user();
        $query = Respon::where('status', 'nonaktif');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('instansi', 'like', "%{$search}%");
            });
        }

        $respons = $query->orderByDesc('tanggal_respon')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.survei.data.arsip', compact('respons', 'admins'));
    }

    // Mengubah status pengecekan respon (kolom "cek") menjadi approve
    public function approve(Request $request)
    {
        $request->validate([
            'id_respon' => 'required|exists:respons,id_respon',
        ]);

        $respon = Respon::findOrFail($request->id_respon);

        $respon->update([
            'cek' => 'approve',
        ]);

        ActivityLog::catat(
            'Approve Survei',
            "Menyetujui (approve) data respon survei atas nama {$respon->nama_lengkap}."
        );

        return redirect()
            ->back()
            ->with('success', 'Status survei berhasil diubah menjadi approve.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id_respon' => 'required|exists:respons,id_respon',
        ]);

        $respon = Respon::findOrFail($request->id_respon);

        $respon->update([
            'status' => 'nonaktif',
        ]);

        ActivityLog::catat(
            'Arsipkan Survei',
            "Mengarsipkan data respon survei atas nama {$respon->nama_lengkap}."
        );

        return redirect()
            ->route('survei.index')
            ->with('success', 'Data survei berhasil diarsipkan.');
    }

    public function pulihkan(Request $request)
    {
        $request->validate([
            'id_respon' => 'required|exists:respons,id_respon',
        ]);

        $respon = Respon::findOrFail($request->id_respon);

        $respon->update([
            'status' => 'aktif',
        ]);

        ActivityLog::catat(
            'Pulihkan Survei',
            "Memulihkan data respon survei atas nama {$respon->nama_lengkap} dari arsip."
        );

        return redirect()
            ->route('survei.arsip')
            ->with('success', 'Data survei berhasil dipulihkan.');
    }
}