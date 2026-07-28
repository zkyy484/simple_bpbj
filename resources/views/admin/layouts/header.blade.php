<!-- Tag Header dengan efek blur dan transisi halus -->
<header id="main-header" class="sticky top-0 z-50 bg-[#102a48] text-white px-6 py-3.5 flex items-center justify-between shadow-md backdrop-blur-md transition-all duration-300">

    <!-- Logo & Title -->
    <div class="flex items-center gap-4 min-w-0">
        <img src="{{ asset('images/logo.png') }}"
             alt="Logo Kota Denpasar"
             class="h-12 w-auto object-contain shrink-0">

        <div class="h-10 w-px bg-white/20 hidden sm:block"></div>

        <div class="min-w-0">
            <p class="text-[11px] text-yellow-400 font-semibold uppercase tracking-wider leading-none">
                Pemerintah Kota Denpasar
            </p>
            <h1 class="text-lg sm:text-xl font-semibold tracking-tight leading-snug mt-1 truncate">
                Buku Tamu Digital
            </h1>
            <p class="text-xs text-gray-300 font-light leading-none mt-1 truncate">
                Bagian Pengadaan Barang dan Jasa
            </p>
        </div>
    </div>

    <!-- Profile -->
    <a href="{{ route('super.profile') }}"

    
    <div class="flex items-center gap-3 shrink-0">
        <div class="hidden md:block text-right leading-tight">
            <p class="text-sm font-semibold">{{$admins->nama_lengkap}}</p>
            <p class="text-[11px] text-gray-300">{{$admins->role}}</p>
        </div>

        <button class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center hover:bg-white/10 transition shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </button>
    </div>


</header>

<!-- Script JS untuk mendeteksi scroll -->
<script>
    window.addEventListener('scroll', function() {
        const header = document.getElementById('main-header');
        if (window.scrollY > 20) {
            // Saat di-scroll: Ubah background ke warna semi-transparan (Opacity 80%)
            header.classList.remove('bg-[#102a48]');
            header.classList.add('bg-[#102a48]/80');
        } else {
            // Kembali ke paling atas: Kembali solid 100%
            header.classList.remove('bg-[#102a48]/80');
            header.classList.add('bg-[#102a48]');
        }
    });
</script>