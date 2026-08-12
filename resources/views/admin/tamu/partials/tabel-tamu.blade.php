{{-- Partial: Daftar Tamu (di-refresh otomatis via AJAX oleh index.blade.php) --}}
            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">Daftar Tamu</h3>
                    <span class="text-xs bg-blue-50 text-[#173860] px-3 py-1 rounded-full font-semibold">
                        Total : {{ $tamus->total() ?? 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50/60 text-gray-400 text-[11px] uppercase font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5">NAMA</th>
                                <th class="px-6 py-3.5">FUNGSI</th>
                                <th class="px-6 py-3.5">TUJUAN</th>
                                <th class="px-6 py-3.5">PEGAWAI</th>
                                <th class="px-6 py-3.5 text-center">STATUS</th>
                                <th class="px-6 py-3.5 text-center w-20">CEK</th>
                                <th class="px-6 py-3.5 text-center w-36">APPROVAL</th>
                                <th class="px-6 py-3.5 text-center w-48">AKSI</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($tamus as $tamu)
                                <tr class="hover:bg-gray-50/50 transition align-top">
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $tamu->nama_lengkap }}
                                        <div class="text-xs font-normal text-gray-400">{{ $tamu->kode_tiket }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $tamu->subBagian->nama_sub_bagian ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $tamu->tujuan->nama_tujuan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $tamu->pegawai->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusColor = match ($tamu->status_tindak_lanjut) {
                                                'selesai' => 'bg-emerald-100 text-emerald-800',
                                                'eskalasi' => 'bg-blue-100 text-blue-800',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                            $statusLabel = match ($tamu->status_tindak_lanjut) {
                                                'selesai' => 'Selesai',
                                                'eskalasi' => 'Eskalasi',
                                                default => 'Belum Eskalasi',
                                            };
                                        @endphp
                                        <span class="inline-block px-3 py-1 text-[11px] font-bold rounded-full whitespace-nowrap {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" class="w-4 h-4 text-[#173860] rounded border-gray-300 focus:ring-[#173860]" disabled
                                            @checked($tamu->status !== 'menunggu')>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <!-- Tombol Pemicu Modal Approval -->
                                        <button type="button"
                                            @click="setApprove({ 
                                                id: {{ $tamu->id_tamu }}, 
                                                nama_lengkap: @js($tamu->nama_lengkap),
                                                approval: @js($tamu->approval)
                                            })"
                                            class="px-3 py-1.5 text-xs font-bold rounded-lg text-white transition w-28 shadow-sm
                                            {{ $tamu->approval === 'approve' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-amber-500 hover:bg-amber-600' }}">
                                            {{ $tamu->approval === 'approve' ? 'Approved' : 'Menunggu' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            <!-- Tombol Detail -->
                                            <button type="button"
                                                @click="setDetail({
                                                    id: {{ $tamu->id_tamu }},
                                                    kode_tiket: @js($tamu->kode_tiket),
                                                    nama_lengkap: @js($tamu->nama_lengkap),
                                                    email: @js($tamu->email ?? '-'),
                                                    no_telp: @js($tamu->nomor_telepon ?? '-'),
                                                    sub_bagian: @js($tamu->subBagian->nama_sub_bagian ?? '-'),
                                                    tujuan: @js($tamu->tujuan->nama_tujuan ?? '-'),
                                                    pegawai: @js($tamu->pegawai->name ?? '-'),
                                                    permasalahan: @js($tamu->permasalahan ?? '-'),
                                                    solusi: @js($tamu->solusi ?? ''),
                                                    status_tindak_lanjut: @js($tamu->status_tindak_lanjut),
                                                    status: @js($tamu->status),
                                                    approval: @js($tamu->approval)
                                                })"
                                                class="px-3 py-1.5 bg-[#173860] hover:bg-[#12294a] rounded-lg text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                <span>Detail</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada data tamu yang mengisi Buku Tamu Digital.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($tamus->hasPages())
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $tamus->firstItem() }} to {{ $tamus->lastItem() }} of {{ $tamus->total() }} entries
                        </p>
                        <div class="flex items-center gap-1.5">
                            @if ($tamus->onFirstPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $tamus->previousPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($tamus->getUrlRange(1, $tamus->lastPage()) as $page => $url)
                                @if ($page == $tamus->currentPage())
                                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#173860] text-white text-xs font-semibold">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-xs font-semibold">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($tamus->hasMorePages())
                                <a href="{{ $tamus->nextPageUrl() }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            @else
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Showing {{ $tamus->count() ? 1 : 0 }} to {{ $tamus->count() }} of {{ $tamus->total() }} entries
                        </p>
                    </div>
                @endif
            </div>