@extends('super-admin.layouts.app')

@section('title', 'Arsip Tujuan')

@section('content')

    <div x-data="{
        openRestore: false,
        selectedSub: {
            id: '',
            nama: ''
        },
    
        setRestoreData(sub) {
            this.selectedSub = {
                id: sub.id_tujuan || sub.id,
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

            {{-- Header --}}
            <div>
                <nav class="text-xs text-gray-500 mb-1">
                    <span>Dashboard</span>
                    <span>/</span>
                    <span>Tujuan</span>
                    <span>/</span>
                    <span class="font-semibold text-gray-700">Arsip</span>
                </nav>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Arsip Data Tujuan</h2>
            </div>

            {{-- Search & Button --}}
            <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col lg:flex-row justify-between items-center gap-4">
                <form action="{{ route('tujuan.arsip') }}" method="GET" class="flex-1 w-full max-w-md">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Tujuan..."
                            class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                        <button type="submit" class="bg-[#173860] hover:bg-[#102a48] text-white px-4 rounded-r-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="flex gap-3">
                    <a href="{{ route('tujuan.index') }}"
                        class="bg-[#080d1a] hover:bg-[#173860] text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Card Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Arsip Tujuan</h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $Tujuans->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
                            <tr>
                                <th scope="col" class="px-8 py-4 text-center w-24">No</th>
                                <th scope="col" class="px-8 py-4 text-left">Nama Tujuan</th>
                                <th scope="col" class="px-8 py-4 text-center w-56">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($Tujuans as $index => $Tujuan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4 text-center text-sm text-gray-600">
                                        {{ $Tujuans->firstItem() + $index }}
                                    </td>

                                    <td class="px-8 py-4 text-left text-sm font-semibold text-gray-900">
                                        {{ $Tujuan->nama_tujuan }}
                                    </td>

                                    <td class="px-8 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <button type="button"
                                                @click="setRestoreData({
                                                    id_tujuan: {{ $Tujuan->id_tujuan }},
                                                    nama: @js($Tujuan->nama_tujuan)
                                                })"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-white text-xs font-semibold transition">
                                                Pulihkan
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-12 text-center text-gray-500 text-sm">
                                        Belum ada data Arsip Tujuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-6 py-4 border-t border-gray-200 bg-white 
                    [&_a]:!bg-white [&_a]:!text-black [&_a]:!border [&_a]:!border-gray-400 hover:[&_a]:!bg-gray-100
                    [&_span[aria-current='page']>span]:!bg-gray-800 [&_span[aria-current='page']>span]:!text-white [&_span[aria-current='page']>span]:!border [&_span[aria-current='page']>span]:!border-gray-800
                    [&_span[aria-disabled='true']>span]:!bg-white [&_span[aria-disabled='true']>span]:!text-gray-400 [&_span[aria-disabled='true']>span]:!border [&_span[aria-disabled='true']>span]:!border-gray-300">
                    {{ $Tujuans->links() }}
                </div>
            </div>

        </div>

        {{-- INCLUDE MODAL PULIHKAN --}}
        @include('super-admin.tujuan.pulihkan')

    </div>

@endsection