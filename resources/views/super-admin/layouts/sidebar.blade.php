@php
    /**
     * Helper kecil untuk menentukan class item nav berdasarkan route aktif saat ini.
     * Ganti pattern route (mis. 'sub-bagian.*') sesuai nama route yang sudah/akan
     * Anda daftarkan di routes/web.php.
     */
    $navClass = fn(string $pattern) => request()->routeIs($pattern)
        ? 'bg-[#173860] text-white shadow-sm'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';

    $iconClass = fn(string $pattern) => request()->routeIs($pattern)
        ? 'text-white'
        : 'text-gray-400 group-hover:text-gray-600';
@endphp

<!-- Tambahkan sticky top-0 h-screen di elemen aside -->
<aside
    class="sticky top-0 h-screen w-64 shrink-0 bg-white flex flex-col justify-between border-r border-gray-100 overflow-hidden">

    <div class="flex-1 overflow-y-auto">

        {{-- Navigation Menu --}}
        <nav class="px-3 py-4 space-y-6 text-sm font-medium">

            <div class="space-y-1">
                <p class="px-3 mb-1 text-[11px] font-semibold tracking-wide text-gray-400 uppercase">
                    Menu Utama
                </p>

                <a href="{{ route('super.dashboard') }}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $navClass('super.dashboard') }}">
                    <svg class="w-5 h-5 {{ $iconClass('super.dashboard') }}" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7m-9-2v10a1 1 0 001 1h3m6-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('tamu.index') }}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $navClass('tamu.index') }}">
                    <svg class="w-5 h-5 {{ $iconClass('tamu.index') }}" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Tamu
                </a>

                <a href="{{ route('super.index.survei') }}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $navClass('super.index.survei') }}">
                    <svg class="w-5 h-5 {{ $iconClass('super.index.survei') }}" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Survei
                </a>
            </div>

            <div class="space-y-1">
                <p class="px-3 mb-1 text-[11px] font-semibold tracking-wide text-gray-400 uppercase">
                    Data &amp; Laporan
                </p>

                {{-- Dropdown Masterdata --}}
                <div x-data="{ open: {{ request()->routeIs('index.akun') || request()->routeIs('index.sub*') || request()->routeIs('tujuan.index*') || request()->routeIs('index.pertanyaan') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full group flex items-center justify-between px-3 py-2.5 rounded-lg transition {{ $navClass('index.akun') === 'bg-[#173860] text-white shadow-sm' || $navClass('index.sub*') === 'bg-[#173860] text-white shadow-sm' || $navClass('tujuan.index*') === 'bg-[#173860] text-white shadow-sm' || $navClass('index.pertanyaan') === 'bg-[#173860] text-white shadow-sm' ? 'bg-[#173860] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 1.1 3.6 2 8 2s8-.9 8-2V7M4 7c0 1.1 3.6 2 8 2s8-.9 8-2M4 7c0-1.1 3.6-2 8-2s8 .9 8 2m0 5c0 1.1-3.6 2-8 2s-8-.9-8-2" />
                            </svg>
                            Masterdata
                        </span>
                        <svg class="w-4 h-4 transition-transform shrink-0" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 pl-11 space-y-0.5">
                        <a href="{{ route('index.sub') }}"
                            class="block px-2 py-1.5 text-xs rounded-md {{ $navClass('index.sub*') }}">Sub Bagian</a>
                        <a href="{{ route('tujuan.index') }}"
                            class="block px-2 py-1.5 text-xs rounded-md {{ $navClass('tujuan.index*') }}">Tujuan</a>
                        <a href="{{ route('index.akun') }}"
                            class="block px-2 py-1.5 text-xs rounded-md {{ $navClass('index.akun') }}">Akun</a>
                        <a href="{{ route('super.index.survei') }}"
                            class="block px-2 py-1.5 text-xs rounded-md {{ $navClass('super.index.survei') }}">Pertanyaan</a>
                    </div>
                </div>

                {{-- Dropdown Laporan --}}
                <div x-data="{ open: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full group flex items-center justify-between px-3 py-2.5 rounded-lg transition {{ $navClass('laporan.*') }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ $iconClass('laporan.*') }}" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm6 0v-3a2 2 0 00-2-2h-2a2 2 0 00-2 2v3a2 2 0 002 2h2a2 2 0 002-2z" />
                            </svg>
                            Laporan
                        </span>
                        <svg class="w-4 h-4 transition-transform shrink-0" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 pl-11 space-y-0.5">
                        <a href="{{ route('laporan.buku-tamu.index') }}"
                            class="block px-2 py-1.5 text-xs rounded-md {{ $navClass('laporan.buku-tamu.*') }}">Laporan
                            Buku Tamu</a>
                        <a href="{{ route('laporan.pengunjung.index') }}"
                            class="block px-2 py-1.5 text-xs rounded-md {{ $navClass('laporan.pengunjung.*') }}">Laporan
                            Pengunjung</a>
                        <a href="{{ route('laporan.survei.index') }}"
                            class="block px-2 py-1.5 text-xs rounded-md {{ $navClass('laporan.survei.*') }}">Laporan
                            Survei</a>


                    </div>
                </div>

                <a href="{{ route('log-aktivitas.index') }}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $navClass('log-aktivitas.*') }}">
                    <svg class="w-5 h-5 {{ $iconClass('log-aktivitas.*') }}" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Log Aktivitas
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
