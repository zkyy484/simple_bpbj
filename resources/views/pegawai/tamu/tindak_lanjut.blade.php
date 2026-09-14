<!-- MODAL: TINDAK LANJUTI (EDITABLE) -->
<div x-data="{ isSaving: false, isSendingEmail: false }" x-show="openTindakLanjut" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-black/40" @click="if(!isSaving && !isSendingEmail) openTindakLanjut = false"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl p-8"
        @click.outside="if(!isSaving && !isSendingEmail) openTindakLanjut = false">

        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-xl font-bold text-[#173860]">Informasi Buku Tamu</h3>
            <button type="button" @click="openTindakLanjut = false" :disabled="isSaving || isSendingEmail"
                class="w-7 h-7 flex items-center justify-center rounded bg-red-600 hover:bg-red-700 text-white text-sm font-bold disabled:opacity-50 disabled:cursor-not-allowed">
                X
            </button>
        </div>

        {{-- TAMBAHKAN enctype="multipart/form-data" KARENA ADA UPLOAD FILE --}}
        <form x-ref="tindakLanjutForm" :action="updateUrl" method="POST" enctype="multipart/form-data"
            @submit="if(!isSendingEmail) isSaving = true">
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
                        <label class="font-semibold text-gray-700 mb-1 block">
                            Solusi <span class="text-red-500">*</span>
                        </label>
                        {{-- Solusi bersifat WAJIB (required) --}}
                        <textarea name="solusi" x-model="selected.solusi" rows="3" required
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#173860] outline-none"
                            placeholder="Tuliskan solusi yang diberikan..."></textarea>
                    </div>

                    {{-- FIELD UPLOAD DOKUMEN (OPSIONAL) --}}
                    <div>
                        <label class="font-semibold text-gray-700 mb-1 block">
                            Dokumen Lampiran <span class="text-gray-400 font-normal text-xs">(Opsional)</span>
                        </label>

                        <input type="file" name="dokumen_lampiran" accept=".pdf,.doc,.docx,.xls,.xlsx"
                            class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#173860]/10 file:text-[#173860] hover:file:bg-[#173860]/20 border border-gray-200 rounded-lg cursor-pointer focus:outline-none">
                        <p class="text-[11px] text-gray-400 mt-1">Format: PDF, Word, Excel (Maks. 5MB)</p>

                        <!-- STATUS DOKUMEN TERUNGGAH / BELUM TERUNGGAH -->
                        <div class="mt-2 text-xs">
                            <!-- KONDISI 1: JIKA DOKUMEN SUDAH TERUPLOAD -->
                            <template x-if="selected.dokumen_lampiran">
                                <div
                                    class="flex items-center gap-2 p-2 rounded-lg bg-blue-50 border border-blue-100 text-blue-900">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <div class="flex-1 truncate">
                                        <span class="font-medium block text-gray-700">Dokumen Terpasang:</span>
                                        <!-- Mengambil dan menampilkan nama filenya saja dari path -->
                                        <span class="font-semibold text-blue-700 truncate block"
                                            x-text="selected.dokumen_lampiran.split('/').pop()"></span>
                                    </div>
                                    <a :href="selected.dokumen_lampiran_url" target="_blank"
                                        class="px-2.5 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] transition shrink-0">
                                        Lihat
                                    </a>
                                </div>
                            </template>

                            <!-- KONDISI 2: JIKA BELUM ADA DOKUMEN -->
                            <template x-if="!selected.dokumen_lampiran">
                                <div class="flex items-center gap-1.5 text-gray-400 italic">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    <span>Belum ada dokumen yang diunggah.</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Kirim Email --}}
                    <div>
                        <button type="button"
                            @click="
                                isSendingEmail = true; 
                                $refs.tindakLanjutForm.action = emailUrl; 
                                $nextTick(() => { $refs.tindakLanjutForm.submit(); });
                            "
                            :disabled="isSendingEmail || isSaving"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold bg-[#173860] hover:bg-[#102a48] text-white transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="isSendingEmail" class="animate-spin h-3.5 w-3.5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span x-text="isSendingEmail ? 'Mengirim...' : 'Kirim Email'"></span>
                        </button>
                    </div>

                    <div class="flex gap-2">
                        <span class="w-32 font-semibold text-gray-700">Ditangani Oleh</span>
                        <span>:</span>
                        <span class="text-gray-800 font-semibold" x-text="selected.pegawai_penanggung_jawab"></span>
                    </div>

                    {{-- Select status_tindak_lanjut dihapus karena otomatis diatur menjadi 'selesai' di backend --}}
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 mt-6">
                <button type="button" @click="openTindakLanjut = false" :disabled="isSaving || isSendingEmail"
                    class="px-5 py-2.5 rounded-full text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal
                </button>
                <button type="submit" :disabled="isSaving || isSendingEmail"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-bold bg-[#173860] hover:bg-[#102a48] text-white transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg x-show="isSaving" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Data'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
