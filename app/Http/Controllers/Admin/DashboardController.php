<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SubBagian;
use App\Models\Tamu;
use App\Models\Respon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $admins = auth()->guard('web')->user();

        // ============================================================
        // KPI 1: Total Kunjungan Tamu (all time) + growth vs bulan lalu
        // ============================================================
        $totalKunjungan = Tamu::count();

        $awalBulanIni = now()->startOfMonth();
        $awalBulanLalu = now()->subMonthNoOverflow()->startOfMonth();
        $akhirBulanLalu = now()->subMonthNoOverflow()->endOfMonth();

        $kunjunganBulanIni = Tamu::whereBetween('created_at', [$awalBulanIni, now()])->count();
        $kunjunganBulanLalu = Tamu::whereBetween('created_at', [$awalBulanLalu, $akhirBulanLalu])->count();

        $persenBulan = $kunjunganBulanLalu > 0
            ? round((($kunjunganBulanIni - $kunjunganBulanLalu) / $kunjunganBulanLalu) * 100, 1)
            : ($kunjunganBulanIni > 0 ? 100 : 0);

        // ============================================================
        // KPI 2: Kunjungan Hari Ini + growth vs kemarin
        // ============================================================
        $kunjunganHariIni = Tamu::whereDate('created_at', today())->count();
        $kunjunganKemarin = Tamu::whereDate('created_at', today()->subDay())->count();

        $persenHari = $kunjunganKemarin > 0
            ? round((($kunjunganHariIni - $kunjunganKemarin) / $kunjunganKemarin) * 100, 1)
            : ($kunjunganHariIni > 0 ? 100 : 0);

        // ============================================================
        // KPI 3: Total Survei Masuk (bulan berjalan, sesuai label di UI)
        // ============================================================
        $totalSurvei = Respon::whereBetween('created_at', [$awalBulanIni, now()])->count();

        // ============================================================
        // Distribusi Kunjungan per Sub Bagian (dinamis, bukan hardcode)
        // ============================================================
        $distribusiSubBagian = SubBagian::withCount('tamus')
            ->orderByDesc('tamus_count')
            ->get();

        $totalDistribusi = $distribusiSubBagian->sum('tamus_count');

        // Palet warna dipakai bergantian untuk kartu, legend, & doughnut chart
        $warnaSubBagian = ['#173860', '#38bdf8', '#818cf8', '#0ea5e9', '#6366f1', '#60a5fa'];

        // ============================================================
        // Filter Mingguan (Senin - Jumat) untuk Aktivitas Kunjungan
        // ?minggu=0  -> minggu ini (default)
        // ?minggu=-1 -> minggu lalu, dst
        // ============================================================
        $minggu = (int) $request->get('minggu', 0);

        $awalMinggu = now()->startOfWeek(Carbon::MONDAY)->addWeeks($minggu);
        $akhirMinggu = (clone $awalMinggu)->addDays(4)->endOfDay(); // sampai Jumat

        $labelHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $dataAktivitas = [];

        foreach (range(0, 4) as $i) {
            $tanggal = (clone $awalMinggu)->addDays($i);
            $dataAktivitas[] = Tamu::whereDate('created_at', $tanggal)->count();
        }

        // Opsi dropdown: 8 minggu terakhir (termasuk minggu ini)
        $opsiMinggu = [];
        foreach (range(0, -7) as $w) {
            $s = now()->startOfWeek(Carbon::MONDAY)->addWeeks($w);
            $e = (clone $s)->addDays(4);
            $opsiMinggu[] = [
                'value' => $w,
                'label' => $s->translatedFormat('d M') . ' - ' . $e->translatedFormat('d M Y'),
            ];
        }

        // ============================================================
        // Log Aktivitas Terbaru (diambil dari data tamu terbaru)
        // ============================================================
        $recentTamu = Tamu::with(['tujuan', 'subBagian'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'admins',
            'totalKunjungan',
            'persenBulan',
            'kunjunganHariIni',
            'persenHari',
            'totalSurvei',
            'distribusiSubBagian',
            'totalDistribusi',
            'warnaSubBagian',
            'labelHari',
            'dataAktivitas',
            'opsiMinggu',
            'minggu',
            'awalMinggu',
            'akhirMinggu',
            'recentTamu'
        ));
    }

    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($user->id_user, 'id_user')],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id_user, 'id_user')],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'nip' => ['required', 'string', 'max:30', Rule::unique('users', 'nip')->ignore($user->id_user, 'id_user')],
            'alamat' => ['nullable', 'string'],
            'paraf' => ['nullable', 'string'],
        ], [
            'nip.unique' => 'NIP sudah digunakan oleh pengguna lain.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'username.unique' => 'Username sudah digunakan oleh pengguna lain.',
        ]);

        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->no_telepon = $validated['no_telepon'] ?? null;
        $user->nip = $validated['nip'];
        $user->alamat = $validated['alamat'] ?? null;

        // Hanya update paraf jika ada data gambar baru (base64) dikirim dari canvas.
        // Jika kosong, paraf lama yang sudah tersimpan tetap dipertahankan.
        $parafDiperbarui = false;
        if (!empty($validated['paraf']) && str_starts_with($validated['paraf'], 'data:image')) {
            $user->paraf = $validated['paraf'];
            $parafDiperbarui = true;
        }

        $user->save();

        ActivityLog::catat(
            'Ubah Profil',
            $parafDiperbarui
                ? 'Memperbarui informasi profil beserta paraf.'
                : 'Memperbarui informasi profil.'
        );

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Informasi profil berhasil disimpan.');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Masukkan password saat ini.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // 2. Cek Password Saat Ini -> Kirim session 'error' untuk SweetAlert
        if (!Hash::check($request->current_password, $user->password)) {
            ActivityLog::catat('Gagal Ubah Password', 'Percobaan ubah password gagal: password saat ini tidak sesuai.');

            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->with('error', 'Password saat ini tidak sesuai!') // <- Memicu SweetAlert Error
                ->withInput()
                ->withFragment('password-section');
        }

        // 3. Simpan Password Baru -> Kirim session 'success' untuk SweetAlert
        $user->password = Hash::make($request->password);
        $user->save();

        // Catatan: deskripsi log SENGAJA tidak menyertakan password lama/baru
        // dalam bentuk apapun (termasuk hash-nya) demi keamanan.
        ActivityLog::catat('Ubah Password', 'Berhasil memperbarui password akun.');

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Password berhasil diperbarui.') // <- Memicu SweetAlert Success
            ->withFragment('password-section');
    }
}