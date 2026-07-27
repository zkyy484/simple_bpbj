@extends('super-admin.layouts.app')

@section('title', 'Arsip Data Tamu')

@section('content')

<div class="p-6"
     x-data="{
        openPulihkan: false,
        selected: { id: '', nama_lengkap: '' },
        pulihkanUrl: '',

        setPulihkan(tamu) {
            this.selected = tamu;
            this.pulihkanUrl = '{{ url('/super-admin/tamu') }}/' + tamu.id + '/pulihkan';
            this.openPulihkan = true;
        }
     }">
    <!-- Breadcrumb & Title -->
    <div class="text-gray-500 text-sm mb-1">Dashboard / Akun</div>
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Arsip Data Tamu</h2>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md p-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-6">
        <div class="flex-1 max-w-lg">
            <form action="{{ route('super-admin.tamu.arsip') }}" method="GET">
                <div class="flex border border-gray-300 rounded-xl overflow-hidden bg-white shadow-sm">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari Data Tamu..."
                        class="flex-1 px-5 py-3 outline-none text-sm focus:ring-1 focus:ring-[#173860] min-w-0"
                    >
                    <button type="submit" 
                            class="bg-[#173860] hover:bg-[#102a48] text-white px-6 flex items-center justify-center transition shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <a href="{{ route('super-admin.tamu.index') }}" 
           class="px-6 py-3 bg-[#173860] hover:bg-[#102a48] text-white font-semibold rounded-xl transition text-center whitespace-nowrap shrink-0">
            Kembali
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase whitespace-nowrap">NAMA</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase whitespace-nowrap">TUJUAN</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase whitespace-nowrap">PEGAWAI</th>
                        <th class="w-12 px-4 py-3"></th> <!-- Checkbox -->
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase whitespace-nowrap">STATUS</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase whitespace-nowrap w-36">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($tamus as $tamu)
                        @php
                            $statusColor = match ($tamu->status_tindak_lanjut) {
                                'selesai' => 'bg-green-600',
                                'eskalasi' => 'bg-red-600',
                                default => 'bg-gray-400',
                            };
                            $statusLabel = match ($tamu->status_tindak_lanjut) {
                                'selesai' => 'Selesai',
                                'eskalasi' => 'Eskalasi',
                                default => 'Menunggu',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 align-top">
                                <div class="max-w-[180px] truncate font-medium">{{ $tamu->nama_lengkap }}</div>
                                <div class="text-xs font-normal text-gray-400">{{ $tamu->kode_tiket }}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-600 align-top">
                                <div class="max-w-[150px] truncate">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-600 align-top">
                                <div class="max-w-[150px] truncate">{{ $tamu->pegawai->name ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4 text-center align-top">
                                <input type="checkbox" class="w-5 h-5 accent-[#173860]" disabled
                                       @checked($tamu->status !== 'menunggu')>
                            </td>
                            <td class="px-4 py-4 text-center align-top">
                                <span class="inline-block px-4 py-1 text-xs font-bold rounded-full text-white whitespace-nowrap {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center align-top">
                                <button type="button"
                                        @click="setPulihkan({ id: {{ $tamu->id_tamu }}, nama_lengkap: @js($tamu->nama_lengkap) })"
                                        class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium transition mx-auto whitespace-nowrap">
                                    <i class="fa fa-undo"></i>
                                    Pulihkan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">
                                Belum ada data tamu yang diarsipkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-5 bg-gray-50 border-t">
            <div class="text-sm text-gray-600 whitespace-nowrap">
                Showing <strong>{{ $tamus->firstItem() ?? 0 }}</strong> to <strong>{{ $tamus->lastItem() ?? 0 }}</strong> of <strong>{{ $tamus->total() }}</strong> entries
            </div>
            <div class="flex flex-wrap gap-2
                [&_a]:!bg-white [&_a]:!text-gray-700 [&_a]:!border [&_a]:!border-gray-300 [&_a]:!rounded-lg [&_a]:!px-4 [&_a]:!py-2 [&_a]:!text-sm hover:[&_a]:!bg-gray-100
                [&_span[aria-current='page']>span]:!bg-[#173860] [&_span[aria-current='page']>span]:!text-white [&_span[aria-current='page']>span]:!rounded-lg [&_span[aria-current='page']>span]:!px-4 [&_span[aria-current='page']>span]:!py-2 [&_span[aria-current='page']>span]:!text-sm
                [&_span[aria-disabled='true']>span]:!hidden">
                {{ $tamus->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Pulihkan -->
    @include('super-admin.tamu.pulihkan')
</div>

@endsection