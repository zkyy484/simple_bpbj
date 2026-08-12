@extends('admin.layouts.app')

@section('title', 'Manajemen Tamu - Buku Tamu Digital')

@section('content')
    <div x-data="{
        openDetail: false,
        openDelete: false,
        openApprove: false,
        selected: {
            id: '', kode_tiket: '', nama_lengkap: '', email: '', no_telp: '',
            sub_bagian: '', tujuan: '', pegawai: '', permasalahan: '', solusi: '',
            status_tindak_lanjut: '', status: '', approval: ''
        },
        updateUrl: '',
        deleteUrl: '',
        approveUrl: '',

        setDetail(tamu) {
            this.selected = tamu;
            this.updateUrl = '{{ url('/admin/tamu') }}/' + tamu.id;
            this.openDetail = true;
        },

        setDelete(tamu) {
            this.selected = tamu;
            this.deleteUrl = '{{ url('/admin/tamu') }}/' + tamu.id;
            this.openDelete = true;
        },

        setApprove(tamu) {
            this.selected = tamu;
            this.approveUrl = '{{ url('/admin/tamu') }}/' + tamu.id + '/approval';
            this.openApprove = true;
        }
    }" class="relative" :data-modal-open="openDetail || openDelete || openApprove">

        <!-- CONTENT MAIN -->
        <div class="space-y-6 transition-all duration-300"
            :class="{ 'blur-sm pointer-events-none select-none scale-[0.99]': openDetail || openDelete || openApprove }">

            {{-- Breadcrumb & Title --}}
            <div>
                <div class="text-sm text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span class="mx-1">/</span>
                    <span class="text-gray-700 font-medium">Tamu</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Tamu</h1>
            </div>

            {{-- Search Bar --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col lg:flex-row justify-between items-center gap-5">
                <form action="{{ route('admin.tamu.index') }}" method="GET" class="flex-1 w-full max-w-lg">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama / Kode Tiket / Sub Bagian / Tujuan..."
                            class="w-full bg-[#f0f2f5] border-none rounded-lg pl-4 pr-12 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#173860] outline-none">
                        <button type="submit" class="absolute right-1 px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] text-white rounded-md transition flex items-center justify-center">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table Card (auto-refresh via AJAX, tidak termasuk kotak pencarian) --}}
            <div id="tabel-tamu-wrapper" data-refresh-url="{{ route('admin.tamu.index') }}">
                @include('admin.tamu.partials.tabel-tamu')
            </div>

        </div>

        {{-- INCLUDES MODAL --}}
        @include('admin.tamu.show')
        @include('admin.tamu.approve')

    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    // ==========================================================
    // AUTO REFRESH (AJAX) - KHUSUS KOMPONEN "DAFTAR TAMU"
    // Pencarian TETAP normal (submit form / reload), TIDAK dibuat realtime.
    // ==========================================================
    (function () {
        const wrapper = document.getElementById('tabel-tamu-wrapper');
        if (!wrapper) return;

        const REFRESH_INTERVAL = 1000; // 1 detik, ubah sesuai kebutuhan

        let isRefreshing = false;  // mencegah request tumpuk
        let timerId = null;

        // Elemen x-data terdekat membawa atribut "data-modal-open" yang di-bind
        // secara reaktif ke (openDetail || openDelete || openApprove), sehingga
        // refresh otomatis bisa dijeda selama modal detail/approve sedang terbuka.
        const alpineRoot = wrapper.closest('[x-data]');
        function isModalOpen() {
            return !!alpineRoot && alpineRoot.dataset.modalOpen === 'true';
        }

        function buildRefreshUrl() {
            // Pertahankan query string aktif (search, page, dll) apa adanya.
            // Tambahkan flag ajax=1 sebagai penanda ke server.
            const url = new URL(window.location.href);
            url.searchParams.set('ajax', '1');
            return url.toString();
        }

        function refreshTable() {
            if (isRefreshing || isModalOpen() || document.hidden) return;
            isRefreshing = true;

            fetch(buildRefreshUrl(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
                credentials: 'same-origin',
            })
                .then((res) => {
                    if (!res.ok) throw new Error('Gagal memuat data tamu.');
                    return res.text();
                })
                .then((html) => {
                    wrapper.innerHTML = html;

                    // Re-init directive Alpine pada HTML baru agar tombol
                    // Detail/Approval tetap berfungsi.
                    if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                        window.Alpine.initTree(wrapper);
                    }

                    // Render ulang ikon Lucide untuk elemen yang baru disisipkan.
                    if (window.lucide && typeof window.lucide.createIcons === 'function') {
                        window.lucide.createIcons();
                    }
                })
                .catch(() => {
                    // Diamkan saja saat auto-refresh gagal (mis. koneksi terputus),
                    // agar tidak mengganggu admin yang sedang bekerja. Coba lagi di interval berikutnya.
                })
                .finally(() => {
                    isRefreshing = false;
                });
        }

        function startAutoRefresh() {
            stopAutoRefresh();
            timerId = setInterval(refreshTable, REFRESH_INTERVAL);
        }

        function stopAutoRefresh() {
            if (timerId) {
                clearInterval(timerId);
                timerId = null;
            }
        }

        // Jangan boros request saat tab tidak aktif; refresh sekali saat kembali aktif.
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) refreshTable();
        });

        startAutoRefresh();
    })();
</script>
@endpush