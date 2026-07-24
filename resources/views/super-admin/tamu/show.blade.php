<!-- Modal Informasi Buku Tamu -->
<div id="detailModal" 
     class="hidden fixed inset-0 bg-black/70 backdrop-blur-md items-center justify-center z-50">
    
    <div class="bg-white rounded-2xl w-full max-w-4xl mx-4 shadow-2xl overflow-hidden">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b px-6 py-4">
            <h3 class="text-2xl font-bold text-gray-800">Informasi Buku Tamu</h3>
            <button onclick="closeDetailModal()" 
                    class="w-8 h-8 flex items-center justify-center text-3xl leading-none text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-full transition">
                ×
            </button>
        </div>

        <div class="p-8 grid grid-cols-2 gap-8">
            
            <!-- Kolom Kiri -->
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Kode Tiket</label>
                    <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800">KNS-2025140702</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                    <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800">I Wayan Ketut Subandi</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Email</label>
                    <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800">wayan123@gmail.com</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nomor HP</label>
                    <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800">082310986234</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Sub Bagian</label>
                    <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800">LPSE</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tujuan</label>
                    <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800">Verifikasi Akun</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Permasalahan</label>
                    <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800 min-h-[100px]">
                        Mengajukan keluhan usaha melakukan verifikasi akun
                    </p>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Solusi</label>
                    <textarea class="w-full border border-gray-300 rounded-lg px-4 py-3 h-40 focus:ring-2 focus:ring-[#173860] focus:border-transparent outline-none resize-none" 
                              placeholder="Tulis solusi di sini..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Status Tidak Lanjut</label>
                    <select class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#173860] outline-none">
                        <option value="">Pilih Status</option>
                        <option value="belum_eskalasi" selected>Belum Eskalasi</option>
                        <option value="eskalasi">Eskalasi</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 border-t px-8 py-5 bg-gray-50">
            <button onclick="closeDetailModal()" 
                    class="px-8 py-3 border border-gray-300 rounded-xl font-semibold hover:bg-gray-100 transition">
                Tutup
            </button>
            <button class="px-8 py-3 bg-[#173860] text-white rounded-xl font-semibold hover:bg-[#102a48] transition">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>