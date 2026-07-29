@extends('pegawai.layouts.app')

@section('title', 'Manajemen Tamu')

@section('content')
    <div x-data="{
        openTindakLanjut: false,
        openDetail: false,
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
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openTindakLanjut || openDetail }">

            {{-- Header --}}
            <div>
                <nav class="text-sm text-gray-500 mb-1 flex items-center gap-1.5">
                    <span>Dashboard</span>
                    <span>/</span>
                    <span class="font-bold text-gray-800">Tamu</span>
                </nav>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Manajemen Tamu</h2>
            </div>

            {{-- Search --}}
            <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-col lg:flex-row justify-between items-center gap-4">
                <form action="{{ route('pegawai.tamu.index') }}" method="GET" class="flex-1 w-full max-w-lg">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Tamu..."
                            class="flex-1 border border-gray-200 bg-gray-50 rounded-l-full pl-5 pr-4 py-3 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                        <button type="submit"
                            class="bg-[#0d1b2a] hover:bg-black text-white px-5 rounded-r-full transition flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Card Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Tamu</h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $tamus->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-[#f4efe6] text-gray-700 text-xs uppercase tracking-wide font-bold">
                            <tr>
                                <th class="px-6 py-4 text-left">Kode Tiket</th>
                                <th class="px-6 py-4 text-left">Nama</th>
                                <th class="px-6 py-4 text-left">Tujuan</th>
                                <th class="px-6 py-4 text-left">Pegawai</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-center w-40">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50 bg-white">
                            @forelse ($tamus as $tamu)
                                @php
                                    $sudahDitindaklanjuti = !is_null($tamu->id_user);

                                    $statusColor = match ($tamu->status_tindak_lanjut) {
                                        'selesai' => 'bg-emerald-100 text-emerald-700',
                                        'eskalasi' => 'bg-amber-100 text-amber-700',
                                        default => 'bg-gray-100 text-gray-500',
                                    };
                                    $statusLabel = match ($tamu->status_tindak_lanjut) {
                                        'selesai' => 'Selesai',
                                        'eskalasi' => 'Eskalasi',
                                        default => 'Belum Eskalasi',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $tamu->kode_tiket }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $tamu->nama_lengkap }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $tamu->pegawai->nama_lengkap ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span
                                            class="inline-block px-3 py-1.5 text-xs font-bold rounded-full {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $isPenanggungJawab = $tamu->id_user == auth()->user()->id_user;
                                        @endphp

                                        @if (is_null($tamu->id_user) || $isPenanggungJawab)
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
                                                    pegawai_penanggung_jawab: @js($tamu->pegawai->nama_lengkap ?? auth()->user()->nama_lengkap ?? '-')
                                                })"
                                                class="px-4 py-1.5 text-xs font-bold rounded-full w-32 shadow-sm transition bg-amber-400 hover:bg-amber-500 text-gray-900">
                                                Tindak Lanjuti
                                            </button>
                                        @else
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
                                                class="px-4 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 rounded-full text-gray-800 text-xs font-bold transition shadow-sm w-32">
                                                Detail
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-12 text-center text-gray-500 text-sm">
                                        Belum ada data tamu yang mengisi Buku Tamu Digital.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-6 py-4 border-t border-gray-100 bg-white flex items-center justify-between flex-wrap gap-3
                    [&_nav]:!flex [&_nav]:!items-center [&_nav]:!gap-2
                    [&_a]:!w-9 [&_a]:!h-9 [&_a]:!flex [&_a]:!items-center [&_a]:!justify-center [&_a]:!rounded-lg [&_a]:!bg-white [&_a]:!text-gray-700 [&_a]:!border [&_a]:!border-gray-300 hover:[&_a]:!bg-gray-100
                    [&_span[aria-current='page']>span]:!w-9 [&_span[aria-current='page']>span]:!h-9 [&_span[aria-current='page']>span]:!flex [&_span[aria-current='page']>span]:!items-center [&_span[aria-current='page']>span]:!justify-center [&_span[aria-current='page']>span]:!rounded-lg [&_span[aria-current='page']>span]:!bg-gray-900 [&_span[aria-current='page']>span]:!text-white [&_span[aria-current='page']>span]:!font-bold
                    [&_span[aria-disabled='true']>span]:!w-9 [&_span[aria-disabled='true']>span]:!h-9 [&_span[aria-disabled='true']>span]:!flex [&_span[aria-disabled='true']>span]:!items-center [&_span[aria-disabled='true']>span]:!justify-center [&_span[aria-disabled='true']>span]:!rounded-lg [&_span[aria-disabled='true']>span]:!bg-white [&_span[aria-disabled='true']>span]:!text-gray-300 [&_span[aria-disabled='true']>span]:!border [&_span[aria-disabled='true']>span]:!border-gray-200">
                    <p class="text-sm text-gray-500">
                        Showing <span class="font-semibold text-gray-700">{{ $tamus->firstItem() ?? 0 }}</span>
                        to <span class="font-semibold text-gray-700">{{ $tamus->lastItem() ?? 0 }}</span>
                        of <span class="font-semibold text-gray-700">{{ $tamus->total() ?? 0 }}</span> entries
                    </p>
                    {{ $tamus->links() }}
                </div>
            </div>
        </div>

        {{-- Partial Modals --}}
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
                    customClass: { popup: 'rounded-2xl' }
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