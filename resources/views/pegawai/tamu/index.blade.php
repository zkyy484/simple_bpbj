@extends('pegawai.layouts.app')

@section('title', 'Manajemen Tamu - Buku Tamu Digital')

@section('content')
    <div x-data="{
        openTindakLanjut: false,
        openDetail: false,
        openKonfirmasiTerima: false,
        selected: {
            id: '',
            kode_tiket: '',
            nama_lengkap: '',
            email: '',
            no_telp: '',
            sub_bagian: '',
            tujuan: '',
            permasalahan: '',
            solusi: '',
            status_tindak_lanjut: '',
            pegawai_penanggung_jawab: ''
        },
        updateUrl: '',
        emailUrl: '',
    
        setTerima(tamu) {
            this.selected = tamu;
            this.openKonfirmasiTerima = true;
        },
    
        setTindakLanjut(tamu) {
            this.selected = tamu;
            this.updateUrl = '{{ url('/pegawai/tamu') }}/' + tamu.id + '/tindak-lanjut';
            this.emailUrl = '{{ url('/pegawai/tamu') }}/' + tamu.id + '/kirim-email';
            this.openTindakLanjut = true;
        },
    
        setDetail(tamu) {
            this.selected = tamu;
            this.openDetail = true;
        }
    }" class="relative">

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{
                'blur-sm pointer-events-none select-none scale-[0.99]': openTindakLanjut || openDetail ||
                    openKonfirmasiTerima
            }">

            {{-- Breadcrumb & Title --}}
            <div>
                <div class="text-sm text-gray-500 mb-1">
                    <a href="{{ route('pegawai.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-1">/</span>
                    <span class="text-gray-700 font-medium">Tamu</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Tamu</h1>
            </div>

            {{-- Search Bar --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-5">
                <form action="{{ route('pegawai.tamu.index') }}" method="GET" class="flex-1 w-full max-w-lg">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Tamu..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                        <button type="submit"
                            class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">Daftar Tamu</h3>
                    <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                        Total : {{ $tamus->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5">KODE TIKET</th>
                                <th class="px-6 py-3.5">NAMA</th>
                                <th class="px-6 py-3.5">TUJUAN</th>
                                <th class="px-6 py-3.5">PEGAWAI</th>
                                <th class="px-6 py-3.5 text-center">STATUS</th>
                                <th class="px-6 py-3.5 text-center w-40">AKSI</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($tamus as $tamu)
                                @php
                                    $sudahDitindaklanjuti = !is_null($tamu->id_user);

                                    $statusColor = match ($tamu->status_tindak_lanjut) {
                                        'selesai' => 'bg-emerald-100 text-emerald-800',
                                        'eskalasi' => 'bg-amber-100 text-amber-800',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $statusLabel = match ($tamu->status_tindak_lanjut) {
                                        'selesai' => 'Selesai',
                                        'eskalasi' => 'Eskalasi',
                                        default => 'Belum Eskalasi',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition align-top">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $tamu->kode_tiket }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $tamu->nama_lengkap }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $tamu->pegawai->nama_lengkap ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-block px-3 py-1 text-[11px] font-bold rounded-full whitespace-nowrap {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @php
                                            $isPenanggungJawab = $tamu->id_user == auth()->user()->id_user;
                                            $sudahDiambil = !is_null($tamu->id_user);
                                        @endphp

                                        <div class="flex justify-center items-center gap-2">
                                            @if (!$sudahDiambil)
                                                {{-- Tombol Terima Tamu (Memicu Modal Konfirmasi) --}}
                                                <button type="button"
                                                    @click="setTerima({
                id: {{ $tamu->id_tamu }},
                kode_tiket: @js($tamu->kode_tiket),
                nama_lengkap: @js($tamu->nama_lengkap)
            })"
                                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                                                    <span>Terima Tamu</span>
                                                </button>
                                            @elseif ($isPenanggungJawab)
                                                {{-- Tombol Tindak Lanjut (Muncul jika pegawai yang login adalah penanggung jawabnya) --}}
                                                <button type="button"
                                                    @click="setTindakLanjut({
                id: {{ $tamu->id_tamu }},
                kode_tiket: @js($tamu->kode_tiket),
                nama_lengkap: @js($tamu->nama_lengkap),
                email: @js($tamu->email ?? '-'),
                no_telp: @js($tamu->nomor_telepon ?? '-'),
                sub_bagian: @js($tamu->subBagian->nama_sub_bagian ?? '-'),
                tujuan: @js($tamu->tujuan->nama_tujuan ?? '-'),
                permasalahan: @js($tamu->permasalahan ?? '-'),
                solusi: @js($tamu->solusi ?? ''),
                status_tindak_lanjut: @js($tamu->status_tindak_lanjut ?? 'belum_eskalasi'),
                pegawai_penanggung_jawab: @js($tamu->pegawai->nama_lengkap ?? (auth()->user()->nama_lengkap ?? '-'))
            })"
                                                    class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                                    <span>Tindak Lanjuti</span>
                                                </button>
                                            @else
                                                {{-- Tombol Detail (Muncul jika sudah diambil pegawai lain) --}}
                                                <button type="button"
                                                    @click="setDetail({
                id: {{ $tamu->id_tamu }},
                kode_tiket: @js($tamu->kode_tiket),
                nama_lengkap: @js($tamu->nama_lengkap),
                email: @js($tamu->email ?? '-'),
                no_telp: @js($tamu->nomor_telepon ?? '-'),
                sub_bagian: @js($tamu->subBagian->nama_sub_bagian ?? '-'),
                tujuan: @js($tamu->tujuan->nama_tujuan ?? '-'),
                permasalahan: @js($tamu->permasalahan ?? '-'),
                solusi: @js($tamu->solusi ?? '-'),
                status_tindak_lanjut: @js($statusLabel),
                pegawai_penanggung_jawab: @js($tamu->pegawai->nama_lengkap ?? '-')
            })"
                                                    class="px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                    <span>Detail</span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada data tamu yang mengisi Buku Tamu Digital.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($tamus->hasPages())
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $tamus->firstItem() }} to {{ $tamus->lastItem() }} of {{ $tamus->total() }}
                            entries
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if ($tamus->onFirstPage())
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
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

                            @if ($tamus->hasMorePages())
                                <a href="{{ $tamus->nextPageUrl() }}"
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
                            Showing {{ $tamus->count() ? 1 : 0 }} to {{ $tamus->count() }} of {{ $tamus->total() }}
                            entries
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Partial Modals --}}
        @include('pegawai.tamu.konfirmasi_terima')
        @include('pegawai.tamu.tindak_lanjut')
        @include('pegawai.tamu.detail')

    </div>

    {{-- SweetAlert Notification --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#173860',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-5 py-2.5 font-semibold'
                    }
                });
            });
        </script>
    @endif
@endsection

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
@endpush
