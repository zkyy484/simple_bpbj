<div x-show="openEdit" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <!-- Overlay -->
    <div x-show="openEdit" x-transition.opacity @click="openEdit = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal Card -->
    <div x-show="openEdit" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.outside="openEdit = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden">

        <!-- Header -->
        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">
                        Edit Sub Bagian
                    </h2>
                </div>
            </div>

            <!-- Tombol Close Merah -->
            <button type="button" @click="openEdit = false" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body Form -->
        <form action="{{ route('super.sub.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tambahkan input hidden ini -->
            <input type="hidden" name="id_sub_bagian" x-model="selectedSub.id">

            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Sub Bagian <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="nama_sub_bagian" x-model="selectedSub.nama"
                        placeholder="Masukkan Nama Sub Bagian"
                        class="w-full rounded-xl border px-4 py-3 outline-none transition
                               focus:ring-2 focus:ring-amber-500 focus:border-amber-500
                               @error('nama_sub_bagian') border-red-400 @else border-gray-300 @enderror">

                    @error('nama_sub_bagian')
                        <p class="flex items-center gap-1 text-red-500 text-sm mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14.18A2 2 0 003.82 21h16.36a2 2 0 001.71-2.96L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">

                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
