@extends('super-admin.layouts.app')

@section('title', 'Log Aktivitas - Buku Tamu Digital')

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

        <!-- Breadcrumb & Title (Jarak Diperdekat) -->
        <div class="space-y-1">
            <div class="text-sm text-gray-500">
                <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700 font-medium">Log Aktivitas</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Log Aktivitas</h1>
        </div>

        {{-- Ringkasan Aktivitas Hari Ini --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Super Admin</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $ringkasanHariIni['super_admin'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">aktivitas hari ini</p>
                </div>
                <span class="w-11 h-11 rounded-full bg-[#173860]/10 flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-5 h-5 text-[#173860]"></i>
                </span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Admin</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $ringkasanHariIni['admin'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">aktivitas hari ini</p>
                </div>
                <span class="w-11 h-11 rounded-full bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5 text-emerald-600"></i>
                </span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pegawai</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $ringkasanHariIni['pegawai'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">aktivitas hari ini</p>
                </div>
                <span class="w-11 h-11 rounded-full bg-amber-100 flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-amber-600"></i>
                </span>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form action="{{ route('super.log-aktivitas.index') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-5 gap-3">
                <div class="lg:col-span-2 relative flex items-center">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, aktivitas, atau deskripsi..."
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-gray-400">
                </div>

                <div>
                    <select name="role" class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Semua Role</option>
                        <option value="super_admin" @selected($role === 'super_admin')>Super Admin</option>
                        <option value="admin" @selected($role === 'admin')>Admin</option>
                        <option value="pegawai" @selected($role === 'pegawai')>Pegawai</option>
                    </select>
                </div>

                <div>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}"
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="flex gap-2">
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}"
                        class="w-full bg-[#f0f2f5] border-none rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                    <button type="submit"
                        class="shrink-0 bg-[#173860] hover:bg-[#12294a] text-white px-4 rounded-lg transition flex items-center justify-center">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            @if ($search || $role || $tanggalMulai || $tanggalSelesai)
                <div class="mt-3">
                    <a href="{{ route('super.log-aktivitas.index') }}" class="text-xs text-gray-500 hover:text-gray-800 underline flex items-center gap-1">
                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Reset filter
                    </a>
                </div>
            @endif
        </div>

        {{-- Tabel Log Card --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900">Riwayat Aktivitas</h3>
                <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold">
                    Total : {{ $logs->total() ?? 0 }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-center w-16">No</th>
                            <th scope="col" class="px-6 py-3.5 w-36">Tanggal</th>
                            <th scope="col" class="px-6 py-3.5 w-28">Jam</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Role</th>
                            <th scope="col" class="px-6 py-3.5">Nama Pengguna</th>
                            <th scope="col" class="px-6 py-3.5">Aktivitas</th>
                            <th scope="col" class="px-6 py-3.5">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($logs as $index => $log)
                            <tr class="hover:bg-gray-50/50 transition align-top">
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">
                                    {{ $logs->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                    {{ $log->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                    {{ $log->created_at->format('H:i') }} WITA
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold {{ $roleBadge($log->role) }}">
                                        {{ strtoupper(\App\Models\ActivityLog::roleLabel($log->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $log->nama_user }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800 whitespace-nowrap">
                                    {{ $log->aktivitas }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 max-w-md">
                                    {{ $log->deskripsi ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    Belum ada aktivitas yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($logs->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} entries
                    </p>
                    <div class="flex items-center gap-1.5">
                        @if ($logs->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </span>
                        @else
                            <a href="{{ $logs->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </a>
                        @endif

                        @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                            @if ($page == $logs->currentPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#173860] text-white text-xs font-semibold">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-xs font-semibold">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($logs->hasMorePages())
                            <a href="{{ $logs->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
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
                        Showing {{ $logs->count() ? 1 : 0 }} to {{ $logs->count() }} of {{ $logs->total() }} entries
                    </p>
                </div>
            @endif
        </div>
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