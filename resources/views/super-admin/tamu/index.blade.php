@extends('super-admin.layouts.app')

@section('title', 'Manajemen Tamu')

@section('content')

<div class="p-6"
     x-data="{
        openDetail: false,
        openDelete: false,
        selected: {
            id: '', kode_tiket: '', nama_lengkap: '', email: '', no_telp: '',
            sub_bagian: '', tujuan: '', pegawai: '', permasalahan: '', solusi: '',
            status_tindak_lanjut: '', status: ''
        },
        updateUrl: '',
        deleteUrl: '',

        setDetail(tamu) {
            this.selected = tamu;
            this.updateUrl = '{{ url('/super-admin/tamu') }}/' + tamu.id;
            this.openDetail = true;
        },

        setDelete(tamu) {
            this.selected = tamu;
            this.deleteUrl = '{{ url('/super-admin/tamu') }}/' + tamu.id;
            this.openDelete = true;
        }
     }">
    <!-- Breadcrumb & Title -->
    <div class="text-gray-500 text-sm mb-1">Dashboard / Tamu</div>
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Tamu</h2>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md p-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Toolbar -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex-1 max-w-lg">
            <form action="{{ route('super-admin.tamu.index') }}" method="GET">
                <div class="flex border border-gray-300 rounded-xl overflow-hidden bg-white shadow-sm">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari Nama / Kode Tiket / Sub Bagian / Tujuan..."
                        class="flex-1 px-5 py-3 outline-none text-sm focus:ring-1 focus:ring-[#173860]"
                    >
                    <button type="submit"
                            class="bg-[#173860] hover:bg-[#102a48] text-white px-6 flex items-center justify-center transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <a href="{{ route('super-admin.tamu.arsip') }}"
           class="flex items-center gap-2 bg-white border border-gray-300 hover:border-gray-400 px-6 py-3 rounded-xl font-semibold text-gray-700 transition">
            <i class="fa fa-box-archive"></i>
            Arsip
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-[#f4ede0]">
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Sub Bagian</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Tujuan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Pegawai</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                    <th class="w-20 px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Cek</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase w-36">Approval</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase w-44">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($tamus as $tamu)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-5 font-semibold">
                            {{ $tamu->nama_lengkap }}
                            <div class="text-xs font-normal text-gray-400">{{ $tamu->kode_tiket }}</div>
                        </td>
                        <td class="px-6 py-5 text-gray-600">{{ $tamu->subBagian->nama_sub_bagian ?? '-' }}</td>
                        <td class="px-6 py-5 text-gray-600">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>
                        <td class="px-6 py-5 text-gray-600">{{ $tamu->pegawai->name ?? '-' }}</td>
                        <td class="px-6 py-5">
                            @php
                                $statusColor = match ($tamu->status_tindak_lanjut) {
                                    'selesai' => 'bg-green-600',
                                    'eskalasi' => 'bg-blue-600',
                                    default => 'bg-gray-400',
                                };
                                $statusLabel = match ($tamu->status_tindak_lanjut) {
                                    'selesai' => 'Selesai',
                                    'eskalasi' => 'Eskalasi',
                                    default => 'Belum Eskalasi',
                                };
                            @endphp
                            <span class="inline-block px-5 py-1.5 text-xs font-bold rounded-full text-white {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <input type="checkbox" class="w-5 h-5 accent-[#0f2a52]" disabled
                                   @checked($tamu->status !== 'menunggu')>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <form action="{{ route('super-admin.tamu.approval', $tamu->id_tamu) }}" method="POST"
                                  onsubmit="return confirm('Ubah status approval tamu ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="approval-btn px-5 py-1.5 text-xs font-bold rounded-full text-white transition w-28
                                        {{ $tamu->approval === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-yellow-500 hover:bg-yellow-600' }}">
                                    {{ $tamu->approval === 'approve' ? 'Approve' : 'Menunggu' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex justify-center gap-3">
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
                                            status: @js($tamu->status)
                                        })"
                                        class="px-5 py-2 text-sm font-semibold border-2 border-[#0f2a52] text-[#0f2a52] rounded-full hover:bg-gray-50 transition">
                                    Detail
                                </button>
                                <button type="button"
                                        @click="setDelete({ id: {{ $tamu->id_tamu }}, nama_lengkap: @js($tamu->nama_lengkap) })"
                                        class="px-5 py-2 text-sm font-semibold bg-red-600 text-white rounded-full hover:bg-red-700 transition">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500 text-sm">
                            Belum ada data tamu yang mengisi Buku Tamu Digital.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="flex items-center justify-between px-6 py-5 bg-gray-50 border-t">
            <div class="text-sm text-gray-600">
                Showing <strong>{{ $tamus->firstItem() ?? 0 }}</strong> to <strong>{{ $tamus->lastItem() ?? 0 }}</strong> of <strong>{{ $tamus->total() }}</strong> entries
            </div>
            <div class="flex gap-2
                [&_a]:!bg-white [&_a]:!text-gray-700 [&_a]:!border [&_a]:!border-gray-300 [&_a]:!rounded-lg [&_a]:!px-4 [&_a]:!py-2 [&_a]:!text-sm hover:[&_a]:!bg-gray-100
                [&_span[aria-current='page']>span]:!bg-[#173860] [&_span[aria-current='page']>span]:!text-white [&_span[aria-current='page']>span]:!rounded-lg [&_span[aria-current='page']>span]:!px-4 [&_span[aria-current='page']>span]:!py-2 [&_span[aria-current='page']>span]:!text-sm
                [&_span[aria-disabled='true']>span]:!hidden">
                {{ $tamus->links() }}
            </div>
        </div>
    </div>

    <!-- Include Modals: HARUS di dalam div x-data agar bisa akses openDetail/openDelete -->
    @include('super-admin.tamu.show')
    @include('super-admin.tamu.delete')
</div>

@endsection