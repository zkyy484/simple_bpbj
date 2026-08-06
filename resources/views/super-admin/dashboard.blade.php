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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <h3 class="text-4xl font-bold text-gray-900">{{ $totalKunjungan }}</h3>
                <p class="text-xs font-medium mt-2 flex items-center {{ $persenBulanan >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                    @if ($persenBulanan >= 0)
                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L13 10.414V17a1 1 0 11-2 0v-6.586l-2.293 2.293a1 1 0 01-1.414-1.414l4-4A1 1 0 0112 7z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 13a1 1 0 01-.707-.293l-4-4a1 1 0 111.414-1.414L11 9.586V3a1 1 0 112 0v6.586l2.293-2.293a1 1 0 111.414 1.414l-4 4A1 1 0 0112 13z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    {{ $persenBulanan >= 0 ? '+' : '' }}{{ $persenBulanan }}% <span class="text-gray-400 font-normal ml-1">vs bulan lalu</span>
                </p>
            </div>
        </div>

        <!-- KPI 2: Kunjungan Hari Ini -->
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="flex justify-between items-start">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kunjungan Hari Ini</span>
                <span class="p-2 bg-blue-50 rounded-lg text-[#173860]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <h3 class="text-4xl font-bold text-gray-900">{{ $kunjunganHariIni }}</h3>
                <p class="text-xs font-medium mt-2 flex items-center {{ $persenHarian >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                    @if ($persenHarian >= 0)
                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L13 10.414V17a1 1 0 11-2 0v-6.586l-2.293 2.293a1 1 0 01-1.414-1.414l4-4A1 1 0 0112 7z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 13a1 1 0 01-.707-.293l-4-4a1 1 0 111.414-1.414L11 9.586V3a1 1 0 112 0v6.586l2.293-2.293a1 1 0 111.414 1.414l-4 4A1 1 0 0112 13z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    {{ $persenHarian >= 0 ? '+' : '' }}{{ $persenHarian }}% <span class="text-gray-400 font-normal ml-1">vs kemarin</span>
                </p>
            </div>
        </div>

        <!-- KPI 3: Total Survei Masuk -->
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="flex justify-between items-start">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Survei Masuk</span>
                <span class="p-2 bg-blue-50 rounded-lg text-[#173860]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
            <p class="text-xs text-gray-400">Belum ada data sub bagian.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @php
                    $warnaPalet = ['#173860', '#38bdf8', '#818cf8', '#f59e0b', '#10b981', '#ef4444', '#a855f7', '#0ea5e9'];
                @endphp
                @foreach ($distribusiSubBagian as $index => $sub)
                    <div class="rounded-xl p-4 bg-[{{ $warnaPalet[$index % count($warnaPalet)] }}]">
                        <p class="text-[11px] font-semibold text-white">{{ $sub->nama_sub_bagian }}</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ $sub->tamus_count }}</p>
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
            <div class="w-full max-w-[220px] my-auto">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="flex flex-wrap justify-center gap-x-4 gap-y-1.5 text-xs font-medium text-gray-600 mt-4">
                @foreach ($distribusiSubBagian as $index => $sub)
                    <span class="flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full inline-block mr-1.5 bg-[{{ $warnaPalet[$index % count($warnaPalet)] }}]"></span>
                        {{ $sub->nama_sub_bagian }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Line Chart Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm lg:col-span-7">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-900 text-base">Aktivitas Kunjungan</h3>
                <span class="text-xs bg-gray-100 border border-gray-200 rounded-lg px-2.5 py-1.5 text-gray-600">
                    {{ $aktivitasMingguan->first()['label'] }} - {{ $aktivitasMingguan->last()['label'] }}
                </span>
            </div>
            <div class="h-60">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

    </div>

    <!-- LOG AKTIVITAS TERBARU (TABLE) -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-base">Kunjungan Terbaru</h3>
            <a href="{{ route('tamu.index') }}" class="text-xs font-semibold text-[#173860] hover:underline">Lihat Semua</a>
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
                    @forelse ($kunjunganTerbaru as $tamu)
                        @php
                            $statusColor = match ($tamu->status_tindak_lanjut) {
                                'selesai' => 'bg-emerald-100 text-emerald-700',
                                'eskalasi' => 'bg-blue-100 text-blue-700',
                                default => 'bg-gray-200 text-gray-700',
                            };
                            $statusLabel = match ($tamu->status_tindak_lanjut) {
                                'selesai' => 'SELESAI',
                                'eskalasi' => 'ESKALASI',
                                default => 'BELUM ESKALASI',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $tamu->nama_lengkap }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $tamu->tujuan->nama_tujuan ?? $tamu->jenis_permohonan ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $tamu->subBagian->nama_sub_bagian ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 {{ $statusColor }} rounded-full text-[10px] font-bold tracking-wider">{{ $statusLabel }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-medium text-gray-800">{{ $tamu->created_at->format('h:i A') }}</p>
                                <p class="text-xs text-gray-400">{{ $tamu->created_at->isToday() ? 'Hari ini' : $tamu->created_at->translatedFormat('d M Y') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-400 text-sm">Belum ada data kunjungan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @php

        $warnaPaletChart = ['#173860', '#38bdf8', '#818cf8', '#f59e0b', '#10b981', '#ef4444', '#a855f7', '#0ea5e9'];
        $jumlahWarnaDipakai = max($distribusiSubBagian->count(), 1);
        $warnaChartDipakai = array_slice($warnaPaletChart, 0, $jumlahWarnaDipakai);

        $dashboardDataArray = [
            'subBagianLabels' => $distribusiSubBagian->pluck('nama_sub_bagian'),
            'subBagianTotal' => $distribusiSubBagian->pluck('tamus_count'),
            'subBagianWarna' => $warnaChartDipakai,
            'aktivitasLabel' => $aktivitasMingguan->pluck('label'),
            'aktivitasTotal' => $aktivitasMingguan->pluck('total'),
        ];

        $dashboardDataJson = json_encode($dashboardDataArray);
    @endphp

    <script id="dashboard-chart-data" type="application/json">{!! $dashboardDataJson !!}</script>
</div>
@endsection

@push('scripts')
<script>
    // Ambil data yang sudah disiapkan server dari tag JSON di atas.
    const dashboardData = JSON.parse(document.getElementById('dashboard-chart-data').textContent);

    // 1. DOUGHNUT CHART - Distribusi Sub Bagian
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: dashboardData.subBagianLabels,
            datasets: [{
                data: dashboardData.subBagianTotal,
                backgroundColor: dashboardData.subBagianWarna,
                borderWidth: 0,
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { display: false }
            },
            responsive: true,
            maintainAspectRatio: true,
        }
    });

    // 2. LINE CHART - Aktivitas Kunjungan 7 hari terakhir
    const ctxLine = document.getElementById('activityChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: dashboardData.aktivitasLabel,
            datasets: [{
                data: dashboardData.aktivitasTotal,
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
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: { size: 10 }
                    },
                    grid: { color: '#f3f4f6' }
                }
            }
        }
    });
</script>
@endpush