@extends('super-admin.layouts.app')

@section('title', 'Manajemen Data Survei Tamu')

@section('content')
    <div x-data="{
        openCreate: Boolean({{ $errors->any() && old('form_type') == 'create' ? 1 : 0 }}),
        openEdit: Boolean({{ $errors->any() && old('form_type') == 'edit' ? 1 : 0 }}),
        openDelete: false,
        openDetail: false,
        loadingDetail: false,
        detailContent: '',
        selectedItem: {{ $errors->any() && old('form_type') == 'edit'
            ? Js::from([
                'id' => old('id_pertanyaan'),
                'pertanyaan' => old('pertanyaan'),
                'tipe_pertanyaan' => old('tipe_pertanyaan'),
                'urutan' => old('urutan'),
                'opsi' => old('opsi') ?? [],
            ])
            : '{}' }},
    
        async loadDetail(id) {
            this.openDetail = true;
            this.loadingDetail = true;
            this.detailContent = '';
            try {
                const res = await fetch(`{{ route('survei.index') }}?id_respon=${id}`, {
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
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openCreate || openDetail || openDelete }">

            <div>
                <nav class="text-xs text-gray-500 mb-1">
                    <span>Dashboard</span> <span>/</span> <span class="font-semibold text-gray-700">Survei Tamu</span>
                </nav>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Data Survei</h2>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col lg:flex-row justify-between items-center gap-4">
                <div class="flex-1"></div>

                <div class="flex items-center gap-3 whitespace-nowrap">
                    @if (request('anomali'))
                        <a href="{{ route('survei.index', request()->except(['anomali', 'page'])) }}"
                            class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zM11.99 15h.01" />
                            </svg>
                            <span>Anomali Saja</span>
                        </a>
                    @else
                        <a href="{{ route('survei.index', array_merge(request()->except('page'), ['anomali' => 1])) }}"
                            class="bg-gray-100 hover:bg-red-50 text-gray-800 hover:text-red-600 px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zM11.99 15h.01" />
                            </svg>
                            <span>Filter Anomali</span>
                        </a>
                    @endif

                    <a href="{{ route('survei.arsip') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span>Arsip</span>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">
                        Daftar Respon Survei
                        @if ($onlyAnomali ?? false)
                            <span class="text-red-600 text-sm font-semibold">(Anomali)</span>
                        @endif
                    </h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $respons->total() ?? 0 }}
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
                            <tr>
                                <th class="px-6 py-4 text-center w-16">NO</th>
                                <th class="px-6 py-4 text-left">NAMA</th>
                                <th class="px-6 py-4 text-center">EMAIL</th>
                                <th class="px-6 py-4 text-center">INSTANSI</th>
                                <th class="px-6 py-4 text-center w-48">STATUS</th>
                                <th class="px-6 py-4 text-center w-36">POLA JAWABAN</th>
                                <th class="px-6 py-4 text-center w-48">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($respons as $respon)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 text-center">
                                        {{ $loop->iteration + ($respons->firstItem() - 1) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $respon->nama_lengkap }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        {{ $respon->email ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        {{ $respon->instansi ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        @if ($respon->cek == 'approve')
                                            <span
                                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                APPROVE
                                            </span>
                                        @else
                                            <span
                                                class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                MENUNGGU
                                            </span>
                                        @endif

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
                                                'normal'      => 'bg-gray-100 text-gray-400',
                                            ][$respon->pola_survei ?? 'normal'];
                                        @endphp
                                        @if (!empty($respon->is_anomali))
                                            <span class="{{ $warnaPola }} px-3 py-1 rounded-full text-xs font-semibold">
                                                {{ $respon->pola_survei_label ?? 'Anomali' }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Valid
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">

                                        <div class="flex justify-center gap-2">

                                            <button @click="loadDetail('{{ $respon->id_respon }}')"
                                                class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold">
                                                Detail
                                            </button>

                                            <button
                                                @click="
                    selectedItem = {
                        id:'{{ $respon->id_respon }}',
                        nama:'{{ $respon->nama_lengkap }}'
                    };
                    openDelete = true;
                "
                                                class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold">

                                                Hapus

                                            </button>

                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-gray-500">
                                        Belum ada data survei.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t">{{ $respons->links() }}</div>
            </div>
        </div>
        @include('super-admin.survei.data.delete')
        @include('super-admin.survei.data.detail')
    </div>
@endsection