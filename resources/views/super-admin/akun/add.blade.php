<!-- Form Modal / Card Tambah Pegawai -->
<div class="max-w-4xl mx-auto bg-[#efefef] p-6 rounded-xl shadow-lg border border-gray-200 font-sans">

    <!-- Header Modal -->
    <div class="flex items-center justify-between pb-4 border-b border-gray-300 mb-6">
        <h2 class="text-xl font-bold text-gray-900">Tambah Pegawai</h2>
        <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-bold w-7 h-7 rounded flex items-center justify-center transition">
            X
        </button>
    </div>

    <form action="{{ route('index.akun') }}" method="POST">
        @csrf

        <!-- Section 1: Biodata Pegawai -->
        <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-100">
            <h3 class="text-base font-bold text-[#173860] mb-5">Biodata Pegawai</h3>

            <div class="space-y-4">
                <!-- Baris 1: Nama Lengkap & NIP -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required
                            class="w-full bg-[#e8e8e8] border-none rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#173860] transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">NIP</label>
                        <input type="text" name="nip" placeholder="Masukkan NIP" required
                            class="w-full bg-[#e8e8e8] border-none rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#173860] transition">
                    </div>
                </div>

                <!-- Baris 2: Alamat Email & Nomor Telepon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Alamat Email</label>
                        <input type="email" name="email" placeholder="contoh@denpasarkota.go.id" required
                            class="w-full bg-[#e8e8e8] border-none rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#173860] transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nomor Telepon</label>
                        <input type="tel" name="no_telp" placeholder="08xxxxxxxxxx" required
                            class="w-full bg-[#e8e8e8] border-none rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#173860] transition">
                    </div>
                </div>

                <!-- Baris 3: Sub Bagian -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Sub Bagian</label>
                    <select name="sub_bagian_id" required
                        class="w-full bg-[#e8e8e8] border-none rounded-md px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#173860] transition cursor-pointer">
                        <option value="" disabled selected>Pilih Sub Bagian</option>
                        <option value="1">Bagian Pengadaan Barang dan Jasa</option>
                        <option value="2">Sub Bagian Pembinaan & Advokasi</option>
                        <option value="3">Sub Bagian Pengelolaan PBJ</option>
                    </select>
                </div>

                <!-- Baris 4: Alamat Tinggal -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Alamat Tinggal</label>
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap tinggal" required
                        class="w-full bg-[#e8e8e8] border-none rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#173860] transition resize-none"></textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: Akun Login Sistem -->
        <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-100">
            <h3 class="text-base font-bold text-[#173860] mb-5">Akun Login Sistem</h3>

            <div class="space-y-4">
                <!-- Baris 1: Username & Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Username</label>
                        <input type="text" name="username" placeholder="Masukkan username" required
                            class="w-full bg-[#e8e8e8] border-none rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#173860] transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Password</label>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full bg-[#e8e8e8] border-none rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#173860] transition">
                    </div>
                </div>

                <!-- Baris 2: Hak Akses (Role) -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Hak Akses (Role)</label>
                    <select name="role" required
                        class="w-full bg-[#e8e8e8] border-none rounded-md px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#173860] transition cursor-pointer">
                        <option value="" disabled selected>Pilih Hak Akses / Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Super Admin">Super Admin</option>
                        <option value="Pegawai">Pegawai</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit"
                class="bg-[#102a48] hover:bg-[#173860] text-white font-semibold px-6 py-3 rounded-lg shadow transition duration-200">
                Simpan Data Pegawai
            </button>
        </div>

    </form>
</div>