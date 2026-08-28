@extends('super-admin.layouts.app')

@section('title', 'Pengaturan Display Online - Buku Tamu Digital')

@section('content')
    <div class="space-y-6">

        {{-- Breadcrumb & Title --}}
        <div>
            <div class="text-sm text-gray-500 mb-1">
                <a href="{{ route('super.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700 font-medium">Display Online</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Pengaturan Display Online</h1>
            <p class="text-sm text-gray-500 mt-1">
                Atur satu atau lebih video (YouTube atau link video lain) yang otomatis tampil bergantian di Layar
                Monitor TV pada slide "Jadwal Dinas" ketika tidak ada jadwal dinas untuk hari ini.
            </p>
        </div>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- FORM PENGATURAN --}}
            <div class="lg:col-span-7 bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Daftar Link Video Display</h2>
                <p class="text-sm text-gray-500 mb-5">
                    Tambahkan satu atau lebih link video YouTube (atau link embed video lainnya). Video akan
                    diputar bergantian di TV Display <strong>sesuai urutan pada daftar di bawah ini</strong>
                    (dari atas ke bawah). Gunakan tombol <i class="fa-solid fa-arrow-up"></i> /
                    <i class="fa-solid fa-arrow-down"></i> untuk mengubah urutan. Kosongkan semua &amp; simpan untuk
                    menonaktifkan tampilan video.
                </p>

                <form action="{{ route('super.pengaturan.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div id="linkVideoList" class="space-y-3">
                        @php $oldLinks = old('link_video', $linkVideos ?? []); @endphp
                        @forelse ($oldLinks as $i => $link)
                            <div class="link-video-row flex items-start gap-2" data-row>
                                <span
                                    class="video-order-badge mt-3 shrink-0 w-7 h-7 flex items-center justify-center rounded-full bg-[#173860]/10 text-[#173860] text-xs font-black">{{ $i + 1 }}</span>
                                <div class="flex-1">
                                    <input type="url" name="link_video[]" value="{{ $link }}"
                                        placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx"
                                        class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] border-gray-300">
                                </div>
                                <div class="flex flex-col gap-1 mt-1 shrink-0">
                                    <button type="button" data-move="up"
                                        class="w-8 h-8 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-[#173860] flex items-center justify-center text-xs"
                                        title="Naikkan urutan">
                                        <i class="fa-solid fa-arrow-up"></i>
                                    </button>
                                    <button type="button" data-move="down"
                                        class="w-8 h-8 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-[#173860] flex items-center justify-center text-xs"
                                        title="Turunkan urutan">
                                        <i class="fa-solid fa-arrow-down"></i>
                                    </button>
                                </div>
                                <button type="button" data-remove
                                    class="mt-1 w-8 h-8 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 flex items-center justify-center shrink-0"
                                    title="Hapus video ini">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @empty
                            {{-- Baris kosong pertama, agar form tidak kosong melompong saat belum ada data --}}
                            <div class="link-video-row flex items-start gap-2" data-row>
                                <span
                                    class="video-order-badge mt-3 shrink-0 w-7 h-7 flex items-center justify-center rounded-full bg-[#173860]/10 text-[#173860] text-xs font-black">1</span>
                                <div class="flex-1">
                                    <input type="url" name="link_video[]" value=""
                                        placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx"
                                        class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] border-gray-300">
                                </div>
                                <div class="flex flex-col gap-1 mt-1 shrink-0">
                                    <button type="button" data-move="up"
                                        class="w-8 h-8 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-[#173860] flex items-center justify-center text-xs"
                                        title="Naikkan urutan">
                                        <i class="fa-solid fa-arrow-up"></i>
                                    </button>
                                    <button type="button" data-move="down"
                                        class="w-8 h-8 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-[#173860] flex items-center justify-center text-xs"
                                        title="Turunkan urutan">
                                        <i class="fa-solid fa-arrow-down"></i>
                                    </button>
                                </div>
                                <button type="button" data-remove
                                    class="mt-1 w-8 h-8 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 flex items-center justify-center shrink-0"
                                    title="Hapus video ini">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @endforelse
                    </div>

                    @error('link_video')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @error('link_video.*')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    <button type="button" id="btnTambahVideo"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border-2 border-dashed border-gray-300 text-gray-600 hover:border-[#173860] hover:text-[#173860] font-semibold text-sm transition">
                        <i class="fa-solid fa-plus"></i> Tambah Video
                    </button>

                    <p class="text-xs text-gray-500 !mt-4">
                        Mendukung link biasa (<span class="font-mono">youtube.com/watch?v=...</span>,
                        <span class="font-mono">youtu.be/...</span>) maupun link embed
                        (<span class="font-mono">youtube.com/embed/...</span>). Link akan otomatis dikonversi
                        menjadi player tersemat. Jika hanya 1 video, video akan diputar berulang (loop). Jika lebih
                        dari 1 video, TV Display akan berpindah otomatis ke video berikutnya sesuai urutan di atas.
                    </p>

                    <div class="pt-2 flex justify-end gap-3">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold transition">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>

            {{-- PREVIEW --}}
            <div class="lg:col-span-5 bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Preview</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Tampilan ini akan muncul di TV Display saat tidak ada jadwal dinas hari ini.
                </p>

                @if (!empty($linkVideoEmbeds))
                    @if (count($linkVideoEmbeds) > 1)
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach ($linkVideoEmbeds as $i => $embed)
                                <button type="button" data-preview-tab="{{ $i }}"
                                    class="preview-tab-btn px-3 py-1.5 rounded-lg text-xs font-bold border transition {{ $i === 0 ? 'bg-[#173860] text-white border-[#173860]' : 'bg-white text-gray-600 border-gray-300 hover:border-[#173860]' }}">
                                    Video {{ $i + 1 }}
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="aspect-video w-full bg-slate-100 rounded-xl overflow-hidden border border-slate-200 flex items-center justify-center relative">
                        @foreach ($linkVideoEmbeds as $i => $embed)
                            <iframe data-preview-frame="{{ $i }}" src="{{ $embed }}"
                                class="w-full h-full absolute inset-0 {{ $i === 0 ? '' : 'hidden' }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        @endforeach
                    </div>
                @else
                    <div
                        class="aspect-video w-full bg-slate-100 rounded-xl overflow-hidden border border-slate-200 flex items-center justify-center">
                        <div class="text-center text-slate-400 px-6">
                            <i class="fa-solid fa-video-slash text-3xl mb-2"></i>
                            <p class="text-sm font-semibold">Belum ada link video diatur</p>
                        </div>
                    </div>
                @endif

                <a href="{{ route('display.tv') }}" target="_blank"
                    class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#173860] hover:underline">
                    <i class="fa-solid fa-tv"></i> Buka Halaman TV Display
                </a>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        (function () {
            const list = document.getElementById('linkVideoList');
            const btnTambah = document.getElementById('btnTambahVideo');

            function renumberRows() {
                list.querySelectorAll('[data-row]').forEach((row, index) => {
                    const badge = row.querySelector('.video-order-badge');
                    if (badge) badge.textContent = index + 1;
                });
            }

            function buatRowBaru() {
                const row = document.createElement('div');
                row.className = 'link-video-row flex items-start gap-2';
                row.setAttribute('data-row', '');
                row.innerHTML = `
                    <span class="video-order-badge mt-3 shrink-0 w-7 h-7 flex items-center justify-center rounded-full bg-[#173860]/10 text-[#173860] text-xs font-black"></span>
                    <div class="flex-1">
                        <input type="url" name="link_video[]" value=""
                            placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx"
                            class="w-full rounded-xl border px-4 py-3 outline-none transition focus:ring-2 focus:ring-[#173860] focus:border-[#173860] border-gray-300">
                    </div>
                    <div class="flex flex-col gap-1 mt-1 shrink-0">
                        <button type="button" data-move="up" class="w-8 h-8 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-[#173860] flex items-center justify-center text-xs" title="Naikkan urutan">
                            <i class="fa-solid fa-arrow-up"></i>
                        </button>
                        <button type="button" data-move="down" class="w-8 h-8 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-[#173860] flex items-center justify-center text-xs" title="Turunkan urutan">
                            <i class="fa-solid fa-arrow-down"></i>
                        </button>
                    </div>
                    <button type="button" data-remove class="mt-1 w-8 h-8 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 flex items-center justify-center shrink-0" title="Hapus video ini">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                `;
                return row;
            }

            btnTambah.addEventListener('click', () => {
                list.appendChild(buatRowBaru());
                renumberRows();
            });

            list.addEventListener('click', (e) => {
                const row = e.target.closest('[data-row]');
                if (!row) return;

                if (e.target.closest('[data-remove]')) {
                    // Selalu sisakan minimal 1 baris agar form tidak kosong total
                    if (list.querySelectorAll('[data-row]').length > 1) {
                        row.remove();
                    } else {
                        row.querySelector('input').value = '';
                    }
                    renumberRows();
                    return;
                }

                const moveBtn = e.target.closest('[data-move]');
                if (moveBtn) {
                    const dir = moveBtn.getAttribute('data-move');
                    if (dir === 'up' && row.previousElementSibling) {
                        list.insertBefore(row, row.previousElementSibling);
                    } else if (dir === 'down' && row.nextElementSibling) {
                        list.insertBefore(row.nextElementSibling, row);
                    }
                    renumberRows();
                }
            });

            renumberRows();

            // Tab preview multi-video
            document.querySelectorAll('[data-preview-tab]').forEach((tab) => {
                tab.addEventListener('click', () => {
                    const idx = tab.getAttribute('data-preview-tab');

                    document.querySelectorAll('.preview-tab-btn').forEach((t) => {
                        t.classList.remove('bg-[#173860]', 'text-white', 'border-[#173860]');
                        t.classList.add('bg-white', 'text-gray-600', 'border-gray-300');
                    });
                    tab.classList.add('bg-[#173860]', 'text-white', 'border-[#173860]');
                    tab.classList.remove('bg-white', 'text-gray-600', 'border-gray-300');

                    document.querySelectorAll('[data-preview-frame]').forEach((frame) => {
                        frame.classList.toggle('hidden', frame.getAttribute('data-preview-frame') !== idx);
                    });
                });
            });
        })();
    </script>
@endsection