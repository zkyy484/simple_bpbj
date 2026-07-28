<!-- Modal Informasi Buku Tamu -->
<div x-show="openDetail" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <!-- Backdrop dengan efek blur & fade -->
    <div x-show="openDetail" 
         x-transition.opacity 
         @click="openDetail = false"
         class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <!-- Container Modal dengan Transisi Zoom In / Scale -->
    <div x-show="openDetail" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90" 
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90" 
         @click.outside="openDetail = false"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden max-h-[90vh] overflow-y-auto">

        <!-- Header Modal -->
        <div class="bg-white px-6 py-4 flex items-center justify-between border-b border-gray-200 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#173860]/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#173860]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 leading-tight">Informasi Buku Tamu</h2>
            </div>
            <button type="button" @click="openDetail = false" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form :action="updateUrl" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Kolom Kiri -->
                <div class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Kode Tiket</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 font-medium" x-text="selected.kode_tiket"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 font-medium" x-text="selected.nama_lengkap"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Email</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800" x-text="selected.email"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor HP</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800" x-text="selected.no_telp"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Sub Bagian</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800" x-text="selected.sub_bagian"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tujuan</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800" x-text="selected.tujuan"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Permasalahan</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 min-h-[70px]" x-text="selected.permasalahan"></p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Solusi</label>
                        <textarea name="solusi" x-model="selected.solusi"
                            class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm h-32 focus:ring-2 focus:ring-[#173860] focus:border-transparent outline-none resize-none transition"
                            placeholder="Tulis solusi di sini..."></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Status Tindak Lanjut</label>
                        <select name="status_tindak_lanjut" x-model="selected.status_tindak_lanjut"
                            class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none transition cursor-pointer">
                            <option value="belum_eskalasi">Belum Eskalasi</option>
                            <option value="eskalasi">Eskalasi</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3 sticky bottom-0">
                <button type="button" @click="openDetail = false"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold text-sm transition">
                    Tutup
                </button>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold text-sm transition flex items-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>