<div x-show="openCreate" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <!-- Overlay -->
    <div x-show="openCreate" x-transition.opacity @click="openCreate = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal -->
    <div x-show="openCreate" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.outside="openCreate = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden max-h-[90vh] overflow-y-auto">

        <!-- Header -->
        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-200 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#173860]/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#173860]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">
                        Tambah Pegawai
                    </h2>
                </div>
            </div>

            <!-- Tombol Close -->
            <button type="button" @click="openCreate = false" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <form action="{{ route('akun.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">

                <!-- SECTION 1: DATA DIRI & PEGAWAI -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Data Diri & Kepegawaian</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] @error('nama_lengkap') border-red-400 @else border-gray-300 @enderror">
                            @error('nama_lengkap')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                NIP <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP"
                                class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] @error('nip') border-red-400 @else border-gray-300 @enderror">
                            @error('nip')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="contoh@denpasarkota.go.id"
                                class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] @error('email') border-red-400 @else border-gray-300 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="no_telepon" value="{{ old('no_telepon') }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] @error('no_telepon') border-red-400 @else border-gray-300 @enderror">
                            @error('no_telepon')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Sub Bagian -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Sub Bagian <span class="text-red-500">*</span>
                        </label>
                        <select name="id_sub_bagian"
                            class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] cursor-pointer @error('id_sub_bagian') border-red-400 @else border-gray-300 @enderror">
                            <option value="" disabled {{ old('id_sub_bagian') ? '' : 'selected' }}>Pilih Sub Bagian</option>
                            @foreach ($subBagians as $sub)
                                <option value="{{ $sub->id_sub_bagian }}"
                                    {{ old('id_sub_bagian') == $sub->id_sub_bagian ? 'selected' : '' }}>
                                    {{ $sub->nama_sub_bagian }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_sub_bagian')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat Tinggal <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" rows="2" placeholder="Masukkan alamat lengkap tinggal"
                            class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] resize-none @error('alamat') border-red-400 @else border-gray-300 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SECTION 2: INFORMASI AKUN LOGIN (BAGIAN BAWAH) -->
                <div class="pt-4 border-t border-gray-200 space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-[#173860]"></div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#173860]">Informasi Akun Login</h3>
                    </div>

                    <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200/80 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Username -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    placeholder="Masukkan username"
                                    class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] bg-white @error('username') border-red-400 @else border-gray-300 @enderror">
                                @error('username')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" placeholder="Masukkan password"
                                    class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] bg-white @error('password') border-red-400 @else border-gray-300 @enderror">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Role Akses <span class="text-red-500">*</span>
                            </label>
                            <select name="role"
                                class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] cursor-pointer bg-white @error('role') border-red-400 @else border-gray-300 @enderror">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role Akses</option>
                                <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan kecil -->
                        <div class="flex items-start gap-2 pt-1 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0 text-[#173860]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs leading-relaxed">
                                Pastikan <strong>Username</strong> unik, <strong>Password</strong> aman, dan <strong>Role</strong> sesuai hak akses pegawai.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3 sticky bottom-0 z-10">
                <button type="button" @click="openCreate = false"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>