{{-- resources/views/super-admin/survei/detail-content.blade.php --}}
<div class="space-y-6">
    {{-- Info identitas responden --}}
    <div class="bg-gray-50 rounded-xl p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <p class="text-[11px] font-bold tracking-wide text-gray-400 mb-0.5">NAMA</p>
            <p class="text-sm font-semibold text-gray-900">{{ $respon->nama_lengkap }}</p>
        </div>
        <div>
            <p class="text-[11px] font-bold tracking-wide text-gray-400 mb-0.5">EMAIL</p>
            <p class="text-sm text-gray-700">{{ $respon->email ?? '-' }}</p>
        </div>
        <div>
            <p class="text-[11px] font-bold tracking-wide text-gray-400 mb-0.5">INSTANSI</p>
            <p class="text-sm text-gray-700">{{ $respon->instansi ?? '-' }}</p>
        </div>
        <div>
            <p class="text-[11px] font-bold tracking-wide text-gray-400 mb-0.5">TANGGAL RESPON</p>
            <p class="text-sm text-gray-700">
                {{ \Carbon\Carbon::parse($respon->tanggal_respon)->translatedFormat('d M Y, H:i') }}
            </p>
        </div>
        <div>
            <p class="text-[11px] font-bold tracking-wide text-gray-400 mb-0.5">STATUS</p>
            @if ($respon->cek == 'approve')
                <span class="inline-flex bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                    APPROVE
                </span>
            @else
                <span class="inline-flex bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                    MENUNGGU
                </span>
            @endif
        </div>
        @if (isset($respon->is_anomali) && $respon->is_anomali)
            <div>
                <p class="text-[11px] font-bold tracking-wide text-gray-400 mb-0.5">DETEKSI</p>
                <span class="inline-flex bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                    ANOMALI
                </span>
            </div>
        @endif
    </div>

    {{-- Daftar jawaban --}}
    <div class="space-y-4">
        <h4 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2">Jawaban Survei</h4>

        @forelse ($jawabans as $i => $j)
            <div class="pb-4 border-b border-gray-100 last:border-b-0">
                <p class="text-sm font-semibold text-gray-900 mb-2">
                    {{ $i + 1 }}. {{ $j->pertanyaan->pertanyaan ?? '-' }}
                </p>

                @php $tipe = $j->pertanyaan->tipe_pertanyaan ?? null; @endphp

                @if (in_array($tipe, ['pilihan_ganda', 'rating']))
                    <span class="inline-flex bg-[#173860] text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                        {{ $j->opsi->opsi ?? '-' }}
                    </span>
                @else
                    <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-4 py-3 whitespace-pre-line">
                        {{ $j->jawaban_teks ?? '-' }}
                    </p>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-6">Belum ada jawaban untuk respon ini.</p>
        @endforelse
    </div>
</div>