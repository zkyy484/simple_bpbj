<div x-show="openDetail" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <!-- Overlay -->
    <div x-show="openDetail"
        x-transition.opacity
        @click="openDetail = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal Card -->
    <div
        x-show="openDetail"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.outside="openDetail = false"
        class="relative bg-white w-full max-w-2xl max-h-[90vh] rounded-2xl shadow-xl flex flex-col overflow-hidden">

        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white sticky top-0 z-10">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                <h3 class="text-base font-bold text-gray-900">Detail Respon Survei</h3>
            </div>
            <button @click="openDetail = false" class="p-1 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-5 overflow-y-auto custom-scrollbar flex-1">
            <template x-if="loadingDetail">
                <div class="flex flex-col items-center justify-center py-12 space-y-3">
                    <div class="w-8 h-8 border-3 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-xs text-gray-500 font-medium">Memuat data respon...</p>
                </div>
            </template>

            <template x-if="!loadingDetail">
                <div x-html="detailContent"></div>
            </template>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex justify-end">
            <button @click="openDetail = false"
                    class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg transition shadow-2xs">
                Tutup
            </button>
        </div>

    </div>
</div>