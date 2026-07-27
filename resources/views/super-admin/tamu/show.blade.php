<!-- Modal Informasi Buku Tamu -->
<div x-show="openDetail" x-cloak
     class="fixed inset-0 bg-black/70 backdrop-blur-md items-center justify-center z-50 flex p-4">

    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto" @click.outside="openDetail = false">

        <!-- Header -->
        <div class="flex items-center justify-between border-b px-5 py-3">
            <h3 class="text-lg font-bold text-gray-800">Informasi Buku Tamu</h3>
            <button type="button" @click="openDetail = false"
                    class="w-7 h-7 flex items-center justify-center text-2xl leading-none text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-full transition">
                ×
            </button>
        </div>

        <form :action="updateUrl" method="POST">
            @csrf
            @method('PUT')

            <div class="p-5 grid grid-cols-2 gap-5">

                <!-- Kolom Kiri -->
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Kode Tiket</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800" x-text="selected.kode_tiket"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800" x-text="selected.nama_lengkap"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Alamat Email</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800" x-text="selected.email"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nomor HP</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800" x-text="selected.no_telp"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sub Bagian</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800" x-text="selected.sub_bagian"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tujuan</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800" x-text="selected.tujuan"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Permasalahan</label>
                        <p class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 min-h-[60px]" x-text="selected.permasalahan"></p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Solusi</label>
                        <textarea name="solusi" x-text="selected.solusi"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm h-24 focus:ring-2 focus:ring-[#173860] focus:border-transparent outline-none resize-none"
                                  placeholder="Tulis solusi di sini..."></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status Tidak Lanjut</label>
                        <select name="status_tindak_lanjut" x-model="selected.status_tindak_lanjut"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                            <option value="belum_eskalasi">Belum Eskalasi</option>
                            <option value="eskalasi">Eskalasi</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2 border-t px-5 py-3 bg-gray-50">
                <button type="button" @click="openDetail = false"
                        class="px-5 py-2 text-sm border border-gray-300 rounded-xl font-semibold hover:bg-gray-100 transition">
                    Tutup
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm bg-[#173860] text-white rounded-xl font-semibold hover:bg-[#102a48] transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>