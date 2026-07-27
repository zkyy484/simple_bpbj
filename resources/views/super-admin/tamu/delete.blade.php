<!-- Modal Konfirmasi Hapus -->
<div x-show="openDelete" x-cloak
     class="fixed inset-0 bg-black/70 backdrop-blur-md items-center justify-center z-[60] flex">

    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl overflow-hidden"
         @click.outside="openDelete = false">

        <!-- Header -->
        <div class="px-8 pt-8 pb-4 text-center">
            <h3 class="text-2xl font-bold text-gray-800">Hapus Data Tamu</h3>
            <p class="mt-4 text-gray-600 text-[15px]">
                Apakah Anda ingin menghapus data
                <span class="font-semibold" x-text="selected.nama_lengkap"></span>?
            </p>
        </div>

        <!-- Form Hapus -->
        <form :action="deleteUrl" method="POST">
            @csrf
            @method('DELETE')

            <!-- Buttons -->
            <div class="flex border-t">
                <button type="button" @click="openDelete = false"
                        class="flex-1 py-5 text-gray-700 font-semibold border-r hover:bg-gray-50 transition rounded-bl-2xl">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-5 text-white font-semibold bg-red-600 hover:bg-red-700 transition rounded-br-2xl">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>