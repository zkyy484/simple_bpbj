{{-- super-admin/pertanyaan/create.blade.php --}}
<div x-show="openCreate" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4"
    x-data="opsiBuilder('{{ old('tipe_pertanyaan') }}', {{ old('opsi') ? Js::from(old('opsi')) : 'null' }})">

    <div x-show="openCreate" x-transition.opacity @click="openCreate = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div x-show="openCreate" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        @click.outside="openCreate = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden max-h-[90vh] overflow-y-auto">

        <div class="bg-white px-6 py-5 flex items-center justify-between border-b sticky top-0 z-10">
            <h2 class="text-lg font-bold text-gray-900">Tambah Pertanyaan</h2>
            <button type="button" @click="openCreate = false"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('pertanyaan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="form_type" value="create">

            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="pertanyaan" rows="2"
                        class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860] focus:border-[#173860] @error('pertanyaan') border-red-400 @else border-gray-300 @enderror">{{ old('pertanyaan') }}</textarea>
                    @error('pertanyaan')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Pertanyaan <span class="text-red-500">*</span></label>
                        <select name="tipe_pertanyaan" x-model="tipe" @change="onTipeChange()"
                            class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860] @error('tipe_pertanyaan') border-red-400 @else border-gray-300 @enderror">
                            <option value="" disabled>-- Pilih Tipe --</option>
                            <option value="rating">Rating</option>
                            <option value="pilihan_ganda">Pilihan Ganda</option>
                            <option value="textarea">Textarea</option>
                        </select>
                        @error('tipe_pertanyaan')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Urutan <span class="text-red-500">*</span></label>
                        <input type="number" name="urutan" value="{{ old('urutan') }}"
                            class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860] @error('urutan') border-red-400 @else border-gray-300 @enderror">
                        @error('urutan')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Builder opsi: rating & pilihan_ganda --}}
                <div x-show="tipe === 'rating' || tipe === 'pilihan_ganda'" x-cloak
                    class="bg-gray-50/70 p-4 rounded-xl border border-gray-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#173860]">
                            Opsi Jawaban <span x-show="tipe === 'rating'">(nilai = skor × 25)</span>
                        </h3>
                        <button type="button" @click="addOpsi()"
                            class="text-xs bg-[#173860] text-white px-3 py-1.5 rounded-lg hover:bg-[#102a48]">
                            + Tambah Opsi
                        </button>
                    </div>

                    <template x-for="(o, index) in opsiList" :key="index">
                        <div class="flex gap-2 items-center">
                            <input type="text" :name="`opsi[${index}][opsi]`" x-model="o.opsi"
                                placeholder="Label opsi"
                                class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#173860]">
                            <input type="number" x-show="tipe === 'rating'" :name="`opsi[${index}][nilai]`" x-model="o.nilai"
                                placeholder="Nilai" class="w-24 rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#173860]">
                            <button type="button" @click="removeOpsi(index)"
                                class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-lg">&times;</button>
                        </div>
                    </template>
                    @error('opsi')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Info textarea --}}
                <div x-show="tipe === 'textarea'" x-cloak
                    class="bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded-xl p-4">
                    Tipe ini akan menampilkan kolom isian bebas (form pengisian teks) bagi responden — tidak memerlukan opsi jawaban.
                </div>
            </div>

            <div class="bg-gray-50 border-t px-6 py-4 flex justify-end gap-3 sticky bottom-0 z-10">
                <button type="button" @click="openCreate = false"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold">Batal</button>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
function opsiBuilder(tipeAwal = '', opsiAwal = null) {
    return {
        tipe: tipeAwal || '',
        opsiList: [],
        init() {
            if (opsiAwal && opsiAwal.length) {
                this.opsiList = opsiAwal.map(o => ({ opsi: o.opsi ?? '', nilai: o.nilai ?? '' }));
            } else {
                this.setDefaultByTipe();
            }
        },
        setDefaultByTipe() {
            if (this.tipe === 'rating') {
                this.opsiList = [
                    { opsi: 'Tidak Mudah', nilai: 1 },
                    { opsi: 'Kurang Mudah', nilai: 2 },
                    { opsi: 'Mudah', nilai: 3 },
                    { opsi: 'Sangat Mudah', nilai: 4 },
                ];
            } else if (this.tipe === 'pilihan_ganda') {
                this.opsiList = [{ opsi: '', nilai: '' }];
            } else {
                this.opsiList = [];
            }
        },
        onTipeChange() { this.setDefaultByTipe(); },
        addOpsi() { this.opsiList.push({ opsi: '', nilai: this.tipe === 'rating' ? '' : null }); },
        removeOpsi(index) { this.opsiList.splice(index, 1); },
    }
}
</script>