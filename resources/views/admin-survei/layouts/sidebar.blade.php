@php
    $navClass = fn(string $pattern) => request()->routeIs($pattern)
        ? 'bg-[#173860] text-white shadow-sm'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';

    $iconClass = fn(string $pattern) => request()->routeIs($pattern)
        ? 'text-white'
        : 'text-gray-400 group-hover:text-gray-600';
@endphp

<aside class="sticky top-0 h-screen w-64 shrink-0 bg-white flex flex-col justify-between border-r border-gray-100 overflow-hidden">
    <div class="flex-1 overflow-y-auto">
        <nav class="px-3 py-4 space-y-6 text-sm font-medium">
            <div class="space-y-1">
                <p class="px-3 mb-1 text-[11px] font-semibold tracking-wide text-gray-400 uppercase">
                    Menu Admin Survei
                </p>

                {{-- 1. Dashboard --}}
                <a href="{{ route('admin-survei.dashboard') }}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $navClass('admin-survei.dashboard') }}">
                    <svg class="w-5 h-5 {{ $iconClass('admin-survei.dashboard') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7m-9-2v10a1 1 0 001 1h3m6-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                {{-- 2. Pertanyaan (Manajemen Soal Survei) --}}
                <a href="{{route('admin-survei.pertanyaan.index')}}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $navClass('admin-survei.pertanyaan*') }}">
                    <svg class="w-5 h-5 {{ $iconClass('admin-survei.pertanyaan*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Pertanyaan
                </a>

                {{-- 3. Laporan Survei --}}
                <a href="{{route('admin-survei.laporan.index')}}"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $navClass('admin-survei.laporan.*') }}">
                    <svg class="w-5 h-5 {{ $iconClass('admin-survei.laporan*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm6 0v-3a2 2 0 00-2-2h-2a2 2 0 00-2 2v3a2 2 0 002 2h2a2 2 0 002-2z" />
                    </svg>
                    Laporan Survei
                </a>
            </div>
        </nav>
    </div>

    {{-- Tombol Keluar / Logout --}}
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