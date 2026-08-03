@extends('super-admin.layouts.app')

@section('title', 'Arsip Survei')

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

        <div class="space-y-6 transition-all duration-300"
            :class="{
                'blur-sm pointer-events-none select-none scale-[0.99]': openRestore
            }">

            {{-- Header --}}
            <div>
                <nav class="text-xs text-gray-500 mb-1">
                    <span>Dashboard</span>
                    <span>/</span>
                    <span>Survei</span>
                    <span>/</span>
                    <span class="font-semibold text-gray-700">Arsip</span>
                </nav>

                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Arsip Data Survei
                </h2>
            </div>

            {{-- Search --}}
            <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col lg:flex-row justify-between items-center gap-4">

                <form action="{{ route('survei.arsip') }}" method="GET" class="flex-1 w-full max-w-md">

                    <div class="flex">

                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Responden..."
                            class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none">

                        <button type="submit" class="bg-[#173860] hover:bg-[#102a48] text-white px-4 rounded-r-lg">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                            </svg>

                        </button>

                    </div>

                </form>

                <a href="{{ route('survei.index') }}"
                    class="bg-[#080d1a] hover:bg-[#173860] text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center gap-2">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />

                    </svg>

                    <span>Kembali</span>

                </a>

            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="px-6 py-5 border-b flex items-center justify-between">

                    <h3 class="text-lg font-bold text-gray-900">
                        Daftar Arsip Survei
                    </h3>

                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">

                        Total : {{ $respons->total() }}

                    </span>

                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">

                            <tr>

                                <th class="px-6 py-4 text-center w-16">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left">
                                    Nama
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Email
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Instansi
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-center w-48">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">

                            @forelse($respons as $index => $respon)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 text-center">

                                        {{ $respons->firstItem() + $index }}

                                    </td>

                                    <td class="px-6 py-4 font-semibold">

                                        {{ $respon->nama_lengkap }}

                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        {{ $respon->email ?? '-' }}

                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        {{ $respon->instansi ?? '-' }}

                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        @if ($respon->cek == 'approve')
                                            <span
                                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">

                                                APPROVE

                                            </span>
                                        @else
                                            <span
                                                class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">

                                                MENUNGGU

                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            @click="setRestoreData({
            id: {{ $respon->id_respon }},
            nama: @js($respon->nama_lengkap)
        })"
                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-white text-xs font-semibold transition flex items-center gap-1.5 mx-auto">

                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>

                                            <span>Pulihkan</span>
                                        </button>
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center py-10 text-gray-500">

                                        Belum ada arsip survei.

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

                    {{ $respons->links() }}

                </div>

            </div>

        </div>

        {{-- Modal Restore --}}
        @include('super-admin.survei.data.pulihkan')

    </div>
@endsection
