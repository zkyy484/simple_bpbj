<!-- MODAL: EDIT JADWAL DINAS -->
<div x-show="openEdit" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">
    <!-- Overlay -->
    <div x-show="openEdit" x-transition.opacity @click="if (!loading) openEdit = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <!-- Modal Card -->
    <div x-show="openEdit" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.outside="if (!loading) openEdit = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden max-h-[90vh] overflow-y-auto">

        <!-- Header Modal Sticky -->
        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-200 sticky top-0 z-10">
            <h2 class="text-lg font-bold text-gray-900 leading-tight">Edit Jadwal Dinas</h2>
            <button type="button" @click="openEdit = false" class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center">✕</button>
        </div>

        <!-- Body Form Edit -->
        <form :action="`{{ url('super-admin/jadwal-dinas') }}/${selectedJadwal.id}`" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit">
            <input type="hidden" name="id_jadwal_dinas" :value="selectedJadwal.id">

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Bidang/Sekretariat</label>
                    <input type="text" name="bidang_sekretariat" x-model="selectedJadwal.bidang_sekretariat"
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Surat Dari *</label>
                    <input type="text" name="surat_dari" x-model="selectedJadwal.surat_dari" required
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Acara *</label>
                    <textarea name="acara" rows="2" x-model="selectedJadwal.acara" required
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Hari/Tanggal *</label>
                        <input type="date" name="hari_tanggal" x-model="selectedJadwal.hari_tanggal" required
                            class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Waktu</label>
                        <input type="time" name="waktu" x-model="selectedJadwal.waktu"
                            class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tempat/Zoom</label>
                    <input type="text" name="tempat_zoom" x-model="selectedJadwal.tempat_zoom"
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Yang Hadir</label>
                    <div class="bg-gray-50 rounded-xl border p-3 max-h-40 overflow-y-auto space-y-2">
                        @foreach($pegawaiList as $user)
                            <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer">
                                <input type="checkbox" name="pegawai_ids[]" value="{{ $user->id_user }}"
                                    :checked="selectedJadwal.pegawai_ids && selectedJadwal.pegawai_ids.includes({{ $user->id_user }})"
                                    class="w-4 h-4 rounded border-gray-300 text-[#173860] focus:ring-[#173860]">
                                <span>{{ $user->nama_lengkap }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" x-model="selectedJadwal.keterangan"
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none resize-none"></textarea>
                </div>
            </div>

            <!-- Footer Sticky -->
            <div class="bg-gray-50 border-t px-6 py-4 flex justify-end gap-3 sticky bottom-0 z-10">
                <button type="button" @click="openEdit = false" class="px-5 py-2 rounded-xl border">Batal</button>
                <button type="submit" class="px-6 py-2 bg-[#173860] text-white rounded-xl">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>