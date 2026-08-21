<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Informasi UKPBJ - TV Monitor (Light Theme)</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gov: {
                            navy: '#0F172A',
                            blue: '#1E40AF',
                            teal: '#0284C7',
                            gold: '#D97706',
                            card: '#FFFFFF'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Mencegah scroll pada TV Display */
        body {
            overflow: hidden;
        }

        .slide-content {
            display: none;
            height: 100%;
        }

        .slide-content.active {
            display: flex;
            flex-direction: column;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-slate-300 text-slate-800 font-sans h-screen flex flex-col justify-between box-border">

    <!-- 1. Header (Mentok Kanan-Kiri & Atas) -->
    <header
        class="bg-gov-navy border-b border-slate-700 px-8 py-3.5 flex items-center justify-between shadow-lg w-full shrink-0">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center font-bold text-2xl text-white shadow-md">
                <img src="{{ asset('/images/logo.png') }}" alt="Logo">
            </div>
            <div>
                <h1 class="font-extrabold text-2xl tracking-wide uppercase text-white">UKPBJ DIGITAL</h1>
                <p class="text-sm text-sky-400 font-medium">Unit Kerja Pengadaan Barang/Jasa Kota Denpasar</p>
            </div>
        </div>

        <!-- Live Clock & Date dengan Fallback -->
        <div class="text-right">
            <div id="clock" class="text-3xl font-black text-amber-400 font-mono tracking-wider">
                {{ ($now ?? now('Asia/Makassar'))->format('H:i:s') }} WITA
            </div>
            <div id="date" class="text-sm text-slate-300 font-semibold">
                {{ ($now ?? now('Asia/Makassar'))->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </header>

    <!-- 2. Main Content Grid (Rasio 3 : 9) -->
    <main class="grid grid-cols-12 gap-4 p-4 flex-1 overflow-hidden">

        <!-- KOLOM KIRI: Metric Cards & Live Summary (3 Cols) -->
        <section class="col-span-3 flex flex-col gap-3.5 h-full">

            <!-- Card Total Kunjungan -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-4 flex items-center gap-4 shadow-sm flex-1">
                <div
                    class="w-14 h-14 bg-blue-50 border border-blue-200 text-blue-600 rounded-xl flex items-center justify-center text-3xl font-bold shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider truncate">Total Kunjungan
                    </p>
                    <p class="text-4xl lg:text-5xl font-black text-slate-900 leading-none my-1 tracking-tight">
                        57{{ number_format($totalKunjungan ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-emerald-600 font-bold flex items-center gap-1 truncate">
                        <i class="fa-solid fa-arrow-trend-up"></i> Total Sepanjang Waktu
                    </p>
                </div>
            </div>

            <!-- Card Kunjungan Hari Ini -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-4 flex items-center gap-4 shadow-sm flex-1">
                <div
                    class="w-14 h-14 bg-blue-50 border border-blue-200 text-blue-600 rounded-xl flex items-center justify-center text-3xl font-bold shrink-0">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider truncate">Kunjungan Hari
                        Ini</p>
                    <p class="text-4xl lg:text-5xl font-black text-slate-900 leading-none my-1 tracking-tight">
                        1{{ number_format($kunjunganHariIni ?? 0, 0, ',', '.') }}
                    </p>
                    <p
                        class="text-xs {{ ($persenHariIni ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold flex items-center gap-1 truncate">
                        <i class="fa-solid fa-arrow-trend-{{ ($persenHariIni ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                        {{ ($persenHariIni ?? 0) >= 0 ? '+' : '' }}{{ $persenHariIni ?? 0 }}% Kemarin
                    </p>
                </div>
            </div>

            <!-- Card SKM / Survei -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-4 flex items-center gap-4 shadow-sm flex-1">
                <div
                    class="w-14 h-14 bg-amber-50 border border-amber-200 text-amber-500 rounded-xl flex items-center justify-center text-3xl font-bold shrink-0">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider truncate">Nilai SKM /
                        Survei</p>
                    <div class="flex items-baseline gap-1.5 my-1">
                        <p class="text-4xl lg:text-5xl font-black text-amber-500 leading-none tracking-tight">
                            9{{ number_format($nilaiSkm ?? 0, 2) }}
                        </p>
                        <span class="text-sm font-bold text-slate-400">/ 100</span>
                    </div>
                    <p class="text-xs text-slate-600 font-semibold truncate">
                        Responden: <strong
                            class="text-slate-900 font-bold">49{{ number_format($totalResponden ?? 0, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>

        </section>

        <!-- KOLOM KANAN: Auto Slider (9 Cols) -->
        <section
            class="col-span-9 bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">

            <!-- Header Slider & Indicator -->
            <div class="flex items-center justify-between border-b border-slate-200 pb-3.5 shrink-0">
                <div class="flex items-center gap-3">
                    <h2 id="slide-title" class="text-lg font-black text-slate-900 tracking-wide uppercase">SLIDE TITLE
                    </h2>
                </div>
                <!-- Indicator Bullets -->
                <div class="flex gap-2">
                    <div class="dot w-8 h-2 bg-gov-teal rounded-full transition-all"></div>
                    <div class="dot w-2.5 h-2 bg-slate-300 rounded-full transition-all"></div>
                </div>
            </div>

            <!-- Slide Content Wrapper -->
            <div class="flex-1 py-4 overflow-hidden">

                <!-- SLIDE 1: Jadwal Dinas Pegawai -->
                <div class="slide-content active" data-title="Jadwal Dinas Petugas Hari Ini">
                    <div class="h-full overflow-hidden flex flex-col justify-between">
                        @if (empty($jadwalHariIni) || $jadwalHariIni->isEmpty())
                            <div class="flex-1 flex items-center justify-center">
                                <p class="text-slate-400 text-xl font-bold">Tidak ada jadwal dinas untuk hari ini</p>
                            </div>
                        @else
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="text-slate-600 border-b-2 border-slate-200 text-base font-extrabold uppercase tracking-wider">
                                        <th class="py-3.5 px-4">Pegawai Bertugas</th>
                                        <th class="py-3.5 px-4">Acara / Kegiatan</th>
                                        <th class="py-3.5 px-4">Waktu</th>
                                        <th class="py-3.5 px-4">Tempat / Zoom</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 text-base">
                                    @foreach ($jadwalHariIni as $jadwal)
                                        @forelse($jadwal->pegawais as $pegawai)
                                            <tr>
                                                <td class="py-4 px-4 font-black text-slate-900 text-lg">
                                                    {{ $pegawai->name ?? $pegawai->nama_lengkap }}
                                                </td>
                                                <td class="py-4 px-4 text-blue-700 font-extrabold text-lg">
                                                    {{ $jadwal->acara }}
                                                </td>
                                                <td class="py-4 px-4 text-slate-800 font-semibold">
                                                    {{ $jadwal->waktu ? \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') : '-' }}
                                                </td>
                                                <td class="py-4 px-4 text-slate-800 font-semibold">
                                                    {{ $jadwal->tempat_zoom ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-4 px-4 text-slate-500 font-medium italic">
                                                    {{ $jadwal->acara }} — belum ada pegawai ditugaskan
                                                </td>
                                            </tr>
                                        @endforelse
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <!-- SLIDE 2: Pengumuman & Informasi Layanan (Full Grid 4 Card) -->
                <div class="slide-content" data-title="Pengumuman & Layanan Informasi Utama">
                    <div class="grid grid-cols-2 gap-4 h-full">

                        <!-- CARD 1: KEPALA BAGIAN -->
                        <div
                            class="bg-slate-50 p-4.5 rounded-xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="text-xs font-black bg-blue-100 text-blue-800 border border-blue-200 px-3 py-1 rounded tracking-wide uppercase">SUB
                                        BAGIAN KEPALA BAGIAN</span>
                                    <i class="fa-solid fa-user-tie text-blue-600 text-lg"></i>
                                </div>
                                <h3 class="text-base font-black text-slate-900 border-b border-slate-200 pb-2 mb-3">
                                    Layanan Kebijakan & Sinergi</h3>
                                <ul class="space-y-2.5 text-sm text-slate-700 font-medium">
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-blue-600 text-sm mt-0.5"></i><span><strong>Koordinasi
                                                Strategis:</strong> Pelaksanaan pembinaan pengadaan daerah.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-blue-600 text-sm mt-0.5"></i><span><strong>Penetapan
                                                Kebijakan:</strong> Arahan regulasi & operasional PBJ.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-blue-600 text-sm mt-0.5"></i><span><strong>Monitoring
                                                Kinerja:</strong> Evaluasi berkala seluruh unit kerja.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-blue-600 text-sm mt-0.5"></i><span><strong>Audience
                                                Stakeholder:</strong> Layanan konsultasi pimpinan SKPD.</span></li>
                                </ul>
                            </div>
                        </div>

                        <!-- CARD 2: ADVOKASI DAN SDM -->
                        <div
                            class="bg-slate-50 p-4.5 rounded-xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="text-xs font-black bg-indigo-100 text-indigo-800 border border-indigo-200 px-3 py-1 rounded tracking-wide uppercase">ADVOKASI
                                        & SDM</span>
                                    <i class="fa-solid fa-scale-balanced text-indigo-600 text-lg"></i>
                                </div>
                                <h3 class="text-base font-black text-slate-900 border-b border-slate-200 pb-2 mb-3">
                                    Layanan Hukum & Bimbingan Teknis</h3>
                                <ul class="space-y-2.5 text-sm text-slate-700 font-medium">
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-indigo-600 text-sm mt-0.5"></i><span><strong>Konsultasi
                                                Hukum PBJ:</strong> Pendampingan penyusunan kontrak.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-indigo-600 text-sm mt-0.5"></i><span><strong>Bimbingan
                                                Teknis:</strong> Pelatihan sertifikasi kompetensi SDM.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-indigo-600 text-sm mt-0.5"></i><span><strong>Penyelesaian
                                                Sanggah:</strong> Penanganan aduan & sengketa tender.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-indigo-600 text-sm mt-0.5"></i><span><strong>Layanan
                                                Probity Advice:</strong> Pendampingan pengadaan strategis.</span></li>
                                </ul>
                            </div>
                        </div>

                        <!-- CARD 3: LPSE -->
                        <div
                            class="bg-slate-50 p-4.5 rounded-xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-1 rounded tracking-wide uppercase">LAYANAN
                                        LPSE</span>
                                    <i class="fa-solid fa-desktop text-emerald-600 text-lg"></i>
                                </div>
                                <h3 class="text-base font-black text-slate-900 border-b border-slate-200 pb-2 mb-3">
                                    Layanan Sistem & E-Katalog</h3>
                                <ul class="space-y-2.5 text-sm text-slate-700 font-medium">
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5"></i><span><strong>Registrasi
                                                Penyedia:</strong> Verifikasi akun SPSE pelaku usaha.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5"></i><span><strong>E-Katalog
                                                Lokal:</strong> Pendaftaran produk & penayangan komoditas.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5"></i><span><strong>Helpdesk
                                                LPSE:</strong> Panduan teknis penggunaan aplikasi SPSE.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5"></i><span><strong>Reset
                                                Pass & Data:</strong> Layanan keamanan akun PPK/Penyedia.</span></li>
                                </ul>
                            </div>
                        </div>

                        <!-- CARD 4: POKJA PEMILIHAN -->
                        <div
                            class="bg-slate-50 p-4.5 rounded-xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="text-xs font-black bg-amber-100 text-amber-800 border border-amber-200 px-3 py-1 rounded tracking-wide uppercase">POKJA
                                        PEMILIHAN</span>
                                    <i class="fa-solid fa-gavel text-amber-600 text-lg"></i>
                                </div>
                                <h3 class="text-base font-black text-slate-900 border-b border-slate-200 pb-2 mb-3">
                                    Layanan Pelaksanaan Tender</h3>
                                <ul class="space-y-2.5 text-sm text-slate-700 font-medium">
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-amber-600 text-sm mt-0.5"></i><span><strong>Review
                                                Dokumen:</strong> Penelaahan RUP & persiapan seleksi.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-amber-600 text-sm mt-0.5"></i><span><strong>Evaluasi
                                                Penawaran:</strong> Penilaian kualifikasi, teknis & harga.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-amber-600 text-sm mt-0.5"></i><span><strong>Pembuktian
                                                Kualifikasi:</strong> Klarifikasi berkas penyedia secara
                                            transparan.</span></li>
                                    <li class="flex items-start gap-2.5"><i
                                            class="fa-solid fa-circle-check text-amber-600 text-sm mt-0.5"></i><span><strong>Penetapan
                                                Pemenang:</strong> Pengumuman resmi tender/seleksi.</span></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- 3. Footer (Mentok Kanan-Kiri & Bawah) -->
    <footer class="bg-gov-navy border-t border-slate-700 px-6 py-2.5 flex items-center gap-3 shadow w-full shrink-0">
        <div
            class="bg-gov-gold text-slate-950 font-extrabold text-sm px-3 py-1 rounded shrink-0 uppercase tracking-wider">
            INFO PENTING
        </div>
        <marquee class="text-sm text-slate-200 font-semibold tracking-wide">
            Selamat Datang di Portal UKPBJ • Jam Pelayanan Tatap Muka: Senin - Jumat (08.00 - 15.30 WITA) • Utamakan
            Transparansi dan Bebas Pungli dalam Pengadaan Barang & Jasa Pemerintah • Layanan Helpdesk & Konsultasi
            Online dapat diakses melalui portal resmi.
        </marquee>
    </footer>

    <audio id="bgAudio" loop>
        <source src="{{ asset('/images/bali.mp3') }}" type="audio/mpeg">
    </audio>

    <!-- Script Otomatisasi TV Display -->
    <script>
        // 1. Sinkronisasi Waktu Server (WITA)
        let serverTime = new Date("{{ ($now ?? now('Asia/Makassar'))->toIso8601String() }}");

        function updateClock() {
            serverTime.setSeconds(serverTime.getSeconds() + 1);

            const hours = String(serverTime.getHours()).padStart(2, '0');
            const minutes = String(serverTime.getMinutes()).padStart(2, '0');
            const seconds = String(serverTime.getSeconds()).padStart(2, '0');

            const clockEl = document.getElementById('clock');
            if (clockEl) {
                clockEl.textContent = `${hours}:${minutes}:${seconds} WITA`;
            }
        }
        setInterval(updateClock, 1000);

        // 2. Auto-Slider Logic (Perpindahan otomatis tanpa progress bar)
        const slides = document.querySelectorAll('.slide-content');
        const dots = document.querySelectorAll('.dot');
        const slideTitle = document.getElementById('slide-title');

        let currentSlide = 0;
        const slideDuration = 15000; // 15 detik per slide

        function showSlide(index) {
            slides.forEach((slide, idx) => {
                slide.classList.remove('active');
                if (dots[idx]) {
                    dots[idx].classList.remove('w-8', 'bg-gov-teal');
                    dots[idx].classList.add('w-2.5', 'bg-slate-300');
                }
            });

            slides[index].classList.add('active');
            if (dots[index]) {
                dots[index].classList.remove('w-2.5', 'bg-slate-300');
                dots[index].classList.add('w-8', 'bg-gov-teal');
            }

            if (slideTitle) {
                slideTitle.textContent = slides[index].getAttribute('data-title');
            }
        }

        if (slides.length > 0 && slideTitle) {
            slideTitle.textContent = slides[0].getAttribute('data-title');
        }

        // Jalankan pergantian slide secara bersih setiap 15 detik
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, slideDuration);

        // Reload halaman setiap 5 menit agar data statistik & jadwal ter-update
        setTimeout(() => location.reload(), 5 * 60 * 1000);


        const bgAudio = document.getElementById('bgAudio');

        // Coba autoplay langsung
        bgAudio.volume = 0.5;
        bgAudio.play().catch(() => {
            // Jika diblokir browser, play saat ada interaksi pertama
            document.addEventListener('click', () => bgAudio.play(), {
                once: true
            });
        });
    </script>
</body>

</html>