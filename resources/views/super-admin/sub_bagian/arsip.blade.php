@extends('super-admin.layouts.app')

@section('title', 'Arsip Sub Bagian - Buku Tamu Digital')

@section('content')
    <div x-data="{
        openRestore: false,
        selectedSub: {
            id: '',
            nama: ''
        },

        setRestoreData(sub) {
            this.selectedSub = {
                id: sub.id_sub_bagian || sub.id,
                nama: sub.nama
            };
            this.openRestore = true;
        }
    }" class="relative">

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{
                'blur-sm pointer-events-none select-none scale-[0.99]': openRestore
            }">

            <!-- Breadcrumb -->
            <div class="text-sm text-gray-500">
                <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('super.sub.index') }}" class="hover:text-gray-700">Sub Bagian</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700 font-medium">Arsip</span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900">Arsip Data Sub Bagian</h1>

            <!-- Search & Action Card -->
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-4">
                <form action="{{ route('super.sub.arsip') }}" method="GET" class="flex-1 w-full max-w-md">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari arsip sub bagian..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-gray-400">
                        <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <div class="flex items-center gap-3 w-full lg:w-auto justify-end">
                    <!-- Tombol Kembali dengan Icon -->
                    <a href="{{ route('super.sub.index') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="arrow-left" class="w-4 h-4 text-gray-600"></i>
                        <span>KEMBALI</span>
                    </a>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">Daftar Arsip Sub Bagian</h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $subBagians->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5 text-center w-16">No</th>
                                <th class="px-6 py-3.5">Nama Sub Bagian</th>
                                <th class="px-6 py-3.5 text-center w-48">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($subBagians as $index => $subBagian)
                                <tr class="hover:bg-gray-50/50 transition align-top">
                                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                                        {{ $subBagians->firstItem() + $index }}
                                    </td>

                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $subBagian->nama_sub_bagian }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center">
                                            <!-- Tombol Pulihkan dengan Icon -->
                                            <button type="button"
                                                @click="setRestoreData({
                                                    id_sub_bagian: {{ $subBagian->id_sub_bagian }},
                                                    nama: @js($subBagian->nama_sub_bagian)
                                                })"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                <span>Pulihkan</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada data arsip sub bagian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($subBagians->hasPages())
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $subBagians->firstItem() }} to {{ $subBagians->lastItem() }} of {{ $subBagians->total() }} entries
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if ($subBagians->onFirstPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $subBagians->previousPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($subBagians->getUrlRange(1, $subBagians->lastPage()) as $page => $url)
                                @if ($page == $subBagians->currentPage())
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

                            @if ($subBagians->hasMorePages())
                                <a href="{{ $subBagians->nextPageUrl() }}"
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
                            Showing {{ $subBagians->count() ? 1 : 0 }} to {{ $subBagians->count() }} of {{ $subBagians->total() }} entries
                        </p>
                    </div>
                @endif
            </div>

        </div>

        {{-- INCLUDE MODAL PULIHKAN --}}
        @include('super-admin.sub_bagian.pulihkan')

    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush