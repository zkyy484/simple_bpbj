<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Respon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SurveiController extends Controller
{
    public function index(Request $request)
    {
        $admins = Auth::user();

        // AJAX Request untuk modal detail survei
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

                    return sprintf('%02d-%04d', $priority, $urutan);
                })
                ->values();

            return view('super-admin.survei.data.detail-content', compact('respon', 'jawabans'));
        }

        // Query dasar data respon aktif
        $query = Respon::query()
            ->where('status', 'aktif')
            ->with(['jawaban.pertanyaan.opsi', 'jawaban.opsi'])
            ->orderByDesc('tanggal_respon');

        // Filter Pencarian (Nama / Email / Instansi)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('instansi', 'like', "%{$search}%");
            });
        }

        // Paginate standar
        $respons = $query->paginate(5)->withQueryString();

        // Menganalisa pola jawaban untuk penandaan badge status di UI
        $respons->getCollection()->transform(function ($respon) {
            $hasil = $this->analisaPolaSurvei($respon);
            $respon->is_anomali = $hasil['anomali'];
            $respon->pola_survei = $hasil['pola'];
            $respon->pola_survei_label = self::POLA_LABEL[$hasil['pola']];
            return $respon;
        });

        return view('super-admin.survei.data.index', compact('respons', 'admins'));
    }

    /**
     * Label pola jawaban untuk ditampilkan di UI.
     */
    public const POLA_LABEL = [
        'rata_kiri' => 'Rata Kiri',
        'rata_kanan' => 'Rata Kanan',
        'rata_tengah' => 'Rata Tengah',
        'menaik' => 'Menaik',
        'menurun' => 'Menurun',
        'zigzag' => 'Zigzag',
        'normal' => 'Normal',
    ];

    /**
     * Deteksi pola jawaban survei.
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
            ->sortBy(fn($j) => $j->pertanyaan->urutan ?? 0)
            ->values();

        if ($jawabanRelevan->count() < 3) {
            return ['pola' => 'normal', 'anomali' => false];
        }

        $posisi = $jawabanRelevan->map(function ($j) {
            $daftarOpsi = $j->pertanyaan->opsi;
            $jumlahOpsi = $daftarOpsi->count();
            $index = $daftarOpsi->pluck('id_opsi')->search($j->id_opsi);
            $index = $index === false ? 0 : $index;

            return $jumlahOpsi > 1 ? $index / ($jumlahOpsi - 1) : 0.0;
        })->values();

        $n = $posisi->count();
        $toleransi = 0.05;

        if ($posisi->every(fn($p) => $p <= $toleransi)) {
            return ['pola' => 'rata_kiri', 'anomali' => true];
        }

        if ($posisi->every(fn($p) => $p >= 1 - $toleransi)) {
            return ['pola' => 'rata_kanan', 'anomali' => true];
        }

        if ($posisi->unique()->count() <= 1) {
            return ['pola' => 'rata_tengah', 'anomali' => true];
        }

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

        Respon::where('id_respon', $request->id_respon)
            ->update([
                'cek' => 'approve',
            ]);

        return redirect()
            ->back()
            ->with('success', 'Status survei berhasil diubah menjadi approve.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id_respon' => 'required|exists:respons,id_respon',
        ]);

        Respon::where('id_respon', $request->id_respon)
            ->update([
                'status' => 'nonaktif',
            ]);

        return redirect()
            ->route('laporan.survei.index')
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

        return redirect()
            ->route('laporan.survei.arsip')
            ->with('success', 'Data survei berhasil dipulihkan.');
    }
}