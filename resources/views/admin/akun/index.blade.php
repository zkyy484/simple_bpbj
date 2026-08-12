@extends('admin.layouts.app')

@section('title', 'Manajemen Akun - Buku Tamu Digital')

@section('content')
    <div class="space-y-6">

        {{-- Breadcrumb & Title --}}
        <div>
            <div class="text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700 font-medium">Akun</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Akun</h1>
        </div>

        {{-- Search & Action Bar --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-5">
            <form action="{{ route('admin.index.akun') }}" method="GET" class="flex-1 w-full max-w-lg">
                <div class="relative flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Akun..."
                        class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                    <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900">Daftar Akun</h3>
                <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                    Total : {{ $accounts->total() ?? 0 }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5 text-center w-16">NO</th>
                            <th class="px-6 py-3.5">NAMA</th>
                            <th class="px-6 py-3.5 text-center">EMAIL</th>
                            <th class="px-6 py-3.5 text-center">NO. TELEPON</th>
                            <th class="px-6 py-3.5 text-center">ALAMAT</th>
                            <th class="px-6 py-3.5 text-center">SUB BAGIAN</th>
                            <th class="px-6 py-3.5 text-center">ROLE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($accounts as $index => $account)
                            <tr class="hover:bg-gray-50/50 transition align-top">
                                <td class="px-6 py-4 text-center font-semibold text-gray-500">
                                    {{ $accounts->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $account->nama_lengkap }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-700">
                                    {{ $account->email }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-700">
                                    {{ $account->no_telepon ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-700">
                                    {{ $account->alamat ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-700">
                                    {{ $account->subBagian->nama_sub_bagian ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-[11px] font-bold text-white rounded-full bg-[#173860] whitespace-nowrap">
                                        {{ strtoupper($account->role) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    Belum ada data akun.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($accounts->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        Showing {{ $accounts->firstItem() }} to {{ $accounts->lastItem() }} of {{ $accounts->total() }} entries
                    </p>
                    <div class="flex items-center gap-1.5">
                        @if ($accounts->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </span>
                        @else
                            <a href="{{ $accounts->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </a>
                        @endif

                        @foreach ($accounts->getUrlRange(1, $accounts->lastPage()) as $page => $url)
                            @if ($page == $accounts->currentPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#173860] text-white text-xs font-semibold">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-xs font-semibold">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($accounts->hasMorePages())
                            <a href="{{ $accounts->nextPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </span>
                        @endif
                    </div>
                </div>
            @else
                <div class="px-6 py-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        Showing {{ $accounts->count() ? 1 : 0 }} to {{ $accounts->count() }} of {{ $accounts->total() }} entries
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush