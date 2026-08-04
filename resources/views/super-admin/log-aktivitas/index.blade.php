@extends('super-admin.layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
    @php
        $roleBadge = fn (?string $role) => match ($role) {
            'super_admin' => 'bg-[#173860] text-white',
            'admin' => 'bg-emerald-600 text-white',
            'pegawai' => 'bg-amber-500 text-white',
            default => 'bg-gray-400 text-white',
        };
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <nav class="text-xs text-gray-500 mb-1">
                <span>Dashboard</span> <span>/</span> <span class="font-semibold text-gray-700">Log Aktivitas</span>
            </nav>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Log Aktivitas</h2>
            <p class="text-sm text-gray-500 mt-1">Riwayat aktivitas seluruh pengguna (Super Admin, Admin, dan Pegawai) di dalam sistem.</p>
        </div>

        {{-- Ringkasan Aktivitas Hari Ini --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Super Admin</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $ringkasanHariIni['super_admin'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">aktivitas hari ini</p>
                </div>
                <span class="w-11 h-11 rounded-full bg-[#173860]/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#173860]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Admin</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $ringkasanHariIni['admin'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">aktivitas hari ini</p>
                </div>
                <span class="w-11 h-11 rounded-full bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pegawai</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $ringkasanHariIni['pegawai'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">aktivitas hari ini</p>
                </div>
                <span class="w-11 h-11 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <form action="{{ route('log-aktivitas.index') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-5 gap-3">
                <div class="lg:col-span-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, aktivitas, atau deskripsi..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                </div>

                <div>
                    <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                        <option value="">Semua Role</option>
                        <option value="super_admin" @selected($role === 'super_admin')>Super Admin</option>
                        <option value="admin" @selected($role === 'admin')>Admin</option>
                        <option value="pegawai" @selected($role === 'pegawai')>Pegawai</option>
                    </select>
                </div>

                <div>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                </div>

                <div class="flex gap-2">
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                    <button type="submit"
                        class="shrink-0 bg-[#173860] hover:bg-[#102a48] text-white px-4 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>

            @if ($search || $role || $tanggalMulai || $tanggalSelesai)
                <div class="mt-3">
                    <a href="{{ route('log-aktivitas.index') }}" class="text-xs text-gray-500 hover:text-gray-800 underline">
                        Reset filter
                    </a>
                </div>
            @endif
        </div>

        {{-- Tabel Log --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Riwayat Aktivitas</h3>
                <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                    Total : {{ $logs->total() ?? 0 }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-center w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left w-40">Tanggal</th>
                            <th scope="col" class="px-6 py-4 text-left w-24">Jam</th>
                            <th scope="col" class="px-6 py-4 text-center">Role</th>
                            <th scope="col" class="px-6 py-4 text-left">Nama Pengguna</th>
                            <th scope="col" class="px-6 py-4 text-left">Aktivitas</th>
                            <th scope="col" class="px-6 py-4 text-left">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($logs as $index => $log)
                            <tr class="hover:bg-gray-50 transition-colors align-top">
                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                    {{ $logs->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-left text-sm text-gray-700 whitespace-nowrap">
                                    {{ $log->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-left text-sm text-gray-700 whitespace-nowrap">
                                    {{ $log->created_at->format('H:i') }} WITA
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $roleBadge($log->role) }}">
                                        {{ \App\Models\ActivityLog::roleLabel($log->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-left text-sm font-semibold text-gray-900 whitespace-nowrap">
                                    {{ $log->nama_user }}
                                </td>
                                <td class="px-6 py-4 text-left text-sm text-gray-800 whitespace-nowrap">
                                    {{ $log->aktivitas }}
                                </td>
                                <td class="px-6 py-4 text-left text-sm text-gray-600 max-w-md">
                                    {{ $log->deskripsi ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-8 py-12 text-center text-gray-500 text-sm">
                                    Belum ada aktivitas yang tercatat.
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
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection