<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaturanController extends Controller
{
    // Halaman Pengaturan Display Online (Link Video YouTube, mendukung lebih dari 1 video + urutan tayang)
    public function index()
    {
        $admins = Auth::user();
        $linkVideos = Pengaturan::displayVideoLinks();
        $linkVideoEmbeds = Pengaturan::displayVideoEmbeds();

        return view('super-admin.pengaturan.index', compact('linkVideos', 'linkVideoEmbeds', 'admins'));
    }

    // Simpan / Update Daftar Link Video Display (urutan mengikuti urutan submit dari form)
    public function update(Request $request)
    {
        $request->validate([
            'link_video' => ['nullable', 'array'],
            'link_video.*' => ['nullable', 'url', 'max:500'],
        ], [
            'link_video.*.url' => 'Setiap link video harus berupa URL yang valid (contoh: https://youtube.com/watch?v=...).',
        ]);

        $links = $request->input('link_video', []);

        Pengaturan::setDisplayVideoLinks($links);

        ActivityLog::catat(
            'Update Pengaturan Display',
            'Memperbarui daftar link video (' . count(array_filter($links)) . ' video) untuk tampilan TV Display Online.'
        );

        return redirect()
            ->back()
            ->with('success', 'Link video Display Online berhasil disimpan.');
    }
}