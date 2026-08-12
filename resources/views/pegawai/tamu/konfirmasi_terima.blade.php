<!-- MODAL: KONFIRMASI TERIMA TAMU (SWEETALERT STYLE) -->
<div x-show="openKonfirmasiTerima" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Backdrop Blur -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" @click="openKonfirmasiTerima = false"></div>

    <!-- Modal Card -->
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 text-center transform transition-all"
        @click.outside="openKonfirmasiTerima = false">
        
        <!-- Icon Warning/Info Circle -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 mb-4">
            <i data-lucide="user-check" class="w-8 h-8 text-[#173860]"></i>
        </div>

        <!-- Title & Subtitle -->
        <h3 class="text-xl font-bold text-gray-900 mb-2">Terima Tamu Ini?</h3>
        <p class="text-sm text-gray-500 mb-6">
            Anda akan menjadi penanggung jawab untuk tamu <span class="font-semibold text-gray-800" x-text="selected.nama_lengkap"></span> (<span class="font-semibold text-gray-800" x-text="selected.kode_tiket"></span>). Pegawai lain hanya dapat melihat detail data.
        </p>

        <!-- Form Action Buttons -->
        <form :action="'{{ url('/pegawai/tamu') }}/' + selected.id + '/terima'" method="POST" class="flex items-center justify-center gap-3">
            @csrf
            @method('PUT')

            <button type="button" @click="openKonfirmasiTerima = false"
                class="w-full py-2.5 rounded-xl text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                Batal
            </button>
            <button type="submit"
                class="w-full py-2.5 rounded-xl text-sm font-bold bg-[#173860] hover:bg-[#12294a] text-white transition shadow-md">
                Ya, Terima Tamu
            </button>
        </form>
    </div>
</div>