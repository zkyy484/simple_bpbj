@extends('super-admin.layouts.app')

@section('title', 'Dashboard - Buku Tamu Digital')

@section('content')
<div class="p-6 md:p-8">

        <!-- Breadcrumb & Judul -->
        <div class="mb-8">
            <nav class="text-sm mb-2" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center text-gray-500">
                        <a href="#" class="hover:text-denpasar-blue">Dashboard</a>
                        <span class="mx-2 text-gray-400">/</span>
                    </li>
                    <li class="flex items-center text-gray-700 font-medium">
                        <span aria-current="page">Akun</span>
                    </li>
                </ol>
            </nav>
            <h1 class="text-4xl font-bold text-gray-900">Manajemen Akun</h1>
        </div>

        <!-- Box Putih Container Utama Tabel -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <!-- Baris Pencarian dan Tombol Aksi -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <!-- Kolom Pencarian -->
                <div class="relative flex-grow max-w-xl">
                    <input type="text" id="searchInput" placeholder="Cari Akun..."
                        class="w-full pl-4 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-400 outline-none transition">
                    <button id="searchButton" class="absolute right-1 top-1/2 -translate-y-1/2 bg-blue-600 p-2 rounded-lg text-white hover:bg-blue-700 transition">
                        <!-- Ikon Kaca Pembesar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Kolom Tombol -->
                <div class="flex items-center gap-3">
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition">
                        <!-- Ikon Arsip/Laci -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        Arsip
                    </button>
                    <button class="flex items-center gap-2 px-5 py-2.5 bg-black hover:bg-gray-800 text-white font-medium rounded-lg transition">
                        <!-- Ikon Tambah (+) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Akun
                    </button>
                </div>
            </div>

            <!-- Wrapper Tabel untuk Responsivitas -->
            <div class="overflow-x-auto border border-gray-200 rounded-xl mb-4">
                <table class="w-full text-left border-collapse" id="accountTable">
                    <!-- Header Tabel -->
                    <thead class="bg-table-head-bg">
                        <tr>
                            <th class="px-6 py-4 font-bold text-xs uppercase text-gray-600 tracking-wider">NAMA</th>
                            <th class="px-6 py-4 font-bold text-xs uppercase text-gray-600 tracking-wider">EMAIL</th>
                            <th class="px-6 py-4 font-bold text-xs uppercase text-gray-600 tracking-wider">ROLE</th>
                            <th class="px-6 py-4 font-bold text-xs uppercase text-gray-600 tracking-wider text-center">AKSI</th>
                        </tr>
                    </thead>
                    <!-- Isi Tabel (Akan dirender oleh JS) -->
                    <tbody class="divide-y divide-gray-200 text-gray-900" id="tableBody">
                        <!-- Data dummy (akan dihapus oleh JS dan diganti) -->
                        <tr class="animate-pulse">
                            <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                            <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                            <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded w-16"></div></td>
                            <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded w-24 mx-auto"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Bagian Footer Tabel (Info & Pagination) -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-gray-700 mt-2 p-2">
                <div id="paginationInfo">
                    Menampilkan <span class="font-semibold text-gray-900">...</span> ke <span class="font-semibold text-gray-900">...</span> dari <span class="font-semibold text-gray-900">...</span> entri
                </div>
                
                <nav class="inline-flex -space-x-px" aria-label="Pagination" id="paginationButtons">
                    <!-- Tombol pagination akan dirender oleh JS -->
                </nav>
            </div>

        </div>

</div>
@endsection

@push('scripts')
<script>
        // 1. Data Dummy (Sesuai gambar referensi)
        const accountData = [
            { id: 1, nama: 'I Wayan Sudarsana', email: 'wayan.sudarsana@denpasarkota.go.id', role: 'ADMIN' },
            { id: 2, nama: 'Ni Nyoman Anjani', email: 'nyoman.anjani@denpasarkota.go.id', role: 'ADMIN' },
            { id: 3, nama: 'Gede Satria', email: 'gede.satria@denpasarkota.go.id', role: 'ADMIN' },
            { id: 4, nama: 'Ketut Dharmawan', email: 'ketut.d@denpasarkota.go.id', role: 'ADMIN' },
            // Tambah data tambahan untuk testing pagination
            { id: 5, nama: 'Made Putra', email: 'made.putra@denpasarkota.go.id', role: 'ADMIN' },
            { id: 6, nama: 'Luh Sari', email: 'luh.sari@denpasarkota.go.id', role: 'OPERATOR' },
            { id: 24, nama: 'User Terakhir', email: 'user.24@denpasarkota.go.id', role: 'ADMIN' }
        ];

        // 2. State Pagination
        let currentPage = 1;
        const rowsPerPage = 4; // Menampilkan 4 baris per halaman sesuai gambar

        // 3. Referensi Elemen DOM
        const tableBody = document.getElementById('tableBody');
        const paginationInfo = document.getElementById('paginationInfo');
        const paginationButtons = document.getElementById('paginationButtons');

        // 4. Fungsi untuk merender data ke tabel
        function renderTable(data, page) {
            tableBody.innerHTML = ''; // Bersihkan loader/isi sebelumnya
            page--;

            let start = rowsPerPage * page;
            let end = start + rowsPerPage;
            let paginatedItems = data.slice(start, end);

            if (paginatedItems.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">Tidak ada data ditemukan.</td></tr>`;
                updatePaginationInfo(0, 0, 0);
                return;
            }

            paginatedItems.forEach(user => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-table-row-hover transition-colors';
                row.innerHTML = `
                    <td class="px-6 py-4 font-medium text-gray-950">${user.nama}</td>
                    <td class="px-6 py-4 text-gray-600">${user.email}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-bold text-white rounded-full bg-role-admin-bg">
                            ${user.role}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <button class="text-blue-600 hover:text-blue-800 font-medium text-sm mx-2 action-button transition" onclick="editUser(${user.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="action-icon h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>Edit
                        </button>
                        <button class="text-red-600 hover:text-red-800 font-medium text-sm mx-2 action-button transition" onclick="deleteUser(${user.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="action-icon h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>Hapus
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Update info di bawah tabel (misal: "Showing 1 to 4 of 24 entries")
            // Di gambar asli teksnya: "Showing 1 to 4 of 24 entries", kita asumsikan 24 adalah total data riil
            const totalItems = data.length;
            const realTotalCount = 24; // Hardcoded sesuai gambar untuk tampilan estetik
            const currentShowEnd = Math.min(start + paginatedItems.length, totalItems);
            
            updatePaginationInfo(start + 1, currentShowEnd, realTotalCount);
            renderPaginationButtons(totalItems, realTotalCount);
        }

        // 5. Fungsi Update Informasi Pagination
        function updatePaginationInfo(start, end, total) {
            if (total === 0) {
                paginationInfo.innerHTML = 'Tidak ada entri untuk ditampilkan';
                return;
            }
            paginationInfo.innerHTML = `Menampilkan <span class="font-semibold text-gray-900">${start}</span> ke <span class="font-semibold text-gray-900">${end}</span> dari <span class="font-semibold text-gray-900">${total}</span> entri`;
        }

        // 6. Fungsi Render Tombol Pagination
        function renderPaginationButtons(totalItemsInArray, totalEntriReal) {
            const pageCount = Math.ceil(totalEntriReal / rowsPerPage); // Asumsikan total halaman berdasarkan total entri real di gambar
            const pagesToShow = [1, 2, 3]; // Meniru gambar: 1, 2, 3
            
            paginationButtons.innerHTML = '';

            // Tombol Previous (<)
            const prevBtn = createPageButton('Prev', '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>');
            if (currentPage === 1) prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
            else prevBtn.addEventListener('click', () => { currentPage--; filterData(); });
            paginationButtons.appendChild(prevBtn);

            // Tombol Nomor Halaman
            pagesToShow.forEach(page => {
                if(page <= pageCount) {
                    const btn = createPageButton(page, page, page === currentPage);
                    btn.addEventListener('click', () => {
                        currentPage = page;
                        filterData();
                    });
                    paginationButtons.appendChild(btn);
                }
            });

            // Tombol Next (>)
            const nextBtn = createPageButton('Next', '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>');
            if (currentPage === pageCount) nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
            else nextBtn.addEventListener('click', () => { currentPage++; filterData(); });
            paginationButtons.appendChild(nextBtn);
        }

        // Helper fungsi membuat tombol
        function createPageButton(id, content, isActive = false) {
            const btn = document.createElement('button');
            let baseClasses = 'px-3.5 py-2 border border-gray-300 font-medium transition flex items-center justify-center ';
            
            // First/Last button rounding (agar sudut luarnya melengkung, sudut dalamnya siku)
            if (id === 'Prev') baseClasses += 'rounded-l-lg ';
            if (id === 'Next') baseClasses += 'rounded-r-lg ';

            if (isActive) {
                // Style tombol aktif (Hitam) sesuai gambar
                btn.className = baseClasses + 'bg-black text-white border-black z-10';
            } else {
                // Style tombol biasa
                btn.className = baseClasses + 'bg-white text-gray-600 hover:bg-gray-100';
            }
            btn.innerHTML = content;
            return btn;
        }

        // 7. Fungsi Pencarian (Filter)
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');

        function filterData() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const filteredData = accountData.filter(user => 
                user.nama.toLowerCase().includes(searchTerm) || 
                user.email.toLowerCase().includes(searchTerm)
            );
            
            // Reset ke halaman 1 saat mencari
            // Namun agar pagination tombol 1,2,3 terlihat seperti di gambar, kita tidak me-reset jika data asli dimuat
            renderTable(filteredData, currentPage);
        }

        searchButton.addEventListener('click', () => { currentPage = 1; filterData(); });
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                currentPage = 1;
                filterData();
            }
        });

        // 8. Fungsi Aksi (Hanya Placeholder)
        function editUser(id) {
            alert('Edit user ID: ' + id);
        }
        function deleteUser(id) {
            if(confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                alert('Hapus user ID: ' + id);
                // Logika hapus data riil di sini, lalu panggil filterData() kembali
            }
        }

        // Inisialisasi awal
        document.addEventListener('DOMContentLoaded', () => {
            filterData(); // Render pertama kali
        });

    </script>
@endpush