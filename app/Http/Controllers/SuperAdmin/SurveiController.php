<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Respon;
use Auth;
use Illuminate\Http\Request;

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
            ->orderByDesc('tanggal_respon');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('instansi', 'like', "%{$search}%");
            });
        }

        $respons = $query->paginate(5)->withQueryString();

        return view('super-admin.survei.data.index', compact('respons', 'admins'));
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

        return redirect()
            ->route('survei.arsip')
            ->with('success', 'Data survei berhasil dipulihkan.');
    }


}