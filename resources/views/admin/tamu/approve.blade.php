<!-- Modal Konfirmasi Approval / Toggle Status -->
<div x-show="openApprove" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <!-- Backdrop dengan efek blur & fade -->
    <div x-show="openApprove" 
         x-transition.opacity 
         @click="openApprove = false"
         class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <!-- Container Modal dengan Transisi Zoom In / Scale -->
    <div x-show="openApprove" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90" 
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90" 
         @click.outside="openApprove = false"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        <!-- Header Modal Dinamis -->
        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-100">
            <div class="flex items-center gap-3">
                <!-- Ikon Hijau jika Mau Approve, Ikon Kuning/Oranye jika Mau Batalkan -->
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                    :class="selected.approval === 'approve' ? 'bg-amber-500/10' : 'bg-emerald-500/10'">
                    
                    <!-- Ikon Check (Persetujuan) -->
                    <template x-if="selected.approval !== 'approve'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>

                    <!-- Ikon Clock/Undo (Kembali Menunggu) -->
                    <template x-if="selected.approval === 'approve'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                </div>

                <!-- Judul Header Dinamis -->
                <h2 class="text-lg font-bold text-gray-900 leading-tight"
                    x-text="selected.approval === 'approve' ? 'Batalkan Persetujuan Tamu' : 'Persetujuan Data Tamu'">
                </h2>
            </div>

            <button type="button" @click="openApprove = false" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body Konten Dinamis -->
        <div class="p-6 text-center space-y-3">
            <p class="text-gray-600 text-sm"
               x-text="selected.approval === 'approve' 
                  ? 'Apakah Anda yakin ingin membatalkan persetujuan kunjungan untuk tamu ini?' 
                  : 'Apakah Anda yakin ingin menyetujui kunjungan dari tamu ini?'">
            </p>

            <div class="p-3 rounded-xl border font-semibold text-sm transition-colors"
                 :class="selected.approval === 'approve' 
                    ? 'bg-amber-50 border-amber-100 text-amber-800' 
                    : 'bg-emerald-50 border-emerald-100 text-emerald-800'">
                <span x-text="selected.nama_lengkap"></span>
            </div>

            <p class="text-xs text-gray-400"
               x-text="selected.approval === 'approve' 
                  ? 'Status kunjungan akan dikembalikan menjadi \'Menunggu\'.' 
                  : 'Status kunjungan akan diperbarui menjadi \'Disetujui\'.'">
            </p>
        </div>

        <!-- Form Approval -->
        <form :action="approveUrl" method="POST">
            @csrf
            @method('PATCH')

            <!-- Footer Modal -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                <button type="button" @click="openApprove = false"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold transition text-sm">
                    Batal
                </button>

                <!-- Tombol Submit Dinamis -->
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl text-white font-semibold transition flex items-center gap-2 text-sm shadow-sm"
                    :class="selected.approval === 'approve' 
                        ? 'bg-amber-500 hover:bg-amber-600' 
                        : 'bg-emerald-600 hover:bg-emerald-700'">
                    
                    <!-- Ikon Tombol -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            :d="selected.approval === 'approve' ? 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M5 13l4 4L19 7'" />
                    </svg>

                    <!-- Teks Tombol -->
                    <span x-text="selected.approval === 'approve' ? 'Ubah ke Menunggu' : 'Setujui Kunjungan'"></span>
                </button>
            </div>
        </form>
    </div>
</div>