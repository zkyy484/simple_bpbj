@extends('super-admin.layouts.app')

@section('title', 'Manajemen Akun - Buku Tamu Digital')

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

            <!-- Breadcrumb -->
            <div class="text-sm text-gray-500">
                <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700 font-medium">Manajemen Akun</span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900">Manajemen Akun</h1>

            <!-- Search & Action Card -->
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-4">
                <form action="{{ route('index.akun') }}" method="GET" class="flex-1 w-full max-w-md">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari akun berdasarkan nama atau email..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-gray-400">
                        <button type="submit"
                            class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <div class="flex items-center gap-3 w-full lg:w-auto justify-end">
                    <!-- Tombol Halaman Arsip dengan Icon -->
                    <a href="{{ route('akun.arsip') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="archive" class="w-4 h-4 text-gray-600"></i>
                        <span>ARSIP</span>
                    </a>

                    <!-- Tombol Tambah Akun -->
                    <button type="button" @click="openCreate = true"
                        class="px-5 py-2.5 bg-[#173860] hover:bg-[#12294a] text-white text-xs font-bold tracking-wide rounded-lg transition flex items-center gap-2 whitespace-nowrap shadow-sm">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>TAMBAH AKUN</span>
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">Daftar Akun</h3>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                        Total : {{ $accounts->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5 text-center w-16">No</th>
                                <th class="px-6 py-3.5">Nama</th>
                                <th class="px-6 py-3.5">Email</th>
                                <th class="px-6 py-3.5 text-center">Role</th>
                                <th class="px-6 py-3.5 text-center w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($accounts as $index => $account)
                                <tr class="hover:bg-gray-50/50 transition align-top">
                                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                                        {{ $accounts->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $account->nama_lengkap }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $account->email }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-3 py-1 bg-[#173860] text-white rounded-full text-[11px] font-bold whitespace-nowrap">
                                            {{ strtoupper($account->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <!-- Tombol Edit -->
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
                                                class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 rounded-lg text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                                                <span>Edit</span>
                                            </button>

                                            <!-- Tombol Arsip/Hapus (Soft Delete) -->
                                            <button type="button"
                                                @click="openDelete = true; selectedUser = { id: '{{ $account->id_user }}', nama: @js($account->nama_lengkap) }"
                                                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 rounded-lg text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada data akun yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($accounts->hasPages())
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $accounts->firstItem() }} to {{ $accounts->lastItem() }} of
                            {{ $accounts->total() }} entries
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if ($accounts->onFirstPage())
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $accounts->previousPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($accounts->getUrlRange(1, $accounts->lastPage()) as $page => $url)
                                @if ($page == $accounts->currentPage())
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

                            @if ($accounts->hasMorePages())
                                <a href="{{ $accounts->nextPageUrl() }}"
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
                            Showing {{ $accounts->count() ? 1 : 0 }} to {{ $accounts->count() }} of
                            {{ $accounts->total() }} entries
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- INCLUDES MODAL --}}
        @include('super-admin.akun.create')
        @include('super-admin.akun.edit')
        @include('super-admin.akun.delete')
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
    });
</script>
@endpush