<!-- MODAL: TINDAK LANJUTI (EDITABLE) -->
<div x-show="openTindakLanjut" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" @click="openTindakLanjut = false"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl p-8"
        @click.outside="openTindakLanjut = false">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-xl font-bold text-[#173860]">Informasi Buku Tamu</h3>
            <button @click="openTindakLanjut = false"
                class="w-7 h-7 flex items-center justify-center rounded bg-red-600 hover:bg-red-700 text-white text-sm font-bold">
                X
            </button>
        </div>

        <form :action="updateUrl" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-4 text-sm">
                <div class="space-y-4">
                    <div class="flex gap-2">
                        <span class="w-32 font-semibold text-gray-700">Kode Tiket</span>
                        <span>:</span>
                        <span class="text-gray-800" x-text="selected.kode_tiket"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-32 font-semibold text-gray-700">Nama Lengkap</span>
                        <span>:</span>
                        <span class="text-gray-800" x-text="selected.nama_lengkap"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-32 font-semibold text-gray-700">Alamat Email</span>
                        <span>:</span>
                        <span class="text-gray-800" x-text="selected.email"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-32 font-semibold text-gray-700">Nomor HP</span>
                        <span>:</span>
                        <span class="text-gray-800" x-text="selected.no_telp"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-32 font-semibold text-gray-700">Sub Bagian</span>
                        <span>:</span>
                        <span class="text-gray-800" x-text="selected.sub_bagian"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-32 font-semibold text-gray-700">Tujuan</span>
                        <span>:</span>
                        <span class="text-gray-800" x-text="selected.tujuan"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-32 font-semibold text-gray-700 shrink-0">Permasalahan</span>
                        <span>:</span>
                        <span class="text-gray-800" x-text="selected.permasalahan"></span>
                    </div>
                </div>

                <div class="space-y-4 md:border-l md:border-gray-100 md:pl-10">
                    <div>
                        <label class="font-semibold text-gray-700 mb-1 block">Solusi</label>
                        <textarea name="solusi" x-model="selected.solusi" rows="3"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#173860] outline-none"
                            placeholder="Tuliskan solusi yang diberikan..."></textarea>
                    </div>

                    {{-- Kirim Email --}}
                    <button type="submit" :formaction="emailUrl" formmethod="POST"
                        class="px-4 py-2 rounded-lg text-xs font-bold bg-[#173860] hover:bg-[#102a48] text-white transition">
                        Kirim Email
                    </button>

                    <div class="flex gap-2">
                        <span class="w-32 font-semibold text-gray-700">Ditangani Oleh</span>
                        <span>:</span>
                        <span class="text-gray-800 font-semibold"
                            x-text="selected.pegawai_penanggung_jawab"></span>
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-1 block">Status Tindak Lanjut</label>
                        <select name="status_tindak_lanjut" x-model="selected.status_tindak_lanjut"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                            <option value="belum_eskalasi">Belum Eskalasi</option>
                            <option value="eskalasi">Eskalasi</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 mt-6">
                <button type="button" @click="openTindakLanjut = false"
                    class="px-5 py-2.5 rounded-full text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-800 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2.5 rounded-full text-sm font-bold bg-[#173860] hover:bg-[#102a48] text-white transition shadow-sm">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>