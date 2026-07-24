@extends('super-admin.layouts.app')

@section('title', 'Arsip Data Tamu')

@section('content')

<div class="p-6">
    <!-- Breadcrumb & Title -->
    <div class="text-gray-500 text-sm mb-1">Dashboard / Akun</div>
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Arsip Data Tamu</h2>

    <!-- Toolbar -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex-1 max-w-lg">
            <form action="#" method="GET">
                <div class="flex border border-gray-300 rounded-xl overflow-hidden bg-white shadow-sm">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari Data Tamu..."
                        class="flex-1 px-5 py-3 outline-none text-sm focus:ring-1 focus:ring-[#173860]"
                    >
                    <button type="submit" 
                            class="bg-[#173860] hover:bg-[#102a48] text-white px-6 flex items-center justify-center transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <a href="{{ route('super-admin.tamu.index') }}" 
           class="px-6 py-3 bg-[#173860] hover:bg-[#102a48] text-white font-semibold rounded-xl transition">
            Kembali
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">NAMA</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">TUJUAN</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">PEGAWAI</th>
                    <th class="w-12 px-6 py-4"></th> <!-- Checkbox -->
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">STATUS</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase w-44">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-5">I Wayan Sudarsana</td>
                    <td class="px-6 py-5 text-gray-600">Koordinasi</td>
                    <td class="px-6 py-5 text-gray-600">Pegawai 1</td>
                    <td class="px-6 py-5 text-center">
                        <input type="checkbox" class="w-5 h-5 accent-[#173860] cursor-pointer">
                    </td>
                    <td class="px-6 py-5 text-center">
                        <span class="inline-block px-5 py-1.5 text-xs font-bold rounded-full text-white bg-gray-400">Menunggu</span>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <button onclick="showPulihkanModal()" 
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium transition mx-auto">
                            <i class="fa fa-undo"></i>
                            Pulihkan
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-5">I Wayan Sudarsana</td>
                    <td class="px-6 py-5 text-gray-600">Koordinasi</td>
                    <td class="px-6 py-5 text-gray-600">Pegawai 1</td>
                    <td class="px-6 py-5 text-center">
                        <input type="checkbox" class="w-5 h-5 accent-[#173860] cursor-pointer">
                    </td>
                    <td class="px-6 py-5 text-center">
                        <span class="inline-block px-5 py-1.5 text-xs font-bold rounded-full text-white bg-red-600">Eskalasi</span>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <button onclick="showPulihkanModal()" 
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium transition mx-auto">
                            <i class="fa fa-undo"></i>
                            Pulihkan
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-5">I Wayan Sudarsana</td>
                    <td class="px-6 py-5 text-gray-600">Koordinasi</td>
                    <td class="px-6 py-5 text-gray-600">Pegawai 1</td>
                    <td class="px-6 py-5 text-center">
                        <input type="checkbox" class="w-5 h-5 accent-[#173860] cursor-pointer">
                    </td>
                    <td class="px-6 py-5 text-center">
                        <span class="inline-block px-5 py-1.5 text-xs font-bold rounded-full text-white bg-blue-600">Diproses</span>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <button onclick="showPulihkanModal()" 
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium transition mx-auto">
                            <i class="fa fa-undo"></i>
                            Pulihkan
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-5">I Wayan Sudarsana</td>
                    <td class="px-6 py-5 text-gray-600">Koordinasi</td>
                    <td class="px-6 py-5 text-gray-600">Pegawai 1</td>
                    <td class="px-6 py-5 text-center">
                        <input type="checkbox" class="w-5 h-5 accent-[#173860] cursor-pointer">
                    </td>
                    <td class="px-6 py-5 text-center">
                        <span class="inline-block px-5 py-1.5 text-xs font-bold rounded-full text-white bg-green-600">Selesai</span>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <button onclick="showPulihkanModal()" 
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium transition mx-auto">
                            <i class="fa fa-undo"></i>
                            Pulihkan
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="flex items-center justify-between px-6 py-5 bg-gray-50 border-t">
            <div class="text-sm text-gray-600">
                Showing <strong>1</strong> to <strong>4</strong> of <strong>24</strong> entries
            </div>
            <div class="flex items-center gap-1">
                <button class="px-3 py-1 border rounded hover:bg-gray-100">&lt;</button>
                <button class="px-4 py-1 bg-[#173860] text-white rounded">1</button>
                <button class="px-4 py-1 border rounded hover:bg-gray-100">2</button>
                <button class="px-4 py-1 border rounded hover:bg-gray-100">3</button>
                <button class="px-3 py-1 border rounded hover:bg-gray-100">&gt;</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pulihkan -->
@include('super-admin.tamu.pulihkan')

@endsection

@push('scripts')
<script>
    function showPulihkanModal() {
        const modal = document.getElementById('pulihkanModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePulihkanModal() {
        const modal = document.getElementById('pulihkanModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmPulihkan() {
        alert('Data berhasil dipulihkan!');
        closePulihkanModal();
    }

    // Close modal jika klik di luar
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('pulihkanModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) closePulihkanModal();
            });
        }
    });
</script>
@endpush