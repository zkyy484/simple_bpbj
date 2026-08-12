<!-- MODAL: EDIT JADWAL DINAS -->
<div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Overlay Backdrop -->
    <div x-show="openEdit" x-transition.opacity @click="if (!loading) openEdit = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal Card dengan Animasi Zoom -->
    <div x-show="openEdit" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.outside="if (!loading) openEdit = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Header Modal Sticky -->
        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-200 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#173860]/10 flex items-center justify-center">
                    <i data-lucide="square-pen" class="w-5 h-5 text-[#173860]"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 leading-tight">
                        Edit Jadwal Dinas / Delegasi
                    </h3>
                </div>
            </div>

            <!-- Tombol Close -->
            <button type="button" @click="openEdit = false" :disabled="loading" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body Form -->
        <form :action="`{{ url('/super/jadwal-dinas/update/') }}/${selectedJadwal.id}`" method="POST"
            x-data="{ loading: false }" @submit="loading = true"
            class="space-y-4 overflow-y-auto p-6 flex-1">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit">
            <input type="hidden" name="id_jadwal_dinas" :value="selectedJadwal.id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Agenda</label>
                    <input type="text" name="nomor_agenda" x-model="selectedJadwal.nomor_agenda"
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Surat Dari <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="surat_dari" x-model="selectedJadwal.surat_dari" required
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Nomor Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor_surat" x-model="selectedJadwal.nomor_surat" required
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_surat" x-model="selectedJadwal.tanggal_surat" required
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Perihal <span class="text-red-500">*</span>
                </label>
                <textarea name="perihal" rows="2" x-model="selectedJadwal.perihal" required
                    class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Tanggal Pelaksanaan Kegiatan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_kegiatan" x-model="selectedJadwal.tanggal_kegiatan" required
                    class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pegawai Yang Ditugaskan</label>
                <div class="bg-[#f0f2f5] rounded-lg p-3 max-h-36 overflow-y-auto space-y-1.5 border border-gray-200/50">
                    @foreach ($users as $user)
                        <label
                            class="flex items-center gap-2 text-xs text-gray-700 hover:bg-gray-200/50 p-1 rounded cursor-pointer transition">
                            <input type="checkbox" name="pegawai_ids[]" value="{{ $user->id_user }}"
                                :checked="selectedJadwal.pegawai_ids && selectedJadwal.pegawai_ids.includes({{ $user->id_user }})"
                                class="rounded border-gray-300 text-[#173860] focus:ring-[#173860]">
                            <span>{{ $user->nama_lengkap }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="2" x-model="selectedJadwal.keterangan"
                    class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none resize-none"></textarea>
            </div>

            <!-- Footer Sticky -->
            <div class="bg-gray-50 border-t border-gray-200 -mx-6 -mb-6 px-6 py-4 flex justify-end gap-3 sticky bottom-0 z-10 mt-6">
                <button type="button" @click="openEdit = false" :disabled="loading"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold transition text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal
                </button>
                
                <button type="submit" :disabled="loading"
                    class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold transition flex items-center gap-2 text-sm disabled:opacity-75 disabled:cursor-not-allowed">
                    <!-- Icon Spinner Loading -->
                    <svg x-show="loading" x-cloak class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <!-- Icon Check saat Normal -->
                    <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>

                    <!-- Text Tombol Dinamis -->
                    <span x-text="loading ? 'Memperbarui...' : 'Perbarui Data'"></span>
                </button>
            </div>
        </form>
    </div>
</div>