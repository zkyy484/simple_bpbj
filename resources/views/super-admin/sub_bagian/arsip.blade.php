@extends('super-admin.layouts.app') @section('title', 'Manajemen Sub Bagian') @section('content') <div class="relative">
    <!-- CONTENT MAIN -->
    <div class="space-y-6 transition-all duration-300"> {{-- Header --}} <div>
            <nav class="text-xs text-gray-500 mb-1"> <span>Dashboard</span> <span>/</span> <span>Sub Bagian</span>
                <span>/</span> <span class="font-semibold text-gray-700">Arsip</span>
            </nav>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Arsip Data Sub Bagian</h2>
        </div> {{-- Search & Button --}} <div
            class="bg-white rounded-xl shadow-sm p-4 flex flex-col lg:flex-row justify-between items-center gap-4">
            <form action="{{ route('arsip.sub') }}" method="GET" class="flex-1 w-full max-w-md">
                <div class="flex"> <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Sub Bagian..."
                        class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#173860] outline-none">
                    <button type="submit" class="bg-[#173860] hover:bg-[#102a48] text-white px-4 rounded-r-lg"> <svg
                            class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg> </button>
                </div>
            </form>
            <div class="flex gap-3"> <a href="{{ route('index.sub') }}"
                    class="bg-[#080d1a] hover:bg-[#173860] text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                    Kembali </a> </div>
        </div> {{-- Card Table --}} <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Daftar Sub Bagian</h3> <span
                    class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold"> Total :
                    {{ $subBagians->total() ?? 0 }} </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 text-gray-700 text-sm">
                        <tr>
                            <th class="px-6 py-4 text-center w-20">No</th>
                            <th class="px-6 py-4">Nama Sub Bagian</th>
                            <th class="px-6 py-4 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($subBagians as $index => $subBagian)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center"> {{ $subBagians->firstItem() + $index }} </td>
                                <td class="px-6 py-4 font-semibold"> {{ $subBagian->nama_sub_bagian }} </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('sub.pulihkan') }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Input hidden untuk mengirim ID data yang dipulihkan -->
                                        <input type="hidden" name="id_sub_bagian"
                                            value="{{ $subBagian->id_sub_bagian }}">

                                        <button type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin memulihkan data {{ $subBagian->nama_sub_bagian }}?')"
                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-white text-xs font-semibold transition">
                                            Pulihkan
                                        </button>
                                    </form>
                                </td>

                                </td>
                        </tr> @empty <tr>
                                <td colspan="3">
                                    <div class="py-12 text-center text-gray-500"> Belum ada data Arsip Sub Bagian.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t bg-gray-50"> {{ $subBagians->links() }} </div>
        </div>
    </div>
</div> @endsection
