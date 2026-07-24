@extends('super-admin.layouts.app')

@section('title', 'Manajemen Tamu')

@section('content')

<div class="p-6">
    <!-- Breadcrumb & Title -->
    <div class="text-gray-500 text-sm mb-1">Dashboard / Tamu</div>
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Tamu</h2>

    <!-- Toolbar -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex-1 max-w-lg">
            <form action="{{ route('super-admin.tamu.index') }}" method="GET">
                <div class="flex border border-gray-300 rounded-xl overflow-hidden bg-white shadow-sm">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari Sub Bagian..."
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

        <button type="button" 
                class="flex items-center gap-2 bg-white border border-gray-300 hover:border-gray-400 px-6 py-3 rounded-xl font-semibold text-gray-700 transition">
            <i class="fa fa-box-archive"></i> 
            Arsip
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-[#f4ede0]">
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Tujuan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Pegawai</th>
                    <th class="w-20 px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Cek</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase w-44">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <!-- Dummy Data -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-5 font-semibold">Ahmad Fauzi</td>
                    <td class="px-6 py-5 text-gray-600">Rapat dengan Kepala Dinas</td>
                    <td class="px-6 py-5 text-gray-600">Budi Santoso</td>
                    <td class="px-6 py-5 text-center">
                        <input type="checkbox" class="w-5 h-5 accent-[#0f2a52]" disabled checked>
                    </td>
                    <td class="px-6 py-5">
                        <span class="inline-block px-5 py-1.5 text-xs font-bold rounded-full text-white bg-green-600">Selesai</span>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex justify-center gap-3">
                            <button onclick="showDetailModal()" 
                                    class="px-5 py-2 text-sm font-semibold border-2 border-[#0f2a52] text-[#0f2a52] rounded-full hover:bg-gray-50 transition">
                                Detail
                            </button>
                            <button onclick="showHapusModal()" 
                                    class="px-5 py-2 text-sm font-semibold bg-red-600 text-white rounded-full hover:bg-red-700 transition">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex items-center justify-between px-6 py-5 bg-gray-50 border-t">
            <div class="text-sm text-gray-600">
                Showing <strong>1</strong> to <strong>4</strong> of <strong>4</strong> entries
            </div>
            <div class="flex gap-2">
                <span class="px-4 py-2 bg-[#173860] text-white rounded-lg text-sm font-medium">1</span>
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('super-admin.tamu.show')
@include('super-admin.tamu.delete')

@endsection

@push('scripts')
<script>
    // ===================== Modal Detail =====================
    function showDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // ===================== Modal Hapus =====================
    function showHapusModal() {
        const modal = document.getElementById('hapusModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeHapusModal() {
        const modal = document.getElementById('hapusModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmHapus() {
        alert('Data tamu berhasil dihapus!'); // Ganti dengan logic delete nanti
        closeHapusModal();
    }

    // Close modal jika klik di luar
    document.addEventListener('DOMContentLoaded', function() {
        const detailModal = document.getElementById('detailModal');
        const hapusModal = document.getElementById('hapusModal');

        if (detailModal) {
            detailModal.addEventListener('click', function(e) {
                if (e.target === this) closeDetailModal();
            });
        }

        if (hapusModal) {
            hapusModal.addEventListener('click', function(e) {
                if (e.target === this) closeHapusModal();
            });
        }
    });
</script>
@endpush