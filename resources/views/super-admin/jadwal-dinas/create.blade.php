<!-- MODAL: TAMBAH JADWAL DINAS -->
<div x-show="openCreate" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <!-- Overlay -->
    <div x-show="openCreate" x-transition.opacity @click="if (!loading) openCreate = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal Card -->
    <div x-show="openCreate" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.outside="if (!loading) openCreate = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden max-h-[90vh] overflow-y-auto">

        <!-- Header Modal Sticky -->
        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-200 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#173860]/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#173860]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">
                        Tambah Jadwal Dinas
                    </h2>
                </div>
            </div>

            <!-- Tombol Close -->
            <button type="button" @click="openCreate = false" :disabled="loading" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body Form -->
        <form action="{{ route('super.jadwal_dinas.store') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <input type="hidden" name="form_type" value="create">

            {{-- Ringkasan Error Validation --}}
            @if ($errors->any() && old('form_type') == 'create')
                <div class="mx-6 mt-6 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm space-y-1">
                    <p class="font-semibold">Data belum tersimpan, mohon periksa kembali:</p>
                    @foreach ($errors->all() as $error)
                        <p>&bull; {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="p-6 space-y-6">

                <!-- SECTION 1: INFORMASI SURAT MASUK -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Informasi Surat Masuk</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Bidang/Sekretariat -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Bidang/Sekretariat
                            </label>
                            @php $borderBidang = $errors->has('bidang_sekretariat') ? 'border-red-400' : 'border-gray-300'; @endphp
                            <input type="text" name="bidang_sekretariat" value="{{ old('bidang_sekretariat') }}"
                                placeholder="Contoh: Sekretariat / Bidang Pengadaan"
                                class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] {{ $borderBidang }}">
                            @error('bidang_sekretariat')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Surat Dari -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Surat Dari <span class="text-red-500">*</span>
                            </label>
                            @php $borderDari = $errors->has('surat_dari') ? 'border-red-400' : 'border-gray-300'; @endphp
                            <input type="text" name="surat_dari" value="{{ old('surat_dari') }}"
                                placeholder="Contoh: Dinas Provinsi"
                                class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] {{ $borderDari }}">
                            @error('surat_dari')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Acara -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Acara <span class="text-red-500">*</span>
                        </label>
                        @php $borderAcara = $errors->has('acara') ? 'border-red-400' : 'border-gray-300'; @endphp
                        <textarea name="acara" rows="2" placeholder="Isi nama acara atau kegiatan..."
                            class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] resize-none {{ $borderAcara }}">{{ old('acara') }}</textarea>
                        @error('acara')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SECTION 2: DETAIL PELAKSANAAN & DELEGASI -->
                <div class="pt-4 border-t border-gray-200 space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-[#173860]"></div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#173860]">Pelaksanaan & Penugasan</h3>
                    </div>

                    <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200/80 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Hari/Tanggal -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Hari/Tanggal <span class="text-red-500">*</span>
                                </label>
                                @php $borderHariTanggal = $errors->has('hari_tanggal') ? 'border-red-400' : 'border-gray-300'; @endphp
                                <input type="date" name="hari_tanggal" value="{{ old('hari_tanggal') }}"
                                    class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] bg-white {{ $borderHariTanggal }}">
                                @error('hari_tanggal')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Waktu -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Waktu
                                </label>
                                @php $borderWaktu = $errors->has('waktu') ? 'border-red-400' : 'border-gray-300'; @endphp
                                <input type="time" name="waktu" value="{{ old('waktu') }}"
                                    class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] bg-white {{ $borderWaktu }}">
                                @error('waktu')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tempat/Zoom -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tempat/Zoom
                            </label>
                            @php $borderTempat = $errors->has('tempat_zoom') ? 'border-red-400' : 'border-gray-300'; @endphp
                            <input type="text" name="tempat_zoom" value="{{ old('tempat_zoom') }}"
                                placeholder="Contoh: Ruang Rapat Utama atau link Zoom"
                                class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] bg-white {{ $borderTempat }}">
                            @error('tempat_zoom')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Yang Hadir dengan Fitur Search Filter Alpine.js -->
                        <div x-data="{ searchPegawai: '' }">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Yang Hadir (Opsional)
                            </label>

                            <!-- Input Search Filter -->
                            <div class="relative mb-2">
                                <input type="text" x-model="searchPegawai" placeholder="Cari nama pegawai..."
                                    class="w-full bg-white border border-gray-300 rounded-lg pl-8 pr-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none">
                                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            <!-- List Checkbox Pegawai -->
                            <div class="bg-white rounded-xl border border-gray-300 p-3 max-h-40 overflow-y-auto space-y-2">
                                @forelse($pegawaiList as $user)
                                    <label x-show="searchPegawai === '' || @js(strtolower($user->nama_lengkap)).includes(searchPegawai.toLowerCase())"
                                        class="flex items-center gap-3 text-sm text-gray-700 hover:bg-gray-50 p-1.5 rounded-lg cursor-pointer transition">
                                        <input type="checkbox" name="pegawai_ids[]" value="{{ $user->id_user }}"
                                            {{ is_array(old('pegawai_ids')) && in_array($user->id_user, old('pegawai_ids')) ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-gray-300 text-[#173860] focus:ring-[#173860]">
                                        <span class="font-medium">{{ $user->nama_lengkap }}</span>
                                    </label>
                                @empty
                                    <p class="text-xs text-gray-400 italic p-1">Belum ada data pegawai.</p>
                                @endforelse

                                <!-- Pesan Jika Pegawai Tidak Ditemukan saat Mencari -->
                                @if($pegawaiList->isNotEmpty())
                                    <div x-show="searchPegawai !== '' && ![...$el.parentElement.querySelectorAll('label')].some(el => el.style.display !== 'none')" 
                                         class="text-xs text-gray-400 text-center py-2">
                                        Pegawai tidak ditemukan.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Keterangan Tambahan
                            </label>
                            <textarea name="keterangan" rows="2" placeholder="Catatan tambahan lokasi atau ruangan (opsional)"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] bg-white resize-none">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Sticky -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3 sticky bottom-0 z-10">
                <button type="button" @click="openCreate = false" :disabled="loading"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal
                </button>
                
                <button type="submit" :disabled="loading"
                    class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold transition flex items-center gap-2 disabled:opacity-75 disabled:cursor-not-allowed">
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Data'"></span>
                </button>
            </div>
        </form>
    </div>
</div>