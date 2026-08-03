@extends('admin.layouts.app')

@section('title', 'Manajemen Tamu')

@section('content')
    <div x-data="{
        openDetail: false,
        openDelete: false,
        openApprove: false,
        selected: {
            id: '', kode_tiket: '', nama_lengkap: '', email: '', no_telp: '',
            sub_bagian: '', tujuan: '', pegawai: '', permasalahan: '', solusi: '',
            status_tindak_lanjut: '', status: '', approval: ''
        },
        updateUrl: '',
        deleteUrl: '',
        approveUrl: '',

        setDetail(tamu) {
            this.selected = tamu;
            this.updateUrl = '{{ url('/admin/tamu') }}/' + tamu.id;
            this.openDetail = true;
        },

        setDelete(tamu) {
            this.selected = tamu;
            this.deleteUrl = '{{ url('/admin/tamu') }}/' + tamu.id;
            this.openDelete = true;
        },

        setApprove(tamu) {
            this.selected = tamu;
            this.approveUrl = '{{ url('/admin/tamu') }}/' + tamu.id + '/approval';
            this.openApprove = true;
        }
    }" class="relative">

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openDetail || openDelete || openApprove }">

            {{-- Header --}}
            <div>
                <nav class="text-xs text-gray-500 mb-1">
                    <span>Dashboard</span>
                    <span>/</span>
                    <span class="font-semibold text-gray-700">Tamu</span>
                </nav>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Tamu</h2>
            </div>

            {{-- Search & Button --}}
            <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col lg:flex-row justify-between items-center gap-4">
                <form action="{{ route('admin.tamu.index') }}" method="GET" class="flex-1 w-full max-w-lg">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama / Kode Tiket / Sub Bagian / Tujuan..."
                            class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                        <button type="submit" class="bg-[#173860] hover:bg-[#102a48] text-white px-4 rounded-r-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Card Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Tamu</h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $tamus->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left">Nama</th>
                                <th scope="col" class="px-6 py-4 text-left">Sub Bagian</th>
                                <th scope="col" class="px-6 py-4 text-left">Tujuan</th>
                                <th scope="col" class="px-6 py-4 text-left">Pegawai</th>
                                <th scope="col" class="px-6 py-4 text-left">Status</th>
                                <th scope="col" class="px-6 py-4 text-center w-20">Cek</th>
                                <th scope="col" class="px-6 py-4 text-center w-36">Approval</th>
                                <th scope="col" class="px-6 py-4 text-center w-48">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($tamus as $tamu)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        {{ $tamu->nama_lengkap }}
                                        <div class="text-xs font-normal text-gray-400">{{ $tamu->kode_tiket }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $tamu->subBagian->nama_sub_bagian ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $tamu->pegawai->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $statusColor = match ($tamu->status_tindak_lanjut) {
                                                'selesai' => 'bg-green-100 text-green-700 border border-green-200',
                                                'eskalasi' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                                default => 'bg-gray-100 text-gray-600 border border-gray-200',
                                            };
                                            $statusLabel = match ($tamu->status_tindak_lanjut) {
                                                'selesai' => 'Selesai',
                                                'eskalasi' => 'Eskalasi',
                                                default => 'Belum Eskalasi',
                                            };
                                        @endphp
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" class="w-4 h-4 text-[#173860] rounded border-gray-300 focus:ring-[#173860]" disabled
                                            @checked($tamu->status !== 'menunggu')>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <!-- Tombol Pemicu Modal Approval -->
                                        <button type="button"
                                            @click="setApprove({ 
                                                id: {{ $tamu->id_tamu }}, 
                                                nama_lengkap: @js($tamu->nama_lengkap),
                                                approval: @js($tamu->approval)
                                            })"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg text-white transition w-28 shadow-sm
                                            {{ $tamu->approval === 'approve' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-amber-500 hover:bg-amber-600' }}">
                                            {{ $tamu->approval === 'approve' ? 'Approved' : 'Menunggu' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <!-- Tombol Detail -->
                                            <button type="button"
                                                @click="setDetail({
                                                    id: {{ $tamu->id_tamu }},
                                                    kode_tiket: @js($tamu->kode_tiket),
                                                    nama_lengkap: @js($tamu->nama_lengkap),
                                                    email: @js($tamu->email ?? '-'),
                                                    no_telp: @js($tamu->nomor_telepon ?? '-'),
                                                    sub_bagian: @js($tamu->subBagian->nama_sub_bagian ?? '-'),
                                                    tujuan: @js($tamu->tujuan->nama_tujuan ?? '-'),
                                                    pegawai: @js($tamu->pegawai->name ?? '-'),
                                                    permasalahan: @js($tamu->permasalahan ?? '-'),
                                                    solusi: @js($tamu->solusi ?? ''),
                                                    status_tindak_lanjut: @js($tamu->status_tindak_lanjut),
                                                    status: @js($tamu->status),
                                                    approval: @js($tamu->approval)
                                                })"
                                                class="px-3 py-1.5 bg-[#173860] hover:bg-[#102a48] rounded-lg text-white text-xs font-semibold transition flex items-center gap-1 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>Detail</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-8 py-12 text-center text-gray-500 text-sm">
                                        Belum ada data tamu yang mengisi Buku Tamu Digital.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 bg-white 
                    [&_a]:!bg-white [&_a]:!text-black [&_a]:!border [&_a]:!border-gray-400 hover:[&_a]:!bg-gray-100
                    [&_span[aria-current='page']>span]:!bg-gray-800 [&_span[aria-current='page']>span]:!text-white [&_span[aria-current='page']>span]:!border [&_span[aria-current='page']>span]:!border-gray-800
                    [&_span[aria-disabled='true']>span]:!bg-white [&_span[aria-disabled='true']>span]:!text-gray-400 [&_span[aria-disabled='true']>span]:!border [&_span[aria-disabled='true']>span]:!border-gray-300">
                    {{ $tamus->links() }}
                </div>
            </div>

        </div>

        {{-- INCLUDES MODAL --}}
        @include('admin.tamu.show')
        @include('admin.tamu.approve')

    </div>
@endsection