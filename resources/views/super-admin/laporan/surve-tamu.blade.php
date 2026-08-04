@extends('super-admin.layouts.app')

@section('title', 'Laporan Survei')

@section('content')
    <div x-data="{
        openDelete: false,
        openDetail: false,
        loadingDetail: false,
        detailContent: '',
        selectedItem: {},

        async loadDetail(id) {
            this.openDetail = true;
            this.loadingDetail = true;
            this.detailContent = '';
            try {
                const res = await fetch(`{{ route('laporan.survei.index') }}?id_respon=${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) throw new Error('Gagal memuat data');
                this.detailContent = await res.text();
            } catch (e) {
                this.detailContent = '<p class=\'text-red-600 text-sm text-center py-10\'>Gagal memuat detail survei.</p>';
            } finally {
                this.loadingDetail = false;
            }
        }
    }" class="relative">
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openDetail || openDelete }">

            {{-- Breadcrumb & Title --}}
            <div>
                <nav class="text-xs text-gray-500 mb-1">
                    <span>Dashboard</span> <span>/</span> <span>Laporan</span> <span>/</span>
                    <span class="font-semibold text-gray-700">Laporan Survei</span>
                </nav>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Laporan Survei</h2>
            </div>

            {{-- Filter Bar --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <form action="{{ route('laporan.survei.index') }}" method="GET"
                    class="flex flex-col lg:flex-row lg:items-end gap-4">

                    <div class="flex-1 w-full">
                        <label class="block text-[11px] font-bold tracking-wide text-gray-500 mb-1.5">
                            TANGGAL AWAL
                        </label>
                        <div class="relative">
                            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                                class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-[#173860] outline-none">
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 w-full">
                        <label class="block text-[11px] font-bold tracking-wide text-gray-500 mb-1.5">
                            TANGGAL AKHIR
                        </label>
                        <div class="relative">
                            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                                class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-[#173860] outline-none">
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 w-full">
                        <label class="block text-[11px] font-bold tracking-wide text-gray-500 mb-1.5">
                            DETEKSI
                        </label>
                        <select name="deteksi"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-[#173860] outline-none bg-white">
                            <option value="" {{ request('deteksi') == '' ? 'selected' : '' }}>Semua Opsi</option>
                            <option value="normal" {{ request('deteksi') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="anomali" {{ request('deteksi') == 'anomali' ? 'selected' : '' }}>Anomali</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full lg:w-auto">
                        <a href="{{ route('laporan.survei.export', request()->query()) }}"
                            class="flex-1 lg:flex-none bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide transition flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                            </svg>
                            EXPORT DOKUMEN PDF
                        </a>

                        <button type="submit"
                            class="bg-[#173860] hover:bg-[#102a48] text-white px-6 py-2.5 rounded-lg text-sm font-bold transition whitespace-nowrap">
                            FILTER
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#f5f1ea] text-gray-700 text-xs font-bold tracking-wide">
                            <tr>
                                <th class="px-6 py-4 text-center w-16">ID</th>
                                <th class="px-6 py-4 text-left">NAMA</th>
                                <th class="px-6 py-4 text-center">EMAIL</th>
                                <th class="px-6 py-4 text-center">INSTANSI</th>
                                <th class="px-6 py-4 text-center">STATUS</th>
                                <th class="px-6 py-4 text-center w-48">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($respons as $respon)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-center text-sm text-gray-700">
                                        {{ $loop->iteration + ($respons->firstItem() - 1) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 text-sm">
                                            {{ $respon->nama_lengkap }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center text-sm text-gray-700">
                                        {{ $respon->email ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-sm text-gray-700">
                                        {{ $respon->instansi ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($respon->cek === 'approve')
                                            <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                Approve
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            <button type="button" @click="loadDetail('{{ $respon->id_respon }}')"
                                                class="px-4 py-1.5 rounded-full border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-50 transition">
                                                Detail
                                            </button>

                                            <button type="button"
                                                @click="
                                                    selectedItem = {
                                                        id: '{{ $respon->id_respon }}',
                                                        nama: '{{ $respon->nama_lengkap }}'
                                                    };
                                                    openDelete = true;
                                                "
                                                class="px-4 py-1.5 rounded-full bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-500 text-sm">
                                        Belum ada data laporan survei.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination footer --}}
                <div class="px-6 py-4 border-t flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Showing {{ $respons->firstItem() ?? 0 }} to {{ $respons->lastItem() ?? 0 }}
                        of {{ $respons->total() }} entries
                    </p>
                    {{ $respons->links() }}
                </div>
            </div>
        </div>

        @include('super-admin.survei.delete')
        @include('super-admin.survei.detail')
    </div>
@endsection