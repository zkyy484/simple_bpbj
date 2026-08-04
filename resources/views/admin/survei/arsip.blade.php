@extends('admin.layouts.app')

@section('title', 'Arsip Survei - Buku Tamu Digital')

@section('content')
    <div x-data="{
        openRestore: false,

        selectedItem: {
            id: '',
            nama: ''
        },

        setRestoreData(item) {
            this.selectedItem = {
                id: item.id,
                nama: item.nama
            };

            this.openRestore = true;
        }

    }" class="relative">

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openRestore }">

            {{-- Breadcrumb & Title --}}
            <div>
                <div class="text-sm text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('admin.survei.index') }}" class="hover:text-gray-700">Survei</a>
                    <span class="mx-1">/</span>
                    <span class="text-gray-700 font-medium">Arsip</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Arsip Data Survei</h1>
            </div>

            {{-- Search & Action Bar --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-5">
                <form action="{{ route('admin.survei.arsip') }}" method="GET" class="flex-1 w-full max-w-lg">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Responden / Email / Instansi..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                        <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <div class="flex gap-3 shrink-0">
                    <a href="{{ route('admin.survei.index') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="arrow-left" class="w-4 h-4 text-gray-600"></i>
                        <span>KEMBALI</span>
                    </a>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">Daftar Arsip Survei</h3>
                    <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                        Total : {{ $respons->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5 text-center w-16">NO</th>
                                <th class="px-6 py-3.5">NAMA</th>
                                <th class="px-6 py-3.5 text-center">EMAIL</th>
                                <th class="px-6 py-3.5 text-center">INSTANSI</th>
                                <th class="px-6 py-3.5 text-center">STATUS</th>
                                <th class="px-6 py-3.5 text-center w-48">AKSI</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($respons as $index => $respon)
                                <tr class="hover:bg-gray-50/50 transition align-top">
                                    <td class="px-6 py-4 text-center font-semibold text-gray-500">
                                        {{ $respons->firstItem() + $index }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $respon->nama_lengkap }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-gray-700">
                                        {{ $respon->email ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-gray-700">
                                        {{ $respon->instansi ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($respon->cek == 'approve')
                                            <span class="px-3 py-1 bg-emerald-500 text-white rounded-full text-[11px] font-bold whitespace-nowrap">
                                                APPROVE
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-amber-500 text-white rounded-full text-[11px] font-bold whitespace-nowrap">
                                                MENUNGGU
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <!-- Tombol Pulihkan -->
                                            <button type="button"
                                                @click="setRestoreData({
                                                    id: {{ $respon->id_respon }},
                                                    nama: @js($respon->nama_lengkap)
                                                })"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                <span>Pulihkan</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada arsip survei.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($respons->hasPages())
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $respons->firstItem() }} to {{ $respons->lastItem() }} of {{ $respons->total() }} entries
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if ($respons->onFirstPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $respons->previousPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($respons->getUrlRange(1, $respons->lastPage()) as $page => $url)
                                @if ($page == $respons->currentPage())
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

                            @if ($respons->hasMorePages())
                                <a href="{{ $respons->nextPageUrl() }}"
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
                            Showing {{ $respons->count() ? 1 : 0 }} to {{ $respons->count() }} of {{ $respons->total() }} entries
                        </p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Modal Restore --}}
        @include('admin.survei.pulihkan')

    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush