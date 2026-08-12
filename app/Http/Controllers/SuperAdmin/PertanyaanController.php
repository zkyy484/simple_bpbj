<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Opsi;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\DB;

class PertanyaanController extends Controller
{
    public function index(Request $request)
    {

        $admins = auth()->guard('web')->user();
        $pertanyaans = Pertanyaan::with('opsi')
            ->where('status', 'aktif')
            ->when($request->search, fn($q) => $q->where('pertanyaan', 'like', '%' . $request->search . '%'))
            ->orderBy('urutan')
            ->paginate(5);

        return view('super-admin.survei.index', compact('pertanyaans', 'admins'));
    }

    public function arsip(Request $request)
    {
        $search = $request->search;
        $admins = Auth::user();

        $pertanyaans = Pertanyaan::where('status', 'nonaktif') // Filter hanya data aktif
            ->when($search, function ($query) use ($search) {
                $query->where('pertanyaan', 'like', "%{$search}%");
            })
            ->latest('id_pertanyaan')
            ->paginate(5)
            ->withQueryString();

        return view('super-admin.survei.arsip', compact(
            'pertanyaans',
            'search',
            'admins'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'tipe_pertanyaan' => 'required|in:rating,pilihan_ganda,textarea',
            'urutan' => 'required|integer',
            'opsi' => 'required_if:tipe_pertanyaan,rating,pilihan_ganda|array|min:1',
            'opsi.*.opsi' => 'required_with:opsi|string|max:100',
            'opsi.*.nilai' => 'nullable|integer',
        ], [], [], );

        $pertanyaan = DB::transaction(function () use ($validated, $request) {
            $pertanyaan = Pertanyaan::create([
                'pertanyaan' => $validated['pertanyaan'],
                'tipe_pertanyaan' => $validated['tipe_pertanyaan'],
                'urutan' => $validated['urutan'],
            ]);

            if (in_array($validated['tipe_pertanyaan'], ['rating', 'pilihan_ganda'])) {
                foreach ($validated['opsi'] as $opsi) {
                    $pertanyaan->opsi()->create([
                        'opsi' => $opsi['opsi'],
                        'nilai' => $opsi['nilai'] ?? null,
                    ]);
                }
            }

            return $pertanyaan;
        });

        ActivityLog::catat(
            'Tambah Pertanyaan Survei',
            "Menambahkan pertanyaan survei: \"{$pertanyaan->pertanyaan}\" (tipe: {$pertanyaan->tipe_pertanyaan})."
        );

        return redirect()->route('index.pertanyaan')->with('success', 'Pertanyaan berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $pertanyaan = Pertanyaan::findOrFail($id);
        $terkunci = $pertanyaan->sudahAdaRespon();

        $rules = [
            'pertanyaan' => 'required|string',
            'urutan' => 'required|integer',
            'opsi' => 'required_if:tipe_pertanyaan,rating,pilihan_ganda|array',
            'opsi.*.id_opsi' => 'nullable|exists:opsis,id_opsi',
            'opsi.*.opsi' => 'required_with:opsi|string|max:100',
        ];

        // Nilai HANYA relevan untuk rating, pilihan_ganda murni kualitatif
        if ($request->tipe_pertanyaan === 'rating') {
            $rules['opsi.*.nilai'] = 'required|integer';
        }

        // Kalau sudah terkunci, tipe_pertanyaan tidak divalidasi dari input,
        // kita paksa pakai nilai lama supaya tidak bisa diubah walau request dimanipulasi
        if (!$terkunci) {
            $rules['tipe_pertanyaan'] = 'required|in:rating,pilihan_ganda,textarea';
        }

        $validated = $request->validate($rules);

        // Paksa tipe_pertanyaan tetap sama jika terkunci, apapun yang dikirim client
        $tipeFinal = $terkunci ? $pertanyaan->tipe_pertanyaan : $validated['tipe_pertanyaan'];

        DB::transaction(function () use ($pertanyaan, $validated, $tipeFinal) {
            $pertanyaan->update([
                'pertanyaan' => $validated['pertanyaan'],
                'tipe_pertanyaan' => $tipeFinal,
                'urutan' => $validated['urutan'],
            ]);

            if (in_array($tipeFinal, ['rating', 'pilihan_ganda'])) {
                $keepIds = [];
                foreach ($validated['opsi'] as $opsi) {
                    $row = $pertanyaan->opsi()->updateOrCreate(
                        ['id_opsi' => $opsi['id_opsi'] ?? null],
                        [
                            'opsi' => $opsi['opsi'],
                            // nilai cuma disimpan untuk rating, pilihan_ganda selalu null
                            'nilai' => $tipeFinal === 'rating' ? $opsi['nilai'] : null,
                        ]
                    );
                    $keepIds[] = $row->id_opsi;
                }
                $pertanyaan->opsi()->whereNotIn('id_opsi', $keepIds)->delete();
            } else {
                $pertanyaan->opsi()->delete();
            }
        });

        ActivityLog::catat(
            'Ubah Pertanyaan Survei',
            "Memperbarui pertanyaan survei: \"{$pertanyaan->pertanyaan}\"."
        );

        return redirect()->route('index.pertanyaan')->with('success', 'Pertanyaan berhasil diperbarui');
    }

    public function destroy(Request $request)
    {
        // Validasi agar ID wajib ada
        $request->validate([
            'id_pertanyaan' => 'required'
        ]);

        // Cari data berdasarkan ID yang dikirim dari input hidden
        $pertanyaan = Pertanyaan::where('id_pertanyaan', $request->id_pertanyaan)->firstOrFail();

        // Ubah status menjadi nonaktif (soft delete)
        $pertanyaan->update([
            'status' => 'nonaktif'
        ]);

        ActivityLog::catat(
            'Arsipkan Pertanyaan Survei',
            "Mengarsipkan pertanyaan survei: \"{$pertanyaan->pertanyaan}\"."
        );

        return back()->with('success', 'Pertanyaan berhasil dihapus');
    }

    public function pulihkan(Request $request) {
        $request->validate([
            'id_pertanyaan' => 'required'
        ]);

        // Cari data berdasarkan ID yang dikirim dari input hidden
        $pertanyaan = Pertanyaan::where('id_pertanyaan', $request->id_pertanyaan)->firstOrFail();

        // Ubah status menjadi nonaktif (soft delete)
        $pertanyaan->update([
            'status' => 'aktif'
        ]);

        ActivityLog::catat(
            'Pulihkan Pertanyaan Survei',
            "Memulihkan pertanyaan survei: \"{$pertanyaan->pertanyaan}\" dari arsip."
        );

        return back()->with('success', 'Pertanyaan berhasil dipulihkan');
    }
}