<div x-show="openRestore" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <!-- Overlay -->
    <div x-show="openRestore" x-transition.opacity @click="openRestore = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal Card -->
    <div x-show="openRestore" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.outside="openRestore = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        <!-- Header -->
        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">
                        Pulihkan Jenis Permohonan
                    </h2>
                </div>
            </div>

            <!-- Tombol Close Merah -->
            <button type="button" @click="openRestore = false" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 text-center space-y-3">
            <p class="text-gray-600 text-sm">
                Apakah Anda yakin ingin memulihkan data Jenis Permohonan ini?
            </p>
            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-emerald-700 font-semibold text-sm">
                <span x-text="selectedJenis.nama"></span>
            </div>
        </div>

        <!-- Footer Form -->
        <form action="{{ route('super.jenis.pulihkan') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Input Hidden untuk mengirimkan ID Jenis Permohonan ke Controller -->
            <input type="hidden" name="id_jenis_permohonan" x-model="selectedJenis.id">

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Pulihkan Data
                </button>
            </div>
        </form>

    </div>
</div>