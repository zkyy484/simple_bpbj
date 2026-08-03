@extends('super-admin.layouts.app')

@section('title', 'Manajemen Pertanyaan Survei')

@section('content')
    <div x-data="{
        openCreate: Boolean({{ $errors->any() && old('form_type') == 'create' ? 1 : 0 }}),
        openEdit: Boolean({{ $errors->any() && old('form_type') == 'edit' ? 1 : 0 }}),
        openDelete: false,
        selectedItem: {{ $errors->any() && old('form_type') == 'edit'
            ? Js::from([
                'id' => old('id_pertanyaan'),
                'pertanyaan' => old('pertanyaan'),
                'tipe_pertanyaan' => old('tipe_pertanyaan'),
                'urutan' => old('urutan'),
                'opsi' => old('opsi') ?? [],
            ])
            : '{}' }}
    }" class="relative">

        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openCreate || openEdit || openDelete }">

            <div>
                <nav class="text-xs text-gray-500 mb-1">
                    <span>Dashboard</span> <span>/</span> <span class="font-semibold text-gray-700">Pertanyaan</span>
                </nav>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Pertanyaan Survei</h2>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4 flex gap-3 justify-end">
                <a href="{{ route('pertanyaan.arsip') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    <span>Arsip</span>
                </a>

                <button type="button" @click="openCreate = true"
                    class="bg-[#080d1a] hover:bg-[#173860] text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                    + Tambah Pertanyaan
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Pertanyaan</h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $pertanyaans->total() ?? 0 }}
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
                            <tr>
                                <th class="px-6 py-4 text-center w-16">Urutan</th>
                                <th class="px-6 py-4 text-left">Pertanyaan</th>
                                <th class="px-6 py-4 text-center">Tipe</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($pertanyaans as $p)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-center text-sm">{{ $p->urutan }}</td>
                                    <td class="px-6 py-4 text-left text-sm font-semibold text-gray-900">{{ $p->pertanyaan }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 text-xs font-bold text-white rounded-full bg-[#173860]">
                                            {{ strtoupper($p->tipe_pertanyaan) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">{{ $p->status }}</td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex justify-center gap-2">
                                            <button type="button"
                                                @click="
        selectedItem = {
            id: '{{ $p->id_pertanyaan }}',
            pertanyaan: {{ json_encode($p->pertanyaan) }},
            tipe_pertanyaan: '{{ $p->tipe_pertanyaan }}',
            urutan: {{ $p->urutan ?? 0 }},
            opsi: {{ json_encode($p->opsi) }}
        };
        openEdit = true;
    "
                                                class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 rounded-lg text-white text-xs font-semibold">
                                                Edit
                                            </button>
                                            <button type="button"
                                                @click="
        selectedItem = {
            id: '{{ $p->id_pertanyaan }}',
            pertanyaan: {{ json_encode($p->pertanyaan) }}
        };
        openDelete = true;
    "
                                                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 rounded-lg text-white text-xs font-semibold">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-gray-500 text-sm">Belum ada
                                        pertanyaan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t">{{ $pertanyaans->links() }}</div>
            </div>
        </div>

        @include('super-admin.survei.create')
        @include('super-admin.survei.edit')
        @include('super-admin.survei.delete')
    </div>
@endsection
