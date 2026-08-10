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
    public function index() {
        $admins = Auth::user();

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // ===== KPI 1: Total Kunjungan Tamu (aktif) + perbandingan bulan ini vs bulan lalu =====
        $totalKunjungan = Tamu::where('status_aktif', 'aktif')->count();

        $kunjunganBulanIni = Tamu::where('status_aktif', 'aktif')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->count();

        $kunjunganBulanLalu = Tamu::where('status_aktif', 'aktif')
            ->whereMonth('created_at', $today->copy()->subMonth()->month)
            ->whereYear('created_at', $today->copy()->subMonth()->year)
            ->count();

        $persenBulanan = $this->hitungPersentase($kunjunganBulanIni, $kunjunganBulanLalu);

        // ===== KPI 2: Kunjungan Hari Ini vs Kemarin =====
        $kunjunganHariIni = Tamu::where('status_aktif', 'aktif')
            ->whereDate('created_at', $today)
            ->count();

        $kunjunganKemarin = Tamu::where('status_aktif', 'aktif')
            ->whereDate('created_at', $yesterday)
            ->count();

        $persenHarian = $this->hitungPersentase($kunjunganHariIni, $kunjunganKemarin);

        // ===== KPI 3: Total Survei Masuk bulan ini =====
        $totalSurvei = Respon::whereMonth('tanggal_respon', $today->month)
            ->whereYear('tanggal_respon', $today->year)
            ->count();

        // ===== Distribusi Kunjungan per Sub Bagian =====
        $distribusiSubBagian = SubBagian::withCount(['tamus' => function ($q) {
                $q->where('status_aktif', 'aktif');
            }])
            ->orderByDesc('tamus_count')
            ->get();

        $totalDistribusi = max($distribusiSubBagian->sum('tamus_count'), 1);

        // ===== Aktivitas Kunjungan 7 hari terakhir (untuk line chart) =====
        $aktivitasMingguan = collect(range(6, 0))->map(function ($i) use ($today) {
            $tanggal = $today->copy()->subDays($i);

            return [
                'label' => $tanggal->translatedFormat('d M'),
                'total' => Tamu::where('status_aktif', 'aktif')
                    ->whereDate('created_at', $tanggal)
                    ->count(),
            ];
        });

        // ===== Log Aktivitas / Kunjungan Terbaru =====
        $kunjunganTerbaru = Tamu::with(['subBagian', 'tujuan'])
            ->where('status_aktif', 'aktif')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'admins',
            'totalKunjungan',
            'persenBulanan',
            'kunjunganHariIni',
            'persenHarian',
            'totalSurvei',
            'distribusiSubBagian',
            'totalDistribusi',
            'aktivitasMingguan',
            'kunjunganTerbaru'
        ));
    }

    /**
     * Menghitung persentase perubahan dari nilai lama ke nilai baru.
     */
    private function hitungPersentase(int $baru, int $lama): float
    {
        if ($lama <= 0) {
            return $baru > 0 ? 100.0 : 0.0;
        }

        return round((($baru - $lama) / $lama) * 100, 1);
    }

    public function profile() {
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
        $user->save();

        ActivityLog::catat('Ubah Profil', 'Memperbarui informasi profil.');

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Informasi profil berhasil disimpan.');
    }

    public function UpdatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Masukkan password saat ini.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            ActivityLog::catat('Gagal Ubah Password', 'Percobaan ubah password gagal: password saat ini tidak sesuai.');

            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->with('error', 'Password saat ini tidak sesuai!')
                ->withInput()
                ->withFragment('password-section');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Catatan: deskripsi log SENGAJA tidak menyertakan password lama/baru
        // dalam bentuk apapun (termasuk hash-nya) demi keamanan.
        ActivityLog::catat('Ubah Password', 'Berhasil memperbarui password akun.');

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Password berhasil diperbarui.')
            ->withFragment('password-section');
    }

}