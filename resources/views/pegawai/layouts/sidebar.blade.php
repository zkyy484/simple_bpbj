@php
    /**
     * Helper kecil untuk menentukan class item nav berdasarkan route aktif saat ini.
     * Ganti pattern route (mis. 'sub-bagian.*') sesuai nama route yang sudah/akan
     * Anda daftarkan di routes/web.php.
     */
    $navClass = fn (string $pattern) => request()->routeIs($pattern)
        ? 'bg-[#173860] text-white shadow-sm'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';

    $iconClass = fn (string $pattern) => request()->routeIs($pattern)
        ? 'text-white'
        : 'text-gray-400 group-hover:text-gray-600';
@endphp

<!-- Tambahkan sticky top-0 h-screen di elemen aside -->
<aside class="sticky top-0 h-screen w-64 shrink-0 bg-white flex flex-col justify-between border-r border-gray-100 overflow-hidden">

    <div class="flex-1 overflow-y-auto">

        {{-- Navigation Menu --}}
        <nav class="px-3 py-4 space-y-6 text-sm font-medium">

            <div class="space-y-1">
                <p class="px-3 mb-1 text-[11px] font-semibold tracking-wide text-gray-400 uppercase">
                    Menu Utama
                </p>

                <a href="{{ route('pegawai.dashboard') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $navClass('pegawai.dashboard') }}">
                    <svg class="w-5 h-5 {{ $iconClass('pegawai.dashboard') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9-2v10a1 1 0 001 1h3m6-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('pegawai.tamu.index') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $navClass('pegawai.tamu.index') }}">
                    <svg class="w-5 h-5 {{ $iconClass('pegawai.tamu.index') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Tamu
                </a>
            </div>
        </nav>
    </div>

    {{-- Button Keluar --}}
    <div class="p-3 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white font-semibold py-2.5 rounded-lg transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>