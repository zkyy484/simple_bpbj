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
            <div id="clock"
                data-jam="{{ (int) ($now ?? now('Asia/Makassar'))->format('H') }}"
                data-menit="{{ (int) ($now ?? now('Asia/Makassar'))->format('i') }}"
                data-detik="{{ (int) ($now ?? now('Asia/Makassar'))->format('s') }}"
                class="text-3xl font-black text-amber-400 font-mono tracking-wider">
                {{ ($now ?? now('Asia/Makassar'))->format('H:i:s') }} WITA
            </div>
            <div id="date"
                data-tanggal="{{ ($now ?? now('Asia/Makassar'))->format('Y-m-d') }}"
                class="text-sm text-slate-300 font-semibold">
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
                    <p id="statTotalKunjungan" class="text-4xl lg:text-5xl font-black text-slate-900 leading-none my-1 tracking-tight">
                        {{ number_format($totalKunjungan ?? 0, 0, ',', '.') }}
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
                    <p id="statKunjunganHariIni" class="text-4xl lg:text-5xl font-black text-slate-900 leading-none my-1 tracking-tight">
                        {{ number_format($kunjunganHariIni ?? 0, 0, ',', '.') }}
                    </p>
                    <p id="statPersenHariIni"
                        class="text-xs {{ ($persenHariIni ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold flex items-center gap-1 truncate">
                        <i id="statPersenIcon" class="fa-solid fa-arrow-trend-{{ ($persenHariIni ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                        <span id="statPersenText">{{ ($persenHariIni ?? 0) >= 0 ? '+' : '' }}{{ $persenHariIni ?? 0 }}% Kemarin</span>
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
                        <p id="statNilaiSkm" class="text-4xl lg:text-5xl font-black text-amber-500 leading-none tracking-tight">
                            {{ number_format($nilaiSkm ?? 0, 2) }}
                        </p>
                        <span class="text-sm font-bold text-slate-400">/ 100</span>
                    </div>
                    <p class="text-xs text-slate-600 font-semibold truncate">
                        Responden: <strong id="statTotalResponden"
                            class="text-slate-900 font-bold">{{ number_format($totalResponden ?? 0, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>

        </section>

        <!-- KOLOM KANAN: Display Online (9 Cols) -->
        <section
            class="col-span-9 bg-white border border-slate-200/80 rounded-xl shadow-sm flex flex-col relative overflow-hidden">

            @if (!empty($linkVideoEmbeds))
                <!-- Badge status mengambang di atas video -->
                <div class="absolute top-4 left-4 z-10 flex items-center gap-2">
                    @if (count($linkVideoEmbeds) > 1)
                        <span id="videoCounterBadge"
                            class="text-xs font-bold text-white bg-slate-900/70 px-2 py-1 rounded tracking-wide">1 / {{ count($linkVideoEmbeds) }}</span>
                    @endif
                </div>

                <!-- Video full-bleed -->
                <div class="flex-1 bg-black">
                    <iframe
                        id="displayVideoFrame"
                        src="{{ $linkVideoEmbeds[0] }}"
                        class="w-full h-full block"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
            @else
                <div class="flex items-center justify-between border-b border-slate-200 px-6 pt-6 pb-3.5 shrink-0">
                </div>
                <div class="flex-1 flex items-center justify-center p-6">
                    <p class="text-slate-400 text-xl font-bold">Belum ada video Display Online yang diatur</p>
                </div>
            @endif
        </section>

    </main>

    <!-- 3. Footer (Disesuaikan Menggunakan bg-gov-navy Agar Senada) -->
    <footer class="w-full shrink-0">
        <div class="bg-gov-navy border-t border-slate-700 px-8 py-3.5 flex items-center gap-4 shadow-lg">
            <div
                class="bg-[#1E3A8A] text-white font-extrabold text-base px-4 py-2 rounded-lg shrink-0 uppercase tracking-wider flex items-center gap-2.5 shadow-md border border-slate-600">
                <i class="fa-solid fa-calendar-days text-xl text-sky-400"></i>
                Jadwal Dinas Hari Ini
            </div>
            <div id="jadwalDinasWrap" class="flex-1 overflow-hidden">
                @if (isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
                    <marquee id="jadwalDinasMarquee" class="text-lg text-slate-100 font-semibold tracking-wide">
                        @foreach ($jadwalHariIni as $jadwal)
                            @php
                                $namaPegawai = $jadwal->pegawais->pluck('nama_lengkap')->filter()->implode(', ');
                            @endphp
                            @if ($jadwal->waktu)
                                <strong class="text-amber-400 font-bold">{{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }} WITA</strong> —
                            @endif
                            {{ $jadwal->acara }}
                            @if ($jadwal->bidang_sekretariat)
                                ({{ $jadwal->bidang_sekretariat }})
                            @endif
                            @if ($jadwal->tempat_zoom)
                                di {{ $jadwal->tempat_zoom }}
                            @endif
                            @if ($namaPegawai)
                                — Dihadiri: {{ $namaPegawai }}
                            @endif
                            @if ($jadwal->surat_dari)
                                (Surat dari: {{ $jadwal->surat_dari }})
                            @endif
                            &nbsp;•&nbsp;
                        @endforeach
                    </marquee>
                @else
                    <p id="jadwalDinasKosong" class="text-lg text-slate-300 font-semibold tracking-wide">
                        Tidak ada jadwal dinas untuk hari ini.
                    </p>
                @endif
            </div>
        </div>
    </footer>

    <!-- Script Otomatisasi TV Display -->
    <script>
        // 1. Sinkronisasi Waktu Server (WITA)
        const clockEl = document.getElementById('clock');
        const dateEl = document.getElementById('date');
        let jamServer   = parseInt(clockEl?.dataset.jam ?? '0', 10);
        let menitServer = parseInt(clockEl?.dataset.menit ?? '0', 10);
        let detikServer = parseInt(clockEl?.dataset.detik ?? '0', 10);

        let tanggalServer = dateEl?.dataset.tanggal ? new Date(dateEl.dataset.tanggal + 'T00:00:00') : null;
        const formatterTanggal = new Intl.DateTimeFormat('id-ID', {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });

        function updateClock() {
            detikServer++;
            if (detikServer >= 60) {
                detikServer = 0;
                menitServer++;
            }
            if (menitServer >= 60) {
                menitServer = 0;
                jamServer++;
            }
            if (jamServer >= 24) {
                jamServer = 0;
            }

            const hours = String(jamServer).padStart(2, '0');
            const minutes = String(menitServer).padStart(2, '0');
            const seconds = String(detikServer).padStart(2, '0');

            if (clockEl) {
                clockEl.textContent = `${hours}:${minutes}:${seconds} WITA`;
            }

            if (jamServer === 0 && menitServer === 0 && detikServer === 2) {
                if (tanggalServer) {
                    tanggalServer.setDate(tanggalServer.getDate() + 1);
                    if (dateEl) {
                        dateEl.textContent = formatterTanggal.format(tanggalServer);
                    }
                }
            }
        }
        setInterval(updateClock, 1000);

        // 2. Auto refresh statistik via AJAX
        function formatRibuan(num) {
            return new Intl.NumberFormat('id-ID').format(num ?? 0);
        }

        function refreshStatistikDisplay() {
            fetch("{{ route('display.stats') }}", {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => {
                    if (!res.ok) throw new Error('Response tidak OK');
                    return res.json();
                })
                .then(data => {
                    const elTotalKunjungan = document.getElementById('statTotalKunjungan');
                    const elKunjunganHariIni = document.getElementById('statKunjunganHariIni');
                    const elPersenWrap = document.getElementById('statPersenHariIni');
                    const elPersenIcon = document.getElementById('statPersenIcon');
                    const elPersenText = document.getElementById('statPersenText');
                    const elNilaiSkm = document.getElementById('statNilaiSkm');
                    const elTotalResponden = document.getElementById('statTotalResponden');

                    if (elTotalKunjungan) elTotalKunjungan.textContent = formatRibuan(data.totalKunjungan);
                    if (elKunjunganHariIni) elKunjunganHariIni.textContent = formatRibuan(data.kunjunganHariIni);

                    const persen = data.persenHariIni ?? 0;
                    if (elPersenWrap && elPersenIcon && elPersenText) {
                        elPersenWrap.classList.remove('text-emerald-600', 'text-rose-600');
                        elPersenWrap.classList.add(persen >= 0 ? 'text-emerald-600' : 'text-rose-600');
                        elPersenIcon.classList.remove('fa-arrow-trend-up', 'fa-arrow-trend-down');
                        elPersenIcon.classList.add(persen >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down');
                        elPersenText.textContent = `${persen >= 0 ? '+' : ''}${persen}% Kemarin`;
                    }

                    if (elNilaiSkm) elNilaiSkm.textContent = Number(data.nilaiSkm ?? 0).toFixed(2);
                    if (elTotalResponden) elTotalResponden.textContent = formatRibuan(data.totalResponden);
                })
                .catch(err => console.error('Gagal refresh statistik Display TV:', err));
        }

        setInterval(refreshStatistikDisplay, 30 * 1000);

        // 3. Auto refresh Jadwal Dinas Hari Ini via AJAX
        function bangunTeksJadwal(item) {
            let teks = '';
            if (item.waktu) {
                teks += `<strong class="text-amber-400 font-bold">${item.waktu} WITA</strong> — `;
            }
            teks += item.acara;
            if (item.bidang_sekretariat) {
                teks += ` (${item.bidang_sekretariat})`;
            }
            if (item.tempat_zoom) {
                teks += ` di ${item.tempat_zoom}`;
            }
            if (item.nama_pegawai) {
                teks += ` — Dihadiri: ${item.nama_pegawai}`;
            }
            if (item.surat_dari) {
                teks += ` (Surat dari: ${item.surat_dari})`;
            }
            return teks;
        }

        function refreshJadwalDinas() {
            fetch("{{ route('display.jadwal') }}", {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => {
                    if (!res.ok) throw new Error('Response tidak OK');
                    return res.json();
                })
                .then(data => {
                    const wrap = document.getElementById('jadwalDinasWrap');
                    if (!wrap) return;

                    const list = data.jadwal ?? [];
                    if (list.length > 0) {
                        const isi = list.map(bangunTeksJadwal).join('&nbsp;•&nbsp;');
                        wrap.innerHTML = `<marquee id="jadwalDinasMarquee" class="text-lg text-slate-100 font-semibold tracking-wide">${isi}</marquee>`;
                    } else {
                        wrap.innerHTML = `<p id="jadwalDinasKosong" class="text-lg text-slate-300 font-semibold tracking-wide">Tidak ada jadwal dinas untuk hari ini.</p>`;
                    }
                })
                .catch(err => console.error('Gagal refresh Jadwal Dinas Display TV:', err));
        }

        setInterval(refreshJadwalDinas, 60 * 1000);
    </script>

    @if (!empty($linkVideoEmbeds) && count($linkVideoEmbeds) > 1)
        <script type="application/json" id="displayVideoListData">{!! json_encode($linkVideoEmbeds) !!}</script>

        <!-- Script Rotasi Video Display Online -->
        <script>
            (function () {
                const dataEl = document.getElementById('displayVideoListData');
                const videoList = dataEl ? JSON.parse(dataEl.textContent) : [];

                if (videoList.length < 2) {
                    return;
                }

                const BATAS_MAKSIMAL_DEFAULT_MS = 3 * 60 * 60 * 1000;
                const BUFFER_SETELAH_DURASI_MS = 60 * 1000;

                let currentIndex = 0;
                let fallbackTimer = null;
                let player = null;

                const frame = document.getElementById('displayVideoFrame');
                const badge = document.getElementById('videoCounterBadge');

                function ekstrakVideoId(url) {
                    const m = url.match(/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/);
                    return m ? m[1] : null;
                }

                function perbaruiBadge() {
                    if (badge) {
                        badge.textContent = (currentIndex + 1) + ' / ' + videoList.length;
                    }
                }

                function hitungDurasiFallback() {
                    if (player && typeof player.getDuration === 'function') {
                        const durasiDetik = player.getDuration();
                        if (durasiDetik && durasiDetik > 0) {
                            return (durasiDetik * 1000) + BUFFER_SETELAH_DURASI_MS;
                        }
                    }
                    return BATAS_MAKSIMAL_DEFAULT_MS;
                }

                function pasangFallbackTimer() {
                    clearTimeout(fallbackTimer);
                    fallbackTimer = setTimeout(putarVideoBerikutnya, hitungDurasiFallback());
                }

                function putarVideoBerikutnya() {
                    clearTimeout(fallbackTimer);
                    currentIndex = (currentIndex + 1) % videoList.length;
                    perbaruiBadge();

                    const urlBerikutnya = videoList[currentIndex];
                    const idBerikutnya = ekstrakVideoId(urlBerikutnya);

                    if (player && idBerikutnya) {
                        player.loadVideoById(idBerikutnya);
                    } else if (frame) {
                        frame.src = urlBerikutnya;
                        pasangFallbackTimer();
                    }
                }

                function inisialisasiYouTubePlayer() {
                    const idPertama = ekstrakVideoId(videoList[0]);

                    if (!idPertama || !frame || typeof YT === 'undefined' || !YT.Player) {
                        pasangFallbackTimer();
                        return;
                    }

                    player = new YT.Player('displayVideoFrame', {
                        events: {
                            onReady: function () {
                                pasangFallbackTimer();
                            },
                            onStateChange: function (event) {
                                if (event.data === YT.PlayerState.ENDED) {
                                    putarVideoBerikutnya();
                                } else if (event.data === YT.PlayerState.PLAYING) {
                                    pasangFallbackTimer();
                                }
                            },
                            onError: function () {
                                putarVideoBerikutnya();
                            }
                        }
                    });
                }

                perbaruiBadge();

                if (typeof YT !== 'undefined' && YT.Player) {
                    inisialisasiYouTubePlayer();
                } else {
                    const tag = document.createElement('script');
                    tag.src = 'https://www.youtube.com/iframe_api';
                    document.head.appendChild(tag);

                    const originalReady = window.onYouTubeIframeAPIReady;
                    window.onYouTubeIframeAPIReady = function () {
                        if (typeof originalReady === 'function') {
                            originalReady();
                        }
                        inisialisasiYouTubePlayer();
                    };

                    setTimeout(function () {
                        if (!player) {
                            pasangFallbackTimer();
                        }
                    }, 8000);
                }
            })();
        </script>
    @endif
</body>

</html>