@extends('super-admin.layouts.app')

@section('title', 'Manajemen Jadwal Dinas - Buku Tamu Digital')

@section('content')
    <div x-data="{
        openCreate: Boolean({{ $errors->any() && old('form_type') == 'create' ? 1 : 0 }}),
        openEdit: Boolean({{ $errors->any() && old('form_type') == 'edit' ? 1 : 0 }}),
        selectedJadwal: {{ $errors->any() && old('form_type') == 'edit'
            ? Js::from([
                'id' => old('id_jadwal_dinas'),
                'nomor_agenda' => old('nomor_agenda'),
                'surat_dari' => old('surat_dari'),
                'nomor_surat' => old('nomor_surat'),
                'perihal' => old('perihal'),
                'tanggal_surat' => old('tanggal_surat'),
                'tanggal_kegiatan' => old('tanggal_kegiatan'),
                'keterangan' => old('keterangan'),
                'pegawai_ids' => old('pegawai_ids', []),
            ])
            : '{}' }}
    }" class="relative" :data-modal-open="openCreate || openEdit">

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openCreate || openEdit }">

            {{-- Breadcrumb & Title --}}
            <div>
                <div class="text-sm text-gray-500 mb-1">
                    <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-1">/</span>
                    <span class="text-gray-700 font-medium">Jadwal Dinas</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Jadwal Dinas</h1>
            </div>

            {{-- Search & Action Bar --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-5">
                <form action="{{ route('super.jadwal_dinas.index') }}" method="GET" class="flex-1 w-full max-w-lg">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari perihal, nomor surat, atau instansi..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none placeholder:text-gray-400">
                        <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <div class="flex gap-3 shrink-0">
                    <button type="button" @click="openCreate = true"
                        class="px-5 py-2.5 bg-[#173860] hover:bg-[#12294a] text-white text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap shadow-sm">
                        <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                        <span>TAMBAH JADWAL</span>
                    </button>
                </div>
            </div>

            {{-- Table Card (auto-refresh via AJAX) --}}
            <div id="tabel-jadwal-wrapper">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">Daftar Penugasan Luar Kantor</h3>
                        <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                            Total : {{ $jadwals->total() ?? 0 }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3.5 text-center w-16">No</th>
                                    <th class="px-6 py-3.5">Agenda & Surat</th>
                                    <th class="px-6 py-3.5">Perihal</th>
                                    <th class="px-6 py-3.5">Tgl Kegiatan</th>
                                    <th class="px-6 py-3.5">Pegawai Bertugas</th>
                                    <th class="px-6 py-3.5 text-center w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($jadwals as $index => $jadwal)
                                    <tr class="hover:bg-gray-50/50 transition align-top">
                                        <td class="px-6 py-4 text-center font-semibold text-gray-900">
                                            {{ $jadwals->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900">{{ $jadwal->nomor_surat }}</div>
                                            <div class="text-xs text-gray-400">Agenda: {{ $jadwal->nomor_agenda ?? '-' }}</div>
                                            <div class="text-xs text-blue-600 font-medium">Dari: {{ $jadwal->surat_dari }}</div>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-800 max-w-xs">
                                            {{ $jadwal->perihal }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md text-xs font-semibold">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal_kegiatan)->translatedFormat('d M Y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($jadwal->pegawais->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($jadwal->pegawais as $pegawai)
                                                        <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 text-gray-700 rounded text-[11px] font-medium">
                                                            {{ $pegawai->nama_lengkap }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-xs text-amber-600 italic">Belum ditentukan</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button type="button"
                                                @click="openEdit = true; selectedJadwal = {
                                                    id: '{{ $jadwal->id_jadwal_dinas }}',
                                                    nomor_agenda: @js($jadwal->nomor_agenda),
                                                    surat_dari: @js($jadwal->surat_dari),
                                                    nomor_surat: @js($jadwal->nomor_surat),
                                                    perihal: @js($jadwal->perihal),
                                                    tanggal_surat: '{{ $jadwal->tanggal_surat }}',
                                                    tanggal_kegiatan: '{{ $jadwal->tanggal_kegiatan }}',
                                                    keterangan: @js($jadwal->keterangan),
                                                    pegawai_ids: @js($jadwal->pegawais->pluck('id_user'))
                                                }"
                                                class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 rounded-lg text-white text-xs font-bold transition inline-flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                                                <span>Edit</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                            Belum ada data jadwal dinas yang tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($jadwals->hasPages())
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500">
                                Showing {{ $jadwals->firstItem() }} to {{ $jadwals->lastItem() }} of {{ $jadwals->total() }} entries
                            </p>
                            <div class="flex items-center gap-1.5">
                                @if ($jadwals->onFirstPage())
                                    <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                    </span>
                                @else
                                    <a href="{{ $jadwals->previousPageUrl() }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                    </a>
                                @endif

                                @foreach ($jadwals->getUrlRange(1, $jadwals->lastPage()) as $page => $url)
                                    @if ($page == $jadwals->currentPage())
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

                                @if ($jadwals->hasMorePages())
                                    <a href="{{ $jadwals->nextPageUrl() }}"
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
                                Showing {{ $jadwals->count() ? 1 : 0 }} to {{ $jadwals->count() }} of {{ $jadwals->total() }} entries
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- INCLUDES MODAL --}}
        @include('super-admin.jadwal-dinas.create')
        @include('super-admin.jadwal-dinas.edit')
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush