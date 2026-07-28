@extends('admin.layouts.app')

@section('title', 'Manajemen Akun')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <nav class="text-xs text-gray-500 mb-1">
                <span>Dashboard</span> <span>/</span> <span class="font-semibold text-gray-700">Akun</span>
            </nav>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Akun</h2>
        </div>

        {{-- Search --}}
        <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col lg:flex-row justify-between items-center gap-4">
            <form action="{{ route('admin.index.akun') }}" method="GET" class="flex-1 w-full max-w-md">
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
                            <th scope="col" class="px-6 py-4 text-left">No. Telepon</th>
                            <th scope="col" class="px-6 py-4 text-left">Alamat</th>
                            <th scope="col" class="px-6 py-4 text-left">Sub Bagian</th>
                            <th scope="col" class="px-6 py-4 text-center">Role</th>
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
                                <td class="px-6 py-4 text-left text-sm text-gray-600">{{ $account->no_telepon ?? '-' }}</td>
                                <td class="px-6 py-4 text-left text-sm text-gray-600">{{ $account->alamat ?? '-' }}</td>
                                <td class="px-6 py-4 text-left text-sm text-gray-600">{{ $account->subBagian->nama_sub_bagian ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-xs font-bold text-white rounded-full bg-[#173860]">
                                        {{ strtoupper($account->role) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-8 py-12 text-center text-gray-500 text-sm">
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
@endsection