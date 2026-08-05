@extends('super-admin.layouts.app')

@section('title', 'Manajemen Akun')

@section('content')
    <div x-data="{
        openCreate: Boolean({{ $errors->any() && old('form_type') == 'create' ? 1 : 0 }}),
        openEdit: Boolean({{ $errors->any() && old('form_type') == 'edit' ? 1 : 0 }}),
        openDelete: false,
        selectedUser: {{ $errors->any() && old('form_type') == 'edit'
            ? Js::from([
                'id' => old('id_user'),
                'nama_lengkap' => old('nama_lengkap'),
                'nip' => old('nip'),
                'email' => old('email'),
                'no_telepon' => old('no_telepon'),
                'id_sub_bagian' => old('id_sub_bagian'),
                'alamat' => old('alamat'),
                'role' => old('role'),
            ])
            : '{}' }}
    }" class="relative">

        {{-- CONTENT MAIN --}}
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openCreate || openEdit || openDelete }">

            {{-- Header --}}
            <div>
                <nav class="text-xs text-gray-500 mb-1">
                    <span>Dashboard</span> <span>/</span> <span class="font-semibold text-gray-700">Akun</span>
                </nav>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Akun</h2>
            </div>

            {{-- Search & Button --}}
            <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col lg:flex-row justify-between items-center gap-4">
                <form action="{{ route('index.akun') }}" method="GET" class="flex-1 w-full max-w-md">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Akun..."
                            class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                        <button type="submit" class="bg-[#173860] hover:bg-[#102a48] text-white px-4 rounded-r-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
                <div class="flex gap-3">
                    <!-- Tombol Halaman Arsip dengan Icon -->
                    <a href="{{ route('akun.arsip') }}"
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
                        + Tambah Akun
                    </button>
                </div>
            </div>

            {{-- Card Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Akun</h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $accounts->total() ?? 0 }}
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-center w-16">No</th>
                                <th scope="col" class="px-6 py-4 text-left">Nama</th>
                                <th scope="col" class="px-6 py-4 text-left">Email</th>
                                <th scope="col" class="px-6 py-4 text-center">Role</th>
                                <th scope="col" class="px-6 py-4 text-center w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($accounts as $index => $account)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">
                                        {{ $accounts->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 text-left text-sm font-semibold text-gray-900">
                                        {{ $account->nama_lengkap }}</td>
                                    <td class="px-6 py-4 text-left text-sm text-gray-600">{{ $account->email }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 text-xs font-bold text-white rounded-full bg-[#173860]">
                                            {{ strtoupper($account->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <button type="button"
                                                @click="openEdit = true; selectedUser = {
                                                id: '{{ $account->id_user }}',
                                                nama_lengkap: @js($account->nama_lengkap),
                                                nip: @js($account->nip),
                                                email: @js($account->email),
                                                no_telepon: @js($account->no_telepon),
                                                alamat: @js($account->alamat),
                                                id_sub_bagian: '{{ $account->id_sub_bagian }}',
                                                role: '{{ $account->role }}'
                                            }"
                                                class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 rounded-lg text-white text-xs font-semibold transition flex items-center gap-1 shadow-sm">
                                                <!-- Icon Edit -->
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 012.828 0L21.8 4.2a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                                </svg>
                                                <span>Edit</span>
                                            </button>

                                            <!-- Tombol Arsip/Hapus (Soft Delete) -->
                                            <button type="button"
                                                @click="openDelete = true; selectedUser = { id: '{{ $account->id_user }}', nama: @js($account->nama_lengkap) }"
                                                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 rounded-lg text-white text-xs font-semibold transition flex items-center gap-1 shadow-sm">
                                                <!-- Icon Arsip -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-gray-500 text-sm">
                                        Belum ada data akun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div
                    class="px-6 py-4 border-t border-gray-200 bg-white 
                [&_a]:!bg-white [&_a]:!text-black [&_a]:!border [&_a]:!border-gray-400 hover:[&_a]:!bg-gray-100
                [&_span[aria-current='page']>span]:!bg-gray-800 [&_span[aria-current='page']>span]:!text-white [&_span[aria-current='page']>span]:!border [&_span[aria-current='page']>span]:!border-gray-800
                [&_span[aria-disabled='true']>span]:!bg-white [&_span[aria-disabled='true']>span]:!text-gray-400 [&_span[aria-disabled='true']>span]:!border [&_span[aria-disabled='true']>span]:!border-gray-300">
                    {{ $accounts->links() }}
                </div>
            </div>
        </div>

        {{-- INCLUDES MODAL --}}
        @include('super-admin.akun.create')
        @include('super-admin.akun.edit')
        @include('super-admin.akun.delete')
    </div>
@endsection