@extends('super-admin.layouts.app')

@section('title', 'Manajemen Tujuan - Buku Tamu Digital')

@section('content')
    <div x-data="{
        openCreate: Boolean({{ $errors->any() ? 1 : 0 }}),
        openEdit: false,
        openDelete: false,
        selectedSub: {
            id_tujuan: '',
            nama_tujuan: '',
            status: ''
        },
        editUrl: '',
        deleteUrl: '',
    
        setEditData(sub) {
            this.selectedSub = {
                id: sub.id_tujuan || sub.id,
                nama: sub.nama,
                status: sub.status
            };
            this.editUrl = '{{ url('/super-admin/tujuan') }}/' + (sub.id_tujuan || sub.id);
            this.openEdit = true;
        },
    
        setDeleteData(sub) {
            this.selectedSub = {
                id: sub.id_tujuan || sub.id,
                nama: sub.nama
            };
            this.deleteUrl = '{{ url('/super-admin/tujuan') }}/' + (sub.id_tujuan || sub.id);
            this.openDelete = true;
        }
    }" class="relative">

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openCreate || openEdit || openDelete }">

            {{-- Breadcrumb & Title --}}
            <div>
                <div class="text-sm text-gray-500 mb-1">
                    <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-1">/</span>
                    <span class="text-gray-700 font-medium">Tujuan</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Tujuan</h1>
            </div>

            {{-- Search & Action Bar --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-5">
                <form action="{{ route('tujuan.index') }}" method="GET" class="flex-1 w-full max-w-lg">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Tujuan..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                        <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <div class="flex gap-3 shrink-0">
                    <!-- Tombol Halaman Arsip -->
                    <a href="{{ route('tujuan.arsip') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="archive" class="w-4 h-4 text-gray-600"></i>
                        <span>ARSIP</span>
                    </a>

                    <button type="button" @click="openCreate = true"
                        class="px-5 py-2.5 bg-[#173860] hover:bg-[#12294a] text-white text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>TAMBAH DATA</span>
                    </button>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">Daftar Tujuan</h3>
                    <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                        Total : {{ $Tujuans->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5 text-center w-16">No</th>
                                <th class="px-6 py-3.5">Nama Tujuan</th>
                                <th class="px-6 py-3.5 text-center w-48">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($Tujuans as $index => $Tujuan)
                                <tr class="hover:bg-gray-50/50 transition align-top">
                                    <td class="px-6 py-4 text-center font-semibold text-gray-500">
                                        {{ $Tujuans->firstItem() + $index }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $Tujuan->nama_tujuan }}
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <!-- Tombol Edit -->
                                            <button type="button"
                                                @click="setEditData({
                                                    id_tujuan: {{ $Tujuan->id_tujuan }},
                                                    nama: @js($Tujuan->nama_tujuan),
                                                    status: @js($Tujuan->status)
                                                })"
                                                class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                                <span>Edit</span>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <button type="button"
                                                @click="setDeleteData({
                                                    id_tujuan: {{ $Tujuan->id_tujuan }},
                                                    nama: @js($Tujuan->nama_tujuan)
                                                })"
                                                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada data Tujuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($Tujuans->hasPages())
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $Tujuans->firstItem() }} to {{ $Tujuans->lastItem() }} of {{ $Tujuans->total() }} entries
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if ($Tujuans->onFirstPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $Tujuans->previousPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($Tujuans->getUrlRange(1, $Tujuans->lastPage()) as $page => $url)
                                @if ($page == $Tujuans->currentPage())
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

                            @if ($Tujuans->hasMorePages())
                                <a href="{{ $Tujuans->nextPageUrl() }}"
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
                            Showing {{ $Tujuans->count() ? 1 : 0 }} to {{ $Tujuans->count() }} of {{ $Tujuans->total() }} entries
                        </p>
                    </div>
                @endif
            </div>

        </div>

        {{-- INCLUDES MODAL --}}
        @include('super-admin.tujuan.create')
        @include('super-admin.tujuan.edit')
        @include('super-admin.tujuan.delete')

    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush