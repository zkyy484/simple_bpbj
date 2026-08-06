@extends('super-admin.layouts.app')

@section('title', 'Manajemen Pertanyaan Survei - Buku Tamu Digital')

@section('content')
<div x-data="{
        openCreate: Boolean({{ $errors->any() && old('form_type') == 'create' ? 1 : 0 }}),
        openEdit: Boolean({{ $errors->any() && old('form_type') == 'edit' ? 1 : 0 }}),
        openDelete: false,
        selectedItem: {{ $errors->any() && old('form_type') == 'edit'
            ? Js::from([
                'id' => old('id_pertanyaan'),
                'pertanyaan' => old('pertanyaan'),
                'tipe_pertanyaan' => old('tipe_pertanyaan'),
                'urutan' => old('urutan'),
                'opsi' => old('opsi') ?? [],
            ])
            : '{}' }}
    }" class="relative">

    <!-- CONTENT MAIN -->
    <div class="space-y-6 transition-all duration-300"
        :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openCreate || openEdit || openDelete }">

        {{-- Breadcrumb & Title --}}
        <div>
            <div class="text-sm text-gray-500 mb-1">
                <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700 font-medium">Pertanyaan</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Pertanyaan Survei</h1>
        </div>

        {{-- Search & Action Bar --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-5">
            <form action="{{ route('survei.index') }}" method="GET" class="flex-1 w-full max-w-lg">
                <div class="relative flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Pertanyaan Survei..."
                        class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                    <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            <div class="flex gap-3 shrink-0">
                <a href="{{ route('pertanyaan.arsip') }}"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="archive" class="w-4 h-4 text-gray-600"></i>
                    <span>ARSIP</span>
                </a>

                <button type="button" @click="openCreate = true"
                    class="px-5 py-2.5 bg-[#173860] hover:bg-[#12294a] text-white text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>TAMBAH PERTANYAAN</span>
                </button>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900">Daftar Pertanyaan</h3>
                <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                    Total : {{ $pertanyaans->total() ?? 0 }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5 text-center w-16">Urutan</th>
                            <th class="px-6 py-3.5">Pertanyaan</th>
                            <th class="px-6 py-3.5 text-center">Tipe</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pertanyaans as $p)
                        <tr class="hover:bg-gray-50/50 transition align-top">
                            <td class="px-6 py-4 text-center font-semibold text-gray-500">{{ $p->urutan }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $p->pertanyaan }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-[11px] font-bold text-white rounded-full bg-[#173860] whitespace-nowrap">
                                    {{ strtoupper($p->tipe_pertanyaan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700 font-medium">
                                <span class="px-3 py-1 text-[11px] font-bold rounded-full {{ strtolower($p->status) === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Edit -->
                                    <button type="button"
                                        @click="
                                            selectedItem = {
                                                id: '{{ $p->id_pertanyaan }}',
                                                pertanyaan: {{ json_encode($p->pertanyaan) }},
                                                tipe_pertanyaan: '{{ $p->tipe_pertanyaan }}',
                                                urutan: {{ $p->urutan ?? 0 }},
                                                opsi: {{ json_encode($p->opsi) }}
                                            };
                                            openEdit = true;
                                        "
                                        class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        <span>Edit</span>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                        @click="
                                            selectedItem = {
                                                id: '{{ $p->id_pertanyaan }}',
                                                pertanyaan: {{ json_encode($p->pertanyaan) }}
                                            };
                                            openDelete = true;
                                        "
                                        class="px-3 py-1.5 bg-red-600 hover:bg-red-700 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                Belum ada pertanyaan survei.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($pertanyaans->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        Showing {{ $pertanyaans->firstItem() }} to {{ $pertanyaans->lastItem() }} of {{ $pertanyaans->total() }} entries
                    </p>
                    <div class="flex items-center gap-1.5">
                        @if ($pertanyaans->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </span>
                        @else
                            <a href="{{ $pertanyaans->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </a>
                        @endif

                        @foreach ($pertanyaans->getUrlRange(1, $pertanyaans->lastPage()) as $page => $url)
                            @if ($page == $pertanyaans->currentPage())
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

                        @if ($pertanyaans->hasMorePages())
                            <a href="{{ $pertanyaans->nextPageUrl() }}"
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
                        Showing {{ $pertanyaans->count() ? 1 : 0 }} to {{ $pertanyaans->count() }} of {{ $pertanyaans->total() }} entries
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- INCLUDES MODAL --}}
    @include('super-admin.survei.create')
    @include('super-admin.survei.edit')
    @include('super-admin.survei.delete')
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush