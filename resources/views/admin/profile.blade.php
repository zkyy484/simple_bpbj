<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengaturan Profile - Buku Tamu Digital</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#dbe5ff] font-sans text-gray-800 min-h-screen flex flex-col justify-between">

    <!-- Top Action Bar -->
    <div class="bg-white px-8 py-4 flex justify-between items-center shadow-sm sticky top-0 z-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                <i data-lucide="arrow-left" class="w-6 h-6"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Pengaturan Profile</h2>
        </div>
        <div class="flex gap-3">
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
        <form id="profile-form" action="{{ route('admin.profile.update') }}" method="POST"
            class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            @csrf
            @method('PUT')

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
                            @error('nama_lengkap')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Username</label>
                            <input type="text" name="username" maxlength="50"
                                value="{{ old('username', $user->username) }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            @error('username')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" maxlength="50"
                                value="{{ old('email', $user->email) }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Nomor HP</label>
                            <input type="text" name="no_telepon" maxlength="20"
                                value="{{ old('no_telepon', $user->no_telepon) }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            @error('no_telepon')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">NIP</label>
                            <input type="text" name="nip" maxlength="30" value="{{ old('nip', $user->nip) }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                            @error('nip')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- ============================ --}}
        {{-- FORM 2: UBAH PASSWORD --}}
        {{-- ============================ --}}
        <div id="password-section" class="mt-6">
            <form action="{{ route('admin.update.password') }}" method="POST"
                class="bg-white rounded-2xl shadow-sm overflow-hidden">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="lock" class="w-5 h-5 text-gray-800"></i>
                        <h3 class="text-base font-bold text-gray-900">Ubah Password</h3>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Wajib diisi semua jika ingin mengganti
                        password</span>
                </div>
                <div class="p-6 space-y-5">
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
                        @error('current_password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
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
                            @error('password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
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

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-[#1b3a6b] text-white font-semibold rounded-lg hover:bg-[#152e55] transition shadow-sm">
                            Simpan Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</body>

</html>