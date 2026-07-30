<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profile - Buku Tamu Digital</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#dbe5ff] font-sans text-gray-800 min-h-screen flex flex-col justify-between"
    data-password-error="{{ $errors->has('current_password') || old('current_password') ? '1' : '0' }}">

    <!-- Top Action Bar -->
    <div class="bg-white px-8 py-4 flex justify-between items-center shadow-sm sticky top-0 z-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('super.dashboard') }}" class="p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                <i data-lucide="arrow-left" class="w-6 h-6"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Pengaturan Profile</h2>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="openPasswordModal()"
                class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <i data-lucide="lock" class="w-4 h-4"></i> Ubah Password
            </button>
            <button type="button" onclick="window.history.back()"
                class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" form="profile-form"
                class="px-6 py-2 bg-[#1b3a6b] text-white font-semibold rounded-lg hover:bg-[#152e55] transition shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </div>

    <main class="max-w-7xl w-full mx-auto p-6 md:p-8">

        {{-- ============================ --}}
        {{-- FORM 1: INFO PROFIL --}}
        {{-- ============================ --}}
        <form id="profile-form" action="{{ route('super.profile.update') }}" method="POST"
            class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            @csrf
            @method('PUT')
            <input type="hidden" name="paraf" id="paraf-input">

            <!-- Left: Summary Card -->
            <div class="lg:col-span-4 bg-white rounded-xl p-6 shadow-sm flex flex-col items-center text-center">
                <div
                    class="w-32 h-32 bg-gray-200 rounded-full overflow-hidden flex items-center justify-center border-2 border-gray-100 shadow-inner mb-6">
                    <i data-lucide="user" class="w-16 h-16 text-gray-400"></i>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $user->nama_lengkap }}</h3>
                <p class="text-sm text-gray-600 mb-6">NIP. {{ $user->nip }}</p>

                <div class="w-full border-t border-gray-200 pt-6 flex flex-col items-center">
                    <span
                        class="{{ $user->status === 'aktif' ? 'bg-[#52c41a]' : 'bg-gray-400' }} text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-6">
                        {{ $user->status === 'aktif' ? 'Pegawai Aktif' : 'Nonaktif' }}
                    </span>

                    <div class="w-full space-y-3 text-left text-xs text-gray-600 px-2">
                        <div class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-500 shrink-0"></i>
                            <span class="truncate">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-4 h-4 text-gray-500 shrink-0"></i>
                            <span>{{ $user->no_telepon ?? '-' }}</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-500 shrink-0 mt-0.5"></i>
                            <span class="leading-relaxed">{{ $user->alamat ?? '-' }}</span>
                        </div>
                        @if ($user->jabatan)
                            <div class="flex items-center gap-3">
                                <i data-lucide="briefcase" class="w-4 h-4 text-gray-500 shrink-0"></i>
                                <span>{{ $user->jabatan }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Form Info -->
            <div class="lg:col-span-8 space-y-6 flex flex-col">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4 flex items-center gap-3">
                        <i data-lucide="user" class="w-5 h-5 text-gray-800"></i>
                        <h3 class="text-base font-bold text-gray-900">Informasi Personal</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" maxlength="50"
                                value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Username</label>
                            <input type="text" name="username" maxlength="50"
                                value="{{ old('username', $user->username) }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" maxlength="50"
                                value="{{ old('email', $user->email) }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Nomor HP</label>
                            <input type="text" name="no_telepon" maxlength="20"
                                value="{{ old('no_telepon', $user->no_telepon) }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">NIP</label>
                            <input type="text" name="nip" maxlength="30" value="{{ old('nip', $user->nip) }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- ============================ --}}
        {{-- SECTION: PARAF / TANDA TANGAN --}}
        {{-- ============================ --}}
        <div id="paraf-section" class="mt-6 bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i data-lucide="pen-tool" class="w-5 h-5 text-gray-800"></i>
                    <h3 class="text-base font-bold text-gray-900">Paraf / Tanda Tangan Digital</h3>
                </div>
                <span class="text-xs text-gray-500 font-medium">Digunakan untuk validasi dokumen</span>
            </div>

            <div class="p-6">
                @if ($user->paraf)
                    {{-- Tampilkan paraf yang sudah tersimpan --}}
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Paraf Tersimpan</label>
                        <div class="border border-gray-200 rounded-lg p-4 bg-[#f9fafb] inline-block">
                            <img src="{{ $user->paraf }}" alt="Paraf" class="h-24 object-contain">
                        </div>
                        <form action="{{ route('super.profile.paraf.delete') }}" method="POST" class="inline-block ml-3"
                            onsubmit="return confirm('Yakin ingin menghapus paraf ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-xs text-red-600 hover:text-red-800 font-semibold mt-2 inline-flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Paraf
                            </button>
                        </form>
                    </div>
                @endif

                <label class="block text-xs font-semibold text-gray-700 mb-2">
                    {{ $user->paraf ? 'Ganti Paraf (gambar di area bawah)' : 'Buat Paraf (gambar di area bawah)' }}
                </label>

                <div class="border-2 border-dashed border-gray-300 rounded-lg bg-[#f9fafb] relative"
                    style="touch-action: none;">
                    <canvas id="signature-pad" class="w-full rounded-lg" height="180"></canvas>
                    <span id="signature-placeholder"
                        class="absolute inset-0 flex items-center justify-center text-gray-400 text-sm pointer-events-none">
                        Tanda tangani di sini
                    </span>
                </div>

                <div class="flex justify-between items-center mt-3">
                    <button type="button" onclick="clearSignature()"
                        class="px-4 py-2 text-xs font-semibold text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center gap-1.5">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Bersihkan Canvas
                    </button>
                    <p class="text-[11px] text-gray-500">Paraf akan otomatis tersimpan saat Anda klik
                        <b>"Simpan Perubahan"</b> di atas.</p>
                </div>
            </div>
        </div>
    </main>

    {{-- ============================ --}}
    {{-- MODAL: UBAH PASSWORD --}}
    {{-- ============================ --}}
    <div id="password-modal-overlay"
        class="hidden fixed inset-0 bg-black/50 z-40 items-center justify-center p-4">
        <div id="password-modal-box"
            class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

            <form action="{{ route('super.profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <i data-lucide="lock" class="w-5 h-5 text-gray-800"></i>
                        <h3 class="text-base font-bold text-gray-900">Ubah Password</h3>
                    </div>
                    <button type="button" onclick="closePasswordModal()"
                        class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <p class="text-xs text-gray-500 font-medium -mt-1">Wajib diisi semua jika ingin mengganti password</p>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Password Saat Ini</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password"
                                placeholder="Masukkan password lama untuk konfirmasi"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            <button type="button" onclick="togglePassword('current_password', 'icon-current')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-800">
                                <i id="icon-current" data-lucide="eye" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Password Baru</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" placeholder="Password baru"
                                    class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                                <button type="button" onclick="togglePassword('password', 'icon-new')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-800">
                                    <i id="icon-new" data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Konfirmasi Password
                                Baru</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Ulangi password baru"
                                    class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                                <button type="button"
                                    onclick="togglePassword('password_confirmation', 'icon-confirm')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-800">
                                    <i id="icon-confirm" data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#f0f2f5] p-4 rounded-xl flex items-start gap-3.5 text-gray-800 mt-2">
                        <div class="p-1 rounded-full border border-gray-800 shrink-0 mt-0.5">
                            <i data-lucide="info" class="w-4 h-4 text-gray-800"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-gray-900">Catatan Keamanan</h4>
                            <p class="text-[11px] text-gray-600 mt-0.5 leading-relaxed">
                                Isi ketiga kolom di atas jika ingin mengganti password. Password baru minimal 8
                                karakter.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 sticky bottom-0 bg-white rounded-b-2xl">
                    <button type="button" onclick="closePasswordModal()"
                        class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#1b3a6b] text-white font-semibold rounded-lg hover:bg-[#152e55] transition shadow-sm">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#0f2545] text-white pt-8 pb-4 px-8 mt-12">
        <div
            class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-6 pb-6 border-b border-gray-700 text-xs">
            <div class="flex items-start gap-4 max-w-lg">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Denpasar" class="h-12 w-auto shrink-0">
                <div>
                    <h4 class="font-bold text-sm mb-1">Bagian Pengadaan Barang dan Jasa</h4>
                    <p class="text-gray-300 leading-relaxed">
                        Sekretariat Daerah Kota Denpasar berkomitmen memberikan pelayanan konsultasi pengadaan
                        barang/jasa pemerintah secara profesional, transparan, dan akuntabel sesuai peraturan yang
                        berlaku.
                    </p>
                </div>
            </div>
            <div class="text-left md:text-right text-gray-300 space-y-1">
                <p class="font-semibold text-white">Kantor Walikota Denpasar</p>
                <p>Jl. Gajah Mada No. 1, Denpasar, Bali 80232</p>
                <p>Jam Layanan: Senin–Jumat, 08.00–16.00 WITA</p>
            </div>
        </div>
        <div class="text-center text-[11px] text-gray-400 pt-4">
            © {{ date('Y') }} Pemerintah Kota Denpasar — Sistem Buku Tamu Digital Pengadaan Barang dan Jasa
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        lucide.createIcons();

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = "password";
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        // ===== Modal Ubah Password =====
        const passwordOverlay = document.getElementById('password-modal-overlay');

        function openPasswordModal() {
            passwordOverlay.classList.remove('hidden');
            passwordOverlay.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closePasswordModal() {
            passwordOverlay.classList.add('hidden');
            passwordOverlay.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Tutup modal jika klik area gelap di luar box
        passwordOverlay.addEventListener('click', function (e) {
            if (e.target === passwordOverlay) {
                closePasswordModal();
            }
        });

        // Tutup modal dengan tombol Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !passwordOverlay.classList.contains('hidden')) {
                closePasswordModal();
            }
        });

        // Jika validasi password gagal di server, otomatis buka kembali modal
        if (document.body.dataset.passwordError === '1') {
            openPasswordModal();
        }

        // ===== Signature Pad (Paraf) =====
        const canvas = document.getElementById('signature-pad');
        const placeholder = document.getElementById('signature-placeholder');
        let signaturePad;

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            if (signaturePad) signaturePad.clear();
        }

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255,255,255,0)',
            penColor: '#1b3a6b'
        });

        signaturePad.addEventListener('beginStroke', () => {
            placeholder.style.display = 'none';
        });

        function clearSignature() {
            signaturePad.clear();
            placeholder.style.display = 'flex';
            document.getElementById('paraf-input').value = '';
        }

        // Ambil hasil tanda tangan sebagai base64 sebelum form Info Profil disubmit
        document.getElementById('profile-form').addEventListener('submit', function () {
            if (!signaturePad.isEmpty()) {
                document.getElementById('paraf-input').value = signaturePad.toDataURL('image/png');
            }
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl',
                    }
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#173860',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-5 py-2.5 font-semibold'
                    }
                });
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>