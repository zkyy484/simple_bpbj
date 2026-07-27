<div x-show="openCreate" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <!-- Overlay -->
    <div x-show="openCreate" x-transition.opacity @click="openCreate = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal -->
    <div x-show="openCreate" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.outside="openCreate = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden">

        <!-- Header -->
        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#173860]/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#173860]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">
                        Tambah Tujuan
                    </h2>
                </div>
            </div>

            <!-- Tombol Close -->
            <button type="button" @click="openCreate = false" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <form action="{{ route('tujuan.add') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Tujuan <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="nama_tujuan" value="{{ old('nama_tujuan') }}"
                        placeholder="Masukkan nama tujuan"
                        class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] @error('nama_tujuan') border-red-400 @else border-gray-300 @enderror">

                    @error('nama_tujuan')
                        <p class="mt-2 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>