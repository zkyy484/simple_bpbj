@php
    // Guard: partial ini dipakai lewat AJAX (id_respon) dari beberapa halaman
    // (super-admin/survei/data, super-admin/laporan/survei, admin/survei).
    // Default aman ditambahkan agar tidak fatal error jika suatu saat ter-render
    // tanpa data (mis. salah include tanpa mengirim variable).
    $jawabans = $jawabans ?? collect();
@endphp

@if (!isset($respon))
    <p class="text-red-600 text-sm text-center py-10">Data respon survei tidak ditemukan.</p>
@else
<!-- Informasi Profil Responden -->
<div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6 space-y-3">
    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
        <div>
            <h4 class="text-base font-bold text-gray-900">{{ $respon->nama_lengkap }}</h4>
            <p class="text-xs text-gray-500">{{ $respon->email ?? 'Tidak ada email' }}</p>
        </div>
        <div class="flex gap-2">
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                {{ $respon->cek === 'approve' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-yellow-100 text-yellow-700 border border-yellow-200' }}">
                {{ $respon->cek }}
            </span>
        </div>
    </div>

    <!-- Grid Informasi Tambahan & Rata-Rata Rating -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs text-gray-600">
        <div class="bg-white p-2.5 rounded-lg border border-gray-100">
            <span class="block text-gray-400 font-medium mb-0.5">Instansi</span>
            <span class="font-semibold text-gray-800 truncate block">{{ $respon->instansi ?? '-' }}</span>
        </div>
        
        <div class="bg-white p-2.5 rounded-lg border border-gray-100">
            <span class="block text-gray-400 font-medium mb-0.5">Tanggal Respon</span>
            <span class="font-semibold text-gray-800 block">
                {{ \Carbon\Carbon::parse($respon->tanggal_respon)->translatedFormat('d M Y, H:i') }}
            </span>
        </div>
        
        <div class="bg-white p-2.5 rounded-lg border border-gray-100">
            <span class="block text-gray-400 font-medium mb-0.5">Durasi Pengisian</span>
            <span class="font-semibold text-gray-800 block">
                @if($respon->durasi_pengisian)
                    {{ floor($respon->durasi_pengisian / 60) > 0 ? floor($respon->durasi_pengisian / 60) . 'm ' : '' }}{{ $respon->durasi_pengisian % 60 }}s
                @else
                    -
                @endif
            </span>
        </div>

        {{-- CARD RATA-RATA RATING --}}
        <div class="bg-amber-50/60 p-2.5 rounded-lg border border-amber-200/60 flex flex-col justify-between">
            <span class="block text-amber-700 font-medium mb-0.5">Rata-rata Rating</span>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="font-bold text-amber-900 text-sm">
                    {{ $respon->rata_rating ? number_format($respon->rata_rating, 2) : '-' }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Pertanyaan & Jawaban -->
<div class="space-y-6">

    @php
        $pilihanGanda = $jawabans->filter(fn($j) => $j->pertanyaan->tipe_pertanyaan === 'pilihan_ganda');
        $ratingDanTextarea = $jawabans->filter(fn($j) => in_array($j->pertanyaan->tipe_pertanyaan, ['rating', 'textarea']));
    @endphp

    {{-- KELOMPOK 1: PILIHAN GANDA (DI ATAS) --}}
    @if ($pilihanGanda->isNotEmpty())
        <div class="space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg inline-block">
                Pertanyaan Pilihan Ganda
            </h4>

            @foreach ($pilihanGanda as $jawaban)
                @php $pertanyaan = $jawaban->pertanyaan; @endphp
                <div class="p-4 bg-white border border-gray-200 rounded-xl space-y-3">
                    <p class="text-sm font-semibold text-gray-900 leading-relaxed">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-xs font-bold mr-1">
                            {{ $pertanyaan->urutan }}
                        </span>
                        {{ $pertanyaan->pertanyaan }}
                    </p>

                    <div class="space-y-1.5 pt-1">
                        @foreach ($pertanyaan->opsi as $opsi)
                            @php $selected = $jawaban->id_opsi === $opsi->id_opsi; @endphp
                            <div class="px-3.5 py-2 rounded-lg border text-xs flex items-center justify-between transition-all
                                {{ $selected
                                    ? 'bg-blue-50 text-blue-900 border-blue-300 font-semibold'
                                    : 'bg-gray-50 text-gray-500 border-gray-200 opacity-60' }}">
                                <span>{{ $opsi->opsi }}</span>
                                @if($selected)
                                    <span class="bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">
                                        Dipilih
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- KELOMPOK 2: RATING & TEXTAREA (DI BAWAH) --}}
    @if ($ratingDanTextarea->isNotEmpty())
        <div class="space-y-3 pt-2">
            <h4 class="text-xs font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-3 py-1.5 rounded-lg inline-block">
                Rating & Masukan Responden
            </h4>

            @foreach ($ratingDanTextarea as $jawaban)
                @php $pertanyaan = $jawaban->pertanyaan; @endphp

                <div class="p-4 bg-white border border-gray-200 rounded-xl space-y-3">
                    <p class="text-sm font-semibold text-gray-900 leading-relaxed">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-50 text-amber-600 text-xs font-bold mr-1">
                            {{ $pertanyaan->urutan }}
                        </span>
                        {{ $pertanyaan->pertanyaan }}
                    </p>

                    {{-- TIPE RATING --}}
                    @if ($pertanyaan->tipe_pertanyaan === 'rating')
                        <div class="flex flex-wrap gap-2 pt-1">
                            @foreach ($pertanyaan->opsi->sortBy('nilai') as $opsi)
                                @php
                                    $selected = $jawaban->rating !== null && (int) $jawaban->rating === (int) $opsi->nilai;
                                @endphp
                                <div class="px-3 py-1.5 rounded-lg border text-xs font-medium flex items-center gap-1.5 transition-all
                                    {{ $selected
                                        ? 'bg-blue-600 text-white border-blue-600 font-semibold shadow-xs'
                                        : 'bg-gray-50 text-gray-600 border-gray-200 opacity-60' }}">
                                    @if($selected)
                                        <svg class="w-3.5 h-3.5 fill-current text-white" viewBox="0 0 20 20">
                                            <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                        </svg>
                                    @endif
                                    <span>{{ $opsi->opsi }}</span>
                                </div>
                            @endforeach
                        </div>

                    {{-- TIPE TEXTAREA --}}
                    @elseif ($pertanyaan->tipe_pertanyaan === 'textarea')
                        <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-800 leading-relaxed italic">
                            "{{ $jawaban->jawaban ?? 'Tidak ada jawaban tertulis.' }}"
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

</div>
@endif