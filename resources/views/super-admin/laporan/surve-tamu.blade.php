@extends('super-admin.layouts.app')

@section('title', 'Laporan Survei - Buku Tamu Digital')

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

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openDetail || openDelete }">

            {{-- Breadcrumb & Title --}}
            <div>
                <div class="text-sm text-gray-500 mb-1">
                    <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-1">/</span>
                    <span class="text-gray-700 font-medium">Laporan Survei</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan Survei Tamu</h1>
            </div>

            {{-- Filter Bar (Tanggal, Dropdown Normal/Anomali, Arsip, Export PDF & Excel) --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
                <form action="{{ route('laporan.survei.index') }}" method="GET"
                    class="flex flex-col lg:flex-row items-end justify-between gap-4">

                    {{-- Input Filter Tanggal & Dropdown Deteksi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1 w-full">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Mulai Tanggal</label>
                            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-3.5 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sampai Tanggal</label>
                            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-3.5 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status Deteksi</label>
                            <select name="deteksi"
                                class="w-full bg-[#f0f2f5] border-none rounded-lg px-3.5 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none cursor-pointer">
                                <option value="" {{ request('deteksi') == '' ? 'selected' : '' }}>Semua Data</option>
                                <option value="normal" {{ request('deteksi') == 'normal' ? 'selected' : '' }}>Normal
                                </option>
                                <option value="anomali" {{ request('deteksi') == 'anomali' ? 'selected' : '' }}>Anomali
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2 shrink-0 w-full lg:w-auto justify-end flex-wrap">
                        <button type="submit"
                            class="px-5 py-2.5 bg-[#173860] hover:bg-[#12294a] text-white text-xs font-bold rounded-lg transition flex items-center gap-1.5">
                            <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                            <span>Filter</span>
                        </button>

                        @if (request()->hasAny(['tanggal_awal', 'tanggal_akhir', 'deteksi']))
                            <a href="{{ route('laporan.survei.index') }}"
                                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition">
                                Reset
                            </a>
                        @endif

                        {{-- Tombol Halaman Arsip --}}
                        @if (Route::has('laporan.survei.arsip'))
                            <a href="{{ route('laporan.survei.arsip') }}"
                                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-1.5">
                                <i data-lucide="archive" class="w-4 h-4 text-gray-600"></i>
                                <span>Arsip</span>
                            </a>
                        @endif

                        {{-- Cetak PDF --}}
                        @if (Route::has('laporan.survei.export.pdf'))
                            <a href="{{ route('laporan.survei.export.pdf', request()->all()) }}" target="_blank"
                                class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition flex items-center gap-1.5 shadow-sm">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                <span>Unduh PDF</span>
                            </a>
                        @endif

                        {{-- Unduh Excel --}}
                        @if (Route::has('laporan.survei.export'))
                            <a href="{{ route('laporan.survei.export', request()->all()) }}"
                                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition flex items-center gap-1.5 shadow-sm">
                                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                                <span>Unduh Excel</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">
                        Daftar Laporan Respon Survei
                        @if (request('deteksi') == 'anomali')
                            <span class="text-red-600 text-xs font-bold">(Hanya Anomali)</span>
                        @elseif (request('deteksi') == 'normal')
                            <span class="text-emerald-600 text-xs font-bold">(Hanya Valid)</span>
                        @endif
                    </h3>
                    <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                        Total : {{ $respons->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5 text-center w-16">NO</th>
                                <th class="px-6 py-3.5">NAMA</th>
                                <th class="px-6 py-3.5 text-center">EMAIL</th>
                                <th class="px-6 py-3.5 text-center">INSTANSI</th>
                                <th class="px-6 py-3.5 text-center">POLA JAWABAN</th>
                                <th class="px-6 py-3.5 text-center w-48">AKSI</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($respons as $respon)
                                <tr class="hover:bg-gray-50/50 transition align-top">
                                    <td class="px-6 py-4 text-center font-semibold text-gray-500">
                                        {{ $loop->iteration + ($respons->firstItem() - 1) }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $respon->nama_lengkap }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-gray-700">
                                        {{ $respon->email ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-gray-700">
                                        {{ $respon->instansi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $warnaPola = [
                                                'rata_kiri' => 'bg-red-100 text-red-700',
                                                'rata_kanan' => 'bg-red-100 text-red-700',
                                                'rata_tengah' => 'bg-orange-100 text-orange-700',
                                                'menaik' => 'bg-orange-100 text-orange-700',
                                                'menurun' => 'bg-orange-100 text-orange-700',
                                                'zigzag' => 'bg-purple-100 text-purple-700',
                                                'normal' => 'bg-gray-100 text-gray-600',
                                            ][$respon->pola_survei ?? 'normal'];
                                        @endphp
                                        @if (!empty($respon->is_anomali))
                                            <span
                                                class="{{ $warnaPola }} px-3 py-1 rounded-full text-[11px] font-bold whitespace-nowrap">
                                                {{ $respon->pola_survei_label ?? 'Anomali' }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-[11px] font-bold whitespace-nowrap">
                                                <i data-lucide="check" class="w-3 h-3"></i>
                                                <span>Valid</span>
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <!-- Tombol Detail -->
                                            <button type="button" @click="loadDetail('{{ $respon->id_respon }}')"
                                                class="px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                <span>Detail</span>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <button type="button"
                                                @click="
                                                    selectedItem = {
                                                        id: '{{ $respon->id_respon }}',
                                                        nama: '{{ $respon->nama_lengkap }}'
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
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada data survei yang sesuai filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($respons->hasPages())
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $respons->firstItem() }} to {{ $respons->lastItem() }} of {{ $respons->total() }}
                            entries
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if ($respons->onFirstPage())
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $respons->previousPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($respons->getUrlRange(1, $respons->lastPage()) as $page => $url)
                                @if ($page == $respons->currentPage())
                                    <span
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#173860] text-white text-xs font-semibold">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-xs font-semibold">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($respons->hasMorePages())
                                <a href="{{ $respons->nextPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            @else
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $respons->count() ? 1 : 0 }} to {{ $respons->count() }} of {{ $respons->total() }}
                            entries
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Partial Modals --}}
        @include('super-admin.survei.data.delete')
        @include('super-admin.survei.data.detail')
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
@endpush