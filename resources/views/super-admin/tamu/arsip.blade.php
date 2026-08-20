@extends('super-admin.layouts.app')

@section('title', 'Arsip Data Tamu - Buku Tamu Digital')

@section('content')
    <div x-data="{
        openPulihkan: false,
        selected: { id: '', nama_lengkap: '' },
        pulihkanUrl: '',

        setPulihkan(tamu) {
            this.selected = tamu;
            this.pulihkanUrl = '{{ url('/super/tamu') }}/' + tamu.id + '/pulihkan';
            this.openPulihkan = true;
        }
    }" class="relative">

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openPulihkan }">

            {{-- Breadcrumb & Title --}}
            <div>
                <div class="text-sm text-gray-500 mb-1">
                    <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('super.tamu.index') }}" class="hover:text-gray-700">Tamu</a>
                    <span class="mx-1">/</span>
                    <span class="text-gray-700 font-medium">Arsip</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Arsip Data Tamu</h1>
            </div>

            {{-- Search & Action Bar --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-5">
                <form action="{{ route('super.tamu.arsip') }}" method="GET" class="flex-1 w-full max-w-lg">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama / Kode Tiket / Sub Bagian / Tujuan..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                        <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <div class="flex gap-3 shrink-0">
                    <a href="{{ route('super.tamu.index') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="arrow-left" class="w-4 h-4 text-gray-600"></i>
                        <span>KEMBALI</span>
                    </a>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">Daftar Arsip Tamu</h3>
                    <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                        Total : {{ $tamus->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5">Nama & Tiket</th>
                                <th class="px-6 py-3.5">Tujuan</th>
                                <th class="px-6 py-3.5">Pegawai</th>
                                <th class="px-6 py-3.5 text-center">Cek</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-center">Aksi</th>
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
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $tamu->nama_lengkap }}
                                        <div class="text-xs font-normal text-gray-400 mt-0.5">{{ $tamu->kode_tiket }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $tamu->pegawai->nama_lengkap ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" class="w-4 h-4 text-[#173860] rounded border-gray-300 focus:ring-[#173860] cursor-not-allowed" disabled
                                            @checked($tamu->status !== 'menunggu')>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 {{ $badge['class'] }} rounded-full text-[11px] font-bold whitespace-nowrap">
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <!-- Tombol Pulihkan -->
                                            <button type="button"
                                                @click="setPulihkan({ id: {{ $tamu->id_tamu }}, nama_lengkap: @js($tamu->nama_lengkap) })"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                <span>Pulihkan</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada data tamu yang diarsipkan.
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

        {{-- INCLUDE MODAL PULIHKAN --}}
        @include('super-admin.tamu.pulihkan')

    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush