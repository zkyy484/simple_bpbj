@extends('super-admin.layouts.app')

@section('title', 'Laporan Buku Tamu - Buku Tamu Digital')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500">
        <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
        <span class="mx-1">/</span>
        <span>Laporan</span>
        <span class="mx-1">/</span>
        <span class="text-gray-700 font-medium">Laporan Buku Tamu</span>
    </div>

    <h1 class="text-3xl font-bold text-gray-900">Laporan Buku Tamu</h1>

    <!-- Filter Card -->
    <form method="GET" action="{{ route('laporan.buku-tamu.index') }}" id="filter-form"
        class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-end gap-5">

            {{-- Input Tanggal Awal (Auto Submit) --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase mb-2">
                    Tanggal Awal
                </label>
                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                    onchange="this.form.submit()"
                    class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
            </div>

            {{-- Input Tanggal Akhir (Auto Submit) --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase mb-2">
                    Tanggal Akhir
                </label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                    onchange="this.form.submit()"
                    class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
            </div>

            {{-- Dropdown Status (Auto Submit) --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase mb-2">
                    Status
                </label>
                <select name="status" onchange="this.form.submit()"
                    class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                    <option value="">Semua status</option>
                    <option value="belum_eskalasi" @selected(request('status') === 'belum_eskalasi')>Belum Eskalasi</option>
                    <option value="eskalasi" @selected(request('status') === 'eskalasi')>Eskalasi</option>
                    <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                </select>
            </div>

            {{-- Action Buttons (Export PDF & Reset Filter) --}}
            <div class="flex gap-3 shrink-0 items-center">
                @if (request()->hasAny(['tanggal_awal', 'tanggal_akhir', 'status']))
                    <a href="{{ route('laporan.buku-tamu.index') }}"
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold tracking-wide rounded-lg transition whitespace-nowrap">
                        RESET FILTER
                    </a>
                @endif

                <button type="submit" formaction="{{ route('laporan.buku-tamu.export') }}"
                    class="px-5 py-2.5 bg-red-600 text-white text-xs font-bold tracking-wide rounded-lg hover:bg-red-700 transition flex items-center gap-2 whitespace-nowrap shadow-sm">
                    <i data-lucide="file-down" class="w-4 h-4"></i> EXPORT DOKUMEN PDF
                </button>
            </div>
        </div>
    </form>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5">ID</th>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-6 py-3.5">No.Tiket</th>
                        <th class="px-6 py-3.5">Nama</th>
                        <th class="px-6 py-3.5">Sub Bagian</th>
                        <th class="px-6 py-3.5">Tujuan</th>
                        <th class="px-6 py-3.5">Pegawai</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($tamus as $tamu)
                        @php
                            $statusMap = [
                                'belum_eskalasi' => ['label' => 'Belum Eskalasi', 'class' => 'bg-gray-300 text-gray-700'],
                                'eskalasi' => ['label' => 'Eskalasi', 'class' => 'bg-lime-400 text-lime-900'],
                                'selesai' => ['label' => 'Selesai', 'class' => 'bg-emerald-500 text-white'],
                            ];
                            $badge = $statusMap[$tamu->status_tindak_lanjut] ?? ['label' => '-', 'class' => 'bg-gray-200 text-gray-600'];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition align-top">
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $tamu->id_tamu }}</td>
                            <td class="px-6 py-4 text-gray-700 whitespace-nowrap">{{ $tamu->created_at->format('d-m-Y') }}</td>
                            <td class="px-6 py-4 text-gray-700 whitespace-nowrap">{{ $tamu->kode_tiket }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $tamu->nama_lengkap }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $tamu->subBagian->nama_sub_bagian ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $tamu->pegawai->nama_lengkap ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 {{ $badge['class'] }} rounded-full text-[11px] font-bold whitespace-nowrap">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                                Tidak ada data buku tamu untuk filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($tamus->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    Showing {{ $tamus->firstItem() }} to {{ $tamus->lastItem() }} of {{ $tamus->total() }} entries
                </p>
                <div class="flex items-center gap-1.5">
                    @if ($tamus->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </span>
                    @else
                        <a href="{{ $tamus->previousPageUrl() }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </a>
                    @endif

                    @foreach ($tamus->getUrlRange(1, $tamus->lastPage()) as $page => $url)
                        @if ($page == $tamus->currentPage())
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

                    @if ($tamus->hasMorePages())
                        <a href="{{ $tamus->nextPageUrl() }}"
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
                    Showing {{ $tamus->count() ? 1 : 0 }} to {{ $tamus->count() }} of {{ $tamus->total() }} entries
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush