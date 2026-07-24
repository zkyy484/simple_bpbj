@extends('super-admin.layouts.app')

@section('title', 'Manajemen Sub Bagian')

@section('content')

    <div x-data="{
        openCreate: Boolean({{ $errors->any() ? 1 : 0 }}),
        openEdit: false,
        openDelete: false,
        selectedSub: {
            id_sub_bagian: '',
            nama_sub_bagian: '',
            status: ''
        },
        editUrl: '',
        deleteUrl: '',
    
        setEditData(sub) {
            this.selectedSub = {
                id: sub.id_sub_bagian || sub.id,
                nama: sub.nama,
                status: sub.status
            };
            this.editUrl = '{{ url('/super-admin/sub-bagian') }}/' + (sub.id_sub_bagian || sub.id);
            this.openEdit = true;
        },
    
        setDeleteData(sub) {
            this.selectedSub = {
                id: sub.id_sub_bagian || sub.id,
                nama: sub.nama
            };
            this.deleteUrl = '{{ url('/super-admin/sub-bagian') }}/' + (sub.id_sub_bagian || sub.id);
            this.openDelete = true;
        }
    }" class="relative">


        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{
                'blur-sm pointer-events-none select-none scale-[0.99]': openCreate || openEdit || openDelete
            }">

            {{-- Header --}}
            <div>
                <nav class="text-xs text-gray-500 mb-1">
                    <span>Dashboard</span>
                    <span>/</span>
                    <span class="font-semibold text-gray-700">Sub Bagian</span>
                </nav>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Sub Bagian</h2>
            </div>

            {{-- Search & Button --}}
            <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col lg:flex-row justify-between items-center gap-4">
                <form action="{{ route('index.sub') }}" method="GET" class="flex-1 w-full max-w-md">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Sub Bagian..."
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
                    <a href="{{ route('arsip.sub') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2.5 rounded-lg text-sm font-semibold">
                        Arsip
                    </a>

                    <button type="button" @click="openCreate = true"
                        class="bg-[#080d1a] hover:bg-[#173860] text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                        + Tambah Data
                    </button>
                </div>
            </div>

            {{-- Card Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Sub Bagian</h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $subBagians->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
                            <tr>
                                <!-- Kolom Nomor: Dibuat ringkas di sisi kiri -->
                                <th scope="col" class="px-8 py-4 text-center w-24">No</th>

                                <!-- Kolom Nama Sub Bagian: Mengambil sisa ruang secara alami -->
                                <th scope="col" class="px-8 py-4 text-left">Nama Sub Bagian</th>

                                <!-- Kolom Aksi: Diberi batas lebar agar tombol rapi di kanan -->
                                <th scope="col" class="px-8 py-4 text-center w-56">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($subBagians as $index => $subBagian)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4 text-center text-sm text-gray-600">
                                        {{ $subBagians->firstItem() + $index }}
                                    </td>

                                    <td class="px-8 py-4 text-left text-sm font-semibold text-gray-900">
                                        {{ $subBagian->nama_sub_bagian }}
                                    </td>

                                    <td class="px-8 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <button type="button"
                                                @click="setEditData({
                                id_sub_bagian: {{ $subBagian->id_sub_bagian }},
                                nama: @js($subBagian->nama_sub_bagian),
                                status: @js($subBagian->status)
                            })"
                                                class="px-3 py-1.5 bg-amber-400 hover:bg-amber-500 rounded-lg text-white text-xs font-semibold transition">
                                                Edit
                                            </button>

                                            <button type="button"
                                                @click="setDeleteData({
                                id_sub_bagian: {{ $subBagian->id_sub_bagian }},
                                nama: @js($subBagian->nama_sub_bagian)
                            })"
                                                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 rounded-lg text-white text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-12 text-center text-gray-500 text-sm">
                                        Belum ada data Sub Bagian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-6 py-4 border-t border-grey bg-white 
            [&_a]:!bg-white [&_a]:!text-black [&_a]:!border [&_a]:!border-gray-400 hover:[&_a]:!bg-gray-100
            [&_span[aria-current='page']>span]:!bg-gray-800 [&_span[aria-current='page']>span]:!text-white [&_span[aria-current='page']>span]:!border [&_span[aria-current='page']>span]:!border-gray-800
            [&_span[aria-disabled='true']>span]:!bg-white [&_span[aria-disabled='true']>span]:!text-gray-400 [&_span[aria-disabled='true']>span]:!border [&_span[aria-disabled='true']>span]:!border-gray-300">
                    {{ $subBagians->links() }}
                </div>
            </div>

        </div>

        {{-- INCLUDES MODAL --}}
        @include('super-admin.sub_bagian.create')
        @include('super-admin.sub_bagian.edit')
        @include('super-admin.sub_bagian.delete')

    </div>

@endsection
