<div x-show="openDelete" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <div x-show="openDelete" x-transition.opacity @click="openDelete = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div x-show="openDelete" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.outside="openDelete = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 leading-tight">Arsipkan Akun</h2>
            </div>
            <button type="button" @click="openDelete = false" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 text-center space-y-3">
            <p class="text-gray-600 text-sm">Apakah Anda yakin ingin mengarsipkan akun ini?</p>
            <div class="p-3 bg-red-50 rounded-xl border border-red-100 text-red-700 font-semibold text-sm">
                <span x-text="selectedUser.nama"></span>
            </div>
        </div>

        <form action="{{ route('akun.delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id_user" x-model="selectedUser.id">

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                <button type="button" @click="openDelete = false"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Arsipkan
                </button>
            </div>
        </form>
    </div>
</div>