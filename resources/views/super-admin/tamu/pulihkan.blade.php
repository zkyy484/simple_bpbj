<!-- Modal Konfirmasi Pulihkan -->
<div id="pulihkanModal" 
     class="hidden fixed inset-0 bg-black/70 backdrop-blur-md items-center justify-center z-[60]">
    
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl overflow-hidden">
        
        <!-- Header -->
        <div class="px-8 pt-8 pb-4 text-center">
            <h3 class="text-2xl font-bold text-gray-800">Pulihkan Data Tamu</h3>
            <p class="mt-4 text-gray-600 text-[15px]">
                Apakah Anda ingin memulihkan data ini?
            </p>
        </div>

        <!-- Buttons -->
        <div class="flex border-t">
            <button onclick="closePulihkanModal()" 
                    class="flex-1 py-5 text-gray-700 font-semibold border-r hover:bg-gray-50 transition rounded-bl-2xl">
                Batal
            </button>
            <button onclick="confirmPulihkan()" 
                    class="flex-1 py-5 text-white font-semibold bg-[#173860] hover:bg-[#102a48] transition rounded-br-2xl">
                Pulihkan
            </button>
        </div>
    </div>
</div>