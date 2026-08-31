@extends('super-admin.layouts.app')

@section('title', 'Dashboard - Buku Tamu Digital')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
            <p class="text-sm text-gray-500 mt-0.5">Ringkasan aktivitas kunjungan dan survei hari ini.</p>
        </div>

        <!-- KPI UTAMA -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <!-- KPI 1: Total Kunjungan Tamu -->
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Kunjungan Tamu</span>
                    <span class="p-2 bg-blue-50 rounded-lg text-[#173860]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-4xl font-bold text-gray-900">{{ $totalKunjungan }}</h3>
                    <p
                        class="text-xs font-medium mt-2 flex items-center {{ $persenBulan >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                        @if ($persenBulan >= 0)
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12 7a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L13 10.414V17a1 1 0 11-2 0v-6.586l-2.293 2.293a1 1 0 01-1.414-1.414l4-4A1 1 0 0112 7z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12 13a1 1 0 01-.707-.293l-4-4a1 1 0 111.414-1.414L11 9.586V3a1 1 0 112 0v6.586l2.293-2.293a1 1 0 111.414 1.414l-4 4A1 1 0 0112 13z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                        {{ $persenBulan >= 0 ? '+' : '' }}{{ $persenBulan }}% <span
                            class="text-gray-400 font-normal ml-1">vs bulan lalu</span>
                    </p>
                </div>
            </div>

            <!-- KPI 2: Kunjungan Hari Ini -->
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kunjungan Hari Ini</span>
                    <span class="p-2 bg-blue-50 rounded-lg text-[#173860]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-4xl font-bold text-gray-900">{{ $kunjunganHariIni }}</h3>
                    <p
                        class="text-xs font-medium mt-2 flex items-center {{ $persenHari >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                        @if ($persenHari >= 0)
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12 7a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L13 10.414V17a1 1 0 11-2 0v-6.586l-2.293 2.293a1 1 0 01-1.414-1.414l4-4A1 1 0 0112 7z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12 13a1 1 0 01-.707-.293l-4-4a1 1 0 111.414-1.414L11 9.586V3a1 1 0 112 0v6.586l2.293-2.293a1 1 0 111.414 1.414l-4 4A1 1 0 0112 13z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                        {{ $persenHari >= 0 ? '+' : '' }}{{ $persenHari }}% <span
                            class="text-gray-400 font-normal ml-1">vs kemarin</span>
                    </p>
                </div>
            </div>

            <!-- KPI 3: Total Survei Masuk -->
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Survei Masuk</span>
                    <span class="p-2 bg-blue-50 rounded-lg text-[#173860]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-4xl font-bold text-gray-900">{{ $totalSurvei }}</h3>
                    <p class="text-xs text-gray-400 font-normal mt-2">Sejak bulan ini</p>
                </div>
            </div>

        </div>

        <!-- DISTRIBUSI PER SUB BAGIAN -->
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Distribusi Kunjungan per Sub Bagian</h3>
            @if ($distribusiSubBagian->isEmpty())
                <p class="text-sm text-gray-400">Belum ada data kunjungan.</p>
            @else
                <!-- Mengubah grid menjadi 4 kolom di layar laptop/desktop (lg:grid-cols-4) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach ($distribusiSubBagian as $i => $sb)
                        {{-- Warna diterapkan lewat data-attribute + JS, bukan langsung di style="",
                             supaya parser CSS editor tidak salah baca sintaks Blade sebagai CSS. --}}
                        <div class="rounded-xl p-4 sub-bagian-card"
                            data-bg-color="{{ $warnaSubBagian[$i % count($warnaSubBagian)] }}">
                            <p class="text-[11px] font-semibold text-white">{{ $sb->nama_sub_bagian }}</p>
                            <p class="text-2xl font-bold text-white mt-2">{{ $sb->tamus_count }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- CHARTS SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Doughnut Chart Card -->
            <div class="bg-white p-6 rounded-2xl shadow-sm lg:col-span-5 flex flex-col justify-between items-center">
                <h3 class="font-bold text-gray-900 text-base self-start mb-2">Distribusi Sub Bagian</h3>
                <div class="relative w-56 h-56 mx-auto my-auto">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="flex flex-wrap justify-center gap-x-4 gap-y-1.5 text-xs font-medium text-gray-600 mt-4">
                    @foreach ($distribusiSubBagian as $i => $sb)
                        <span class="flex items-center">
                            <span class="w-2.5 h-2.5 rounded-full inline-block mr-1.5 legend-dot"
                                data-bg-color="{{ $warnaSubBagian[$i % count($warnaSubBagian)] }}"></span>
                            {{ $sb->nama_sub_bagian }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Line Chart Card -->
            <div class="bg-white p-6 rounded-2xl shadow-sm lg:col-span-7">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-900 text-base">Aktivitas Kunjungan</h3>
                    <select id="filterMinggu"
                        class="text-xs bg-gray-100 border border-gray-200 rounded-lg px-2.5 py-1.5 text-gray-600 outline-none cursor-pointer">
                        @foreach ($opsiMinggu as $opsi)
                            <option value="{{ $opsi['value'] }}" {{ (int) $opsi['value'] === $minggu ? 'selected' : '' }}>
                                {{ $opsi['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="h-60">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

        </div>

        <!-- LOG AKTIVITAS TERBARU (TABLE) -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-base">Log Aktivitas Terbaru</h3>
                <a href="{{ route('super.tamu.index') }}" class="text-xs font-semibold text-[#173860] hover:underline">Lihat
                    Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/50 text-gray-400 text-xs uppercase font-semibold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5">Pengunjung</th>
                            <th class="px-6 py-3.5">Tujuan</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentTamu as $tamu)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $tamu->nama_lengkap }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $tamu->subBagian->nama_sub_bagian ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @switch($tamu->status_tindak_lanjut)
                                        @case('selesai')
                                            <span
                                                class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold tracking-wider uppercase">
                                                Selesai
                                            </span>
                                        @break

                                        @case('eskalasi')
                                            <span
                                                class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold tracking-wider uppercase">
                                                Eskalasi
                                            </span>
                                        @break

                                        @case('belum_eskalasi')
                                            <span
                                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold tracking-wider uppercase">
                                                Belum Eskalasi
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-[10px] font-bold tracking-wider uppercase">
                                                {{ str_replace('_', ' ', $tamu->status_tindak_lanjut ?? '-') }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="font-medium text-gray-800">{{ $tamu->created_at->format('h:i A') }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $tamu->created_at->isToday() ? 'Hari ini' : ($tamu->created_at->isYesterday() ? 'Kemarin' : $tamu->created_at->format('d M Y')) }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400">Belum ada aktivitas
                                    kunjungan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
     
    <script type="application/json" id="dashboard-chart-data">
        {!! json_encode([
            'subBagianLabels' => $distribusiSubBagian->pluck('nama_sub_bagian'),
            'subBagianData'   => $distribusiSubBagian->pluck('tamus_count'),
            'warnaSubBagian'  => $warnaSubBagian,
            'labelHari'       => $labelHari,
            'dataAktivitas'   => $dataAktivitas,
        ]) !!}
    </script>
@endsection

@push('scripts')
    <script>
        (function () {
            // Ambil semua data dashboard dari tag JSON di atas (bukan langsung inline di JS).
            const dashboardData = JSON.parse(document.getElementById('dashboard-chart-data').textContent);

            // Terapkan warna kartu distribusi sub bagian & legend dot dari data-attribute.
            document.querySelectorAll('.sub-bagian-card, .legend-dot').forEach(function (el) {
                const color = el.getAttribute('data-bg-color');
                if (color) {
                    el.style.backgroundColor = color;
                }
            });

            // 1. DOUGHNUT CHART
            const ctxPie = document.getElementById('pieChart').getContext('2d');

            let subBagianLabels = dashboardData.subBagianLabels;
            let subBagianData = dashboardData.subBagianData;
            let subBagianColors = dashboardData.warnaSubBagian.slice(0, Math.max(subBagianLabels.length, 1));

            // Jika belum ada data kunjungan sama sekali (semua bernilai 0),
            // Chart.js tidak akan menggambar irisan apa pun sehingga lingkaran
            // terlihat kosong/tidak ada. Tampilkan lingkaran abu-abu netral
            // sebagai placeholder agar bentuk doughnut tetap terlihat.
            const totalSubBagian = subBagianData.reduce((a, b) => a + b, 0);
            if (totalSubBagian === 0) {
                subBagianLabels = ['Belum ada data'];
                subBagianData = [1];
                subBagianColors = ['#e5e7eb'];
            }

            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: subBagianLabels,
                    datasets: [{
                        data: subBagianData,
                        backgroundColor: subBagianColors,
                        borderWidth: 0,
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });

            // 2. LINE CHART ACTIVITY (Senin - Jumat, mengikuti filter minggu)
            const ctxLine = document.getElementById('activityChart').getContext('2d');
            const activityChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: dashboardData.labelHari,
                    datasets: [{
                        data: dashboardData.dataAktivitas,
                        borderColor: '#173860',
                        borderWidth: 1.5,
                        pointBackgroundColor: '#173860',
                        pointRadius: 2,
                        tension: 0.3,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                color: '#f3f4f6'
                            }
                        }
                    }
                }
            });

            // 3. FILTER MINGGU -> reload halaman dengan query string ?minggu=...
            document.getElementById('filterMinggu').addEventListener('change', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('minggu', this.value);
                window.location.href = url.toString();
            });
        })();
    </script>
@endpush