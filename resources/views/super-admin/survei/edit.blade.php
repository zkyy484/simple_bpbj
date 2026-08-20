{{-- super-admin/pertanyaan/edit.blade.php --}}
<div x-show="openEdit" 
     x-cloak 
     class="fixed inset-0 z-[999] flex items-center justify-center p-4"
     x-data="{
         tipe: 'rating',
         opsiList: [],
         
         init() {
             // Sinkronkan state lokal saat selectedItem di index berubah
             this.$watch('selectedItem', (value) => {
                 if (value) {
                     this.tipe = value.tipe_pertanyaan || 'rating';
                     this.opsiList = value.opsi ? JSON.parse(JSON.stringify(value.opsi)) : [];
                 }
             });
         },
         addOpsi() {
             this.opsiList.push({ id_opsi: null, opsi: '', nilai: 0 });
         },
         removeOpsi(index) {
             this.opsiList.splice(index, 1);
         },
         onTipeChange() {
             if (this.tipe === 'textarea') {
                 this.opsiList = [];
             }
         }
     }">

    <!-- Backdrop -->
    <div @click="openEdit = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <!-- Modal Card -->
    <div @click.outside="openEdit = false"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden max-h-[90vh] overflow-y-auto">

        <div class="bg-white px-6 py-5 flex items-center justify-between border-b sticky top-0 z-10">
            <h2 class="text-lg font-bold text-gray-900">Edit Pertanyaan</h2>
            <button type="button" @click="openEdit = false"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form :action="`{{ url('/super/pertanyaan') }}/${selectedItem.id}`" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit">
            <input type="hidden" name="id_pertanyaan" :value="selectedItem.id">

            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Pertanyaan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="pertanyaan" rows="2" x-model="selectedItem.pertanyaan"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860]"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Pertanyaan</label>
                    <select name="tipe_pertanyaan" x-model="tipe" @change="onTipeChange()"
                        :disabled="selectedItem.locked"
                        :class="selectedItem.locked ? 'bg-gray-100 cursor-not-allowed text-gray-500' : 'bg-white'"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860]">
                        <option value="rating">Rating</option>
                        <option value="pilihan_ganda">Pilihan Ganda</option>
                        <option value="textarea">Textarea</option>
                    </select>

                    <p x-show="selectedItem.locked" x-cloak
                        class="mt-2 text-xs text-amber-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tipe tidak bisa diubah karena pertanyaan ini sudah memiliki respon dari pengguna.
                    </p>

                    <input type="hidden" name="tipe_pertanyaan" x-model="tipe" x-show="selectedItem.locked">
                </div>

                <div x-show="tipe === 'rating' || tipe === 'pilihan_ganda'"
                    class="bg-gray-50/70 p-4 rounded-xl border border-gray-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#173860]">Opsi Jawaban</h3>
                        <button type="button" @click="addOpsi()"
                            class="text-xs bg-[#173860] text-white px-3 py-1.5 rounded-lg hover:bg-[#102a48]">+ Tambah Opsi</button>
                    </div>
                    <template x-for="(o, index) in opsiList" :key="index">
                        <div class="flex gap-2 items-center">
                            <input type="hidden" :name="`opsi[${index}][id_opsi]`" x-model="o.id_opsi">
                            <input type="text" :name="`opsi[${index}][opsi]`" x-model="o.opsi"
                                class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#173860]">
                            <input type="number" x-show="tipe === 'rating'" :name="`opsi[${index}][nilai]`"
                                x-model="o.nilai"
                                class="w-24 rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#173860]">
                            <button type="button" @click="removeOpsi(index)"
                                class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-lg">&times;</button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-gray-50 border-t px-6 py-4 flex justify-end gap-3 sticky bottom-0 z-10">
                <button type="button" @click="openEdit = false"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold">Batal</button>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold">Update Data</button>
            </div>
        </form>
    </div>
</div>