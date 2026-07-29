<!-- MODAL: DETAIL (READ ONLY) -->
<div x-show="openDetail" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" @click="openDetail = false"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl p-8" @click.outside="openDetail = false">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-xl font-bold text-[#173860]">Informasi Buku Tamu</h3>
            <button @click="openDetail = false"
                class="w-7 h-7 flex items-center justify-center rounded bg-red-600 hover:bg-red-700 text-white text-sm font-bold">
                X
            </button>
        </div>

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
                    <p class="font-semibold text-gray-700 mb-1">Solusi</p>
                    <div class="w-full min-h-[100px] rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-gray-700"
                        x-text="selected.solusi"></div>
                </div>
                <div class="flex gap-2">
                    <span class="w-32 font-semibold text-gray-700 shrink-0">Ditangani Oleh</span>
                    <span>:</span>
                    <span class="text-gray-800 font-semibold" x-text="selected.pegawai_penanggung_jawab"></span>
                </div>
                <div>
                    <p class="font-semibold text-gray-700 mb-1">Status Tindak Lanjut</p>
                    <span class="inline-block px-3 py-1.5 text-xs font-bold rounded-full bg-gray-100 text-gray-700"
                        x-text="selected.status_tindak_lanjut"></span>
                </div>
                <p class="text-xs text-gray-400 italic pt-2">
                    Anda hanya dapat melihat detail tamu ini karena bukan yang menangani.
                </p>
            </div>
        </div>
    </div>
</div>