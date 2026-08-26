@extends('super-admin.layouts.app')

@section('title', 'Manajemen Data Survei Tamu - Buku Tamu Digital')

@section('content')
    <div x-data="{
        openDetail: false,
        loadingDetail: false,
        detailContent: '',
        
        async loadDetail(id) {
            this.openDetail = true;
            this.loadingDetail = true;
            this.detailContent = '';
            try {
                const res = await fetch(`{{ route('super.survei.index') }}?id_respon=${id}`, {
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
    }" class="relative" :data-modal-open="openDetail">

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openDetail }">

            {{-- Breadcrumb & Title --}}
            <div>
                <div class="text-sm text-gray-500 mb-1">
                    <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-1">/</span>
                    <span class="text-gray-700 font-medium">Survei Tamu</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Data Survei</h1>
            </div>

            {{-- Search Bar --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-5">
                <form action="{{ route('super.survei.index') }}" method="GET" class="flex-1 w-full max-w-lg">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama / Email / Instansi..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none placeholder:text-gray-400">
                        <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table Card (auto-refresh via AJAX) --}}
            <div id="tabel-survei-wrapper">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">Daftar Respon Survei</h3>
                        <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                            Total : {{ $respons->total() ?? 0 }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3.5 text-center w-16">NO</th>
                                    <th class="px-6 py-3.5">NAMA</th>
                                    <th class="px-6 py-3.5 text-center">EMAIL</th>
                                    <th class="px-6 py-3.5 text-center">INSTANSI</th>
                                    <th class="px-6 py-3.5 text-center">POLA JAWABAN</th>
                                    <th class="px-6 py-3.5 text-center w-36">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($respons as $index => $respon)
                                    <tr class="hover:bg-gray-50/50 transition align-top">
                                        <td class="px-6 py-4 text-center font-semibold text-gray-500">
                                            {{ $respons->firstItem() + $index }}
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
                                                    'rata_kiri'   => 'bg-red-100 text-red-700',
                                                    'rata_kanan'  => 'bg-red-100 text-red-700',
                                                    'rata_tengah' => 'bg-orange-100 text-orange-700',
                                                    'menaik'      => 'bg-orange-100 text-orange-700',
                                                    'menurun'     => 'bg-orange-100 text-orange-700',
                                                    'zigzag'      => 'bg-purple-100 text-purple-700',
                                                    'normal'      => 'bg-gray-100 text-gray-600',
                                                ][$respon->pola_survei ?? 'normal'];
                                            @endphp
                                            @if (!empty($respon->is_anomali))
                                                <span class="{{ $warnaPola }} px-3 py-1 rounded-full text-[11px] font-bold whitespace-nowrap">
                                                    {{ $respon->pola_survei_label ?? 'Anomali' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-[11px] font-bold whitespace-nowrap">
                                                    <i data-lucide="check" class="w-3 h-3"></i>
                                                    <span>Valid</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <div class="flex justify-center items-center">
                                                <button type="button"
                                                    @click="loadDetail('{{ $respon->id_respon }}')"
                                                    class="px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                    <span>Detail</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                            Belum ada data respon survei yang tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($respons->hasPages())
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500">
                                Showing {{ $respons->firstItem() }} to {{ $respons->lastItem() }} of {{ $respons->total() }} entries
                            </p>
                            <div class="flex items-center gap-1.5">
                                @if ($respons->onFirstPage())
                                    <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
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

                                @if ($respons->hasMorePages())
                                    <a href="{{ $respons->nextPageUrl() }}"
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
                                Showing {{ $respons->count() ? 1 : 0 }} to {{ $respons->count() }} of {{ $respons->total() }} entries
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- INCLUDES MODAL DETAIL --}}
        @include('super-admin.survei.data.detail')
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush