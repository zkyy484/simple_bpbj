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
            {{-- INFORMASI TAMU (KIRI) --}}
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

            {{-- INFORMASI PENANGANAN (KANAN) --}}
            <div class="space-y-4 md:border-l md:border-gray-100 md:pl-10">
                <div>
                    <p class="font-semibold text-gray-700 mb-1">Solusi</p>
                    <div class="w-full min-h-[90px] rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-gray-700"
                        x-text="selected.solusi || '-'"></div>
                </div>

                {{-- TAMPILAN DOKUMEN LAMPIRAN --}}
                <div>
                    <p class="font-semibold text-gray-700 mb-1">Dokumen Lampiran</p>
                    
                    <!-- Kondisi 1: Ada Dokumen -->
                    <template x-if="selected.dokumen_lampiran">
                        <div class="flex items-center gap-2 p-2 rounded-lg bg-blue-50 border border-blue-100 text-blue-900 text-xs">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <div class="flex-1 truncate">
                                <span class="font-semibold text-blue-700 truncate block" x-text="selected.dokumen_lampiran.split('/').pop()"></span>
                            </div>
                            <a :href="selected.dokumen_lampiran_url" target="_blank" 
                               class="px-2.5 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] transition shrink-0 flex items-center gap-1">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 123.223 3.867 0 0 0 20.037 8.118 8.118 0 0 1 3.223 3.867C3.867 2.036 5.871 1 8 1c2.129 0 4.133 1.036 5.777 2.867 1.831 2.036 2.036 4.133 1.036 5.777C13.867 11.964 11.863 13 9.734 13c-2.129 0-4.133-1.036-5.777-2.867Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Lihat
                            </a>
                        </div>
                    </template>

                    <!-- Kondisi 2: Tidak Ada Dokumen -->
                    <template x-if="!selected.dokumen_lampiran">
                        <div class="flex items-center gap-1.5 text-gray-400 italic text-xs py-1">
                            <span>Tidak ada dokumen yang dilampirkan.</span>
                        </div>
                    </template>
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