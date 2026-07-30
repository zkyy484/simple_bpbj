@extends('super-admin.layouts.app')

@section('title', 'Laporan Pengunjung - Buku Tamu Digital')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500">
        <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
        <span class="mx-1">/</span>
        <span>Laporan</span>
        <span class="mx-1">/</span>
        <span class="text-gray-700 font-medium">Laporan Pengunjung</span>
    </div>

    <h1 class="text-3xl font-bold text-gray-900">Laporan Pengunjung</h1>

    <!-- Filter Card -->
    <form method="GET" action="{{ route('laporan.pengunjung.index') }}" id="filter-form"
        class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-end gap-5">

            <div class="flex-1 min-w-[160px]">
                <label class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase mb-2">
                    Tanggal Awal
                </label>
                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                    class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex-1 min-w-[160px]">
                <label class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase mb-2">
                    Tanggal Akhir
                </label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                    class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex-1 min-w-[160px]">
                <label class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase mb-2">
                    Pelaku Usaha
                </label>
                <select name="pelaku_usaha"
                    class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                    <option value="">Semua Pelaku Usaha</option>
                    <option value="Pelaku Usaha" @selected(request('pelaku_usaha') === 'Pelaku Usaha')>Pelaku Usaha</option>
                    <option value="Instansi Pemerintah" @selected(request('pelaku_usaha') === 'Instansi Pemerintah')>Instansi Pemerintahan</option>
                </select>
            </div>

            <div class="flex gap-3 shrink-0">
                <button type="submit" formaction="{{ route('laporan.pengunjung.export') }}"
                    class="px-5 py-2.5 bg-red-600 text-white text-xs font-bold tracking-wide rounded-lg hover:bg-red-700 transition flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="file-down" class="w-4 h-4"></i> EXPORT DOKUMEN PDF
                </button>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#173860] text-white text-xs font-bold tracking-wide rounded-lg hover:bg-[#12294a] transition whitespace-nowrap">
                    FILTER
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
                        <th class="px-6 py-3.5">Nama</th>
                        <th class="px-6 py-3.5">Email</th>
                        <th class="px-6 py-3.5">Nomor</th>
                        <th class="px-6 py-3.5">Perusahaan</th>
                        <th class="px-6 py-3.5">Pelaku Usaha</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pengunjungs as $pengunjung)
                        @php
                            $statusMap = [
                                'belum_eskalasi' => ['label' => 'Belum Ditindak', 'class' => 'bg-gray-300 text-gray-700'],
                                'eskalasi' => ['label' => 'Ditindak', 'class' => 'bg-lime-400 text-lime-900'],
                                'selesai' => ['label' => 'Selesai', 'class' => 'bg-emerald-500 text-white'],
                            ];
                            $badge = $statusMap[$pengunjung->status_tindak_lanjut] ?? ['label' => '-', 'class' => 'bg-gray-200 text-gray-600'];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition align-top">
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $pengunjung->id_tamu }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $pengunjung->nama_lengkap }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $pengunjung->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700 whitespace-nowrap">{{ $pengunjung->nomor_telepon ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $pengunjung->nama_perusahaan ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $pengunjung->jenis_permohonan ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 {{ $badge['class'] }} rounded-full text-[11px] font-bold whitespace-nowrap">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                Tidak ada data pengunjung untuk filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($pengunjungs->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    Showing {{ $pengunjungs->firstItem() }} to {{ $pengunjungs->lastItem() }} of {{ $pengunjungs->total() }} entries
                </p>
                <div class="flex items-center gap-1.5">
                    @if ($pengunjungs->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </span>
                    @else
                        <a href="{{ $pengunjungs->previousPageUrl() }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </a>
                    @endif

                    @foreach ($pengunjungs->getUrlRange(1, $pengunjungs->lastPage()) as $page => $url)
                        @if ($page == $pengunjungs->currentPage())
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

                    @if ($pengunjungs->hasMorePages())
                        <a href="{{ $pengunjungs->nextPageUrl() }}"
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
                    Showing {{ $pengunjungs->count() ? 1 : 0 }} to {{ $pengunjungs->count() }} of {{ $pengunjungs->total() }} entries
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