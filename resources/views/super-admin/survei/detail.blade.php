{{-- resources/views/super-admin/survei/detail.blade.php --}}
<div x-show="openDetail" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50" @click="openDetail = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    {{-- Card --}}
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="openDetail = false">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
            <h3 class="text-lg font-bold text-gray-900">Detail Respon Survei</h3>
            <button type="button" @click="openDetail = false"
                class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body (scrollable) --}}
        <div class="p-6 overflow-y-auto flex-1">
            {{-- Loading state --}}
            <template x-if="loadingDetail">
                <div class="flex flex-col items-center justify-center py-16 text-gray-400 gap-3">
                    <svg class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <p class="text-sm">Memuat detail survei...</p>
                </div>
            </template>

            {{-- Loaded content --}}
            <template x-if="!loadingDetail">
                <div x-html="detailContent"></div>
            </template>
        </div>
    </div>
</div>