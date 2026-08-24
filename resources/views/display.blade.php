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

        <!-- KOLOM KANAN: Display Online (9 Cols) -->
        <section
            class="col-span-9 bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 pb-3.5 shrink-0">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-black text-slate-900 tracking-wide uppercase">Display Online</h2>
                </div>
            </div>

            <!-- Konten Display Online -->
            <div class="flex-1 py-4 overflow-hidden">
                <div class="h-full overflow-hidden flex flex-col justify-between">
                    <div class="flex-1 flex flex-col">
                        @if (!empty($linkVideoEmbeds))
                            <div class="flex items-center gap-2 pb-3 shrink-0">
                                <span
                                    class="text-xs font-black bg-rose-100 text-rose-700 border border-rose-200 px-3 py-1 rounded tracking-wide uppercase flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rose-600 animate-pulse"></span>
                                    Display Online
                                </span>
                                @if (count($linkVideoEmbeds) > 1)
                                    <span id="videoCounterBadge"
                                        class="text-xs font-bold text-slate-400 tracking-wide">1 / {{ count($linkVideoEmbeds) }}</span>
                                @endif
                            </div>
                            <div class="flex-1 rounded-xl overflow-hidden bg-black shadow-inner">
                                <iframe
                                    id="displayVideoFrame"
                                    src="{{ $linkVideoEmbeds[0] }}"
                                    class="w-full h-full"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @else
                            <div class="flex-1 flex items-center justify-center">
                                <p class="text-slate-400 text-xl font-bold">Belum ada video Display Online yang diatur</p>
                            </div>
                        @endif
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

        // Reload halaman setiap 5 menit agar data statistik ter-update
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

    @if (!empty($linkVideoEmbeds) && count($linkVideoEmbeds) > 1)
        <script type="application/json" id="displayVideoListData">{!! json_encode($linkVideoEmbeds) !!}</script>

        <!-- Script Rotasi Video Display Online (lebih dari 1 video, diputar bergantian sesuai urutan) -->
        <script>
            (function () {
                const dataEl = document.getElementById('displayVideoListData');
                const videoList = dataEl ? JSON.parse(dataEl.textContent) : [];
                const DURASI_PER_VIDEO_MS = 90 * 1000; // ganti video berikutnya setiap 90 detik

                if (videoList.length < 2) {
                    return;
                }

                let currentIndex = 0;
                const frame = document.getElementById('displayVideoFrame');
                const badge = document.getElementById('videoCounterBadge');

                function putarVideoBerikutnya() {
                    currentIndex = (currentIndex + 1) % videoList.length;

                    if (frame) {
                        frame.src = videoList[currentIndex];
                    }
                    if (badge) {
                        badge.textContent = (currentIndex + 1) + ' / ' + videoList.length;
                    }
                }

                setInterval(putarVideoBerikutnya, DURASI_PER_VIDEO_MS);
            })();
        </script>
    @endif
</body>

</html>