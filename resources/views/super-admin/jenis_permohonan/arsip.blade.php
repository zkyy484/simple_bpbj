@extends('super-admin.layouts.app')

@section('title', 'Arsip Jenis Permohonan - Buku Tamu Digital')

@section('content')
    <div x-data="{
        openRestore: false,
        selectedJenis: {
            id: '',
            nama: ''
        },

        setRestoreData(jenis) {
            this.selectedJenis = {
                id: jenis.id_jenis_permohonan || jenis.id,
                nama: jenis.nama
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
                <a href="{{ route('index.jenis') }}" class="hover:text-gray-700">Jenis Permohonan</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700 font-medium">Arsip</span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900">Arsip Data Jenis Permohonan</h1>

            <!-- Search & Action Card -->
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-4">
                <form action="{{ route('arsip.jenis') }}" method="GET" class="flex-1 w-full max-w-md">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari arsip jenis permohonan..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-gray-400">
                        <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <div class="flex items-center gap-3 w-full lg:w-auto justify-end">
                    <!-- Tombol Kembali -->
                    <a href="{{ route('index.jenis') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="arrow-left" class="w-4 h-4 text-gray-600"></i>
                        <span>KEMBALI</span>
                    </a>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">Daftar Arsip Jenis Permohonan</h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $jenisPermohonans->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5 text-center w-16">No</th>
                                <th class="px-6 py-3.5">Nama Jenis Permohonan</th>
                                <th class="px-6 py-3.5 text-center w-48">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($jenisPermohonans as $index => $jenis)
                                <tr class="hover:bg-gray-50/50 transition align-top">
                                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                                        {{ $jenisPermohonans->firstItem() + $index }}
                                    </td>

                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $jenis->nama_jenis_permohonan }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center">
                                            <!-- Tombol Pulihkan -->
                                            <button type="button"
                                                @click="setRestoreData({
                                                    id_jenis_permohonan: {{ $jenis->id_jenis_permohonan }},
                                                    nama: @js($jenis->nama_jenis_permohonan)
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
                                        Belum ada data arsip jenis permohonan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($jenisPermohonans->hasPages())
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $jenisPermohonans->firstItem() }} to {{ $jenisPermohonans->lastItem() }} of {{ $jenisPermohonans->total() }} entries
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if ($jenisPermohonans->onFirstPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $jenisPermohonans->previousPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($jenisPermohonans->getUrlRange(1, $jenisPermohonans->lastPage()) as $page => $url)
                                @if ($page == $jenisPermohonans->currentPage())
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

                            @if ($jenisPermohonans->hasMorePages())
                                <a href="{{ $jenisPermohonans->nextPageUrl() }}"
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
                            Showing {{ $jenisPermohonans->count() ? 1 : 0 }} to {{ $jenisPermohonans->count() }} of {{ $jenisPermohonans->total() }} entries
                        </p>
                    </div>
                @endif
            </div>

        </div>

        {{-- INCLUDE MODAL PULIHKAN --}}
        @include('super-admin.jenis_permohonan.pulihkan')

    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush