{{-- resources/views/survei/create.blade.php --}}
@extends('tamu.layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10 px-4" 
         x-data="{ 
            step: 1, 
            totalSteps: 3, 
            waktuMulai: Math.floor(Date.now() / 1000) 
         }">
        
        <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Survei Kepuasan Layanan</h1>
            <p class="text-sm text-gray-500 mb-6">Buku Tamu Digital - Kota Denpasar</p>

            {{-- Progress Indicator / Steps Bar --}}
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-[#173860]" x-text="'Langkah ' + step + ' dari ' + totalSteps"></span>
                    <span class="text-xs font-semibold text-gray-400" x-text="Math.round((step / totalSteps) * 100) + '% Selesai'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-[#173860] h-2 rounded-full transition-all duration-300"
                         :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
                </div>
            </div>

            {{-- Flash Alert Errors --}}
            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-700 p-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-700 p-4">
                    <p class="font-semibold mb-1">Mohon periksa kembali isian survei:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('survei.store') }}" method="POST">
                @csrf
                <input type="hidden" name="waktu_mulai" x-bind:value="waktuMulai">

                {{-- ================= SLIDE 1: Identitas & Pilihan Ganda ================= --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6">
                    <div class="pb-4 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Diri</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                                    class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860] @error('nama_lengkap') border-red-400 @else border-gray-300 @enderror">
                                @error('nama_lengkap')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860]">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Instansi</label>
                                    <input type="text" name="instansi" value="{{ old('instansi') }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860]">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pertanyaan Pilihan Ganda --}}
                    @php
                        $pilihanGanda = $pertanyaans->where('tipe_pertanyaan', 'pilihan_ganda');
                    @endphp

                    @if($pilihanGanda->count() > 0)
                        <div class="space-y-6 pt-2">
                            <h2 class="text-lg font-bold text-gray-800">Pertanyaan UMUM</h2>
                            @foreach ($pilihanGanda as $i => $p)
                                <div class="pb-4 border-b border-gray-100">
                                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                                        {{ $i + 1 }}. {{ $p->pertanyaan }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-y-2">
                                        @foreach ($p->opsi as $o)
                                            <label class="flex items-center gap-3 border border-gray-300 rounded-xl px-4 py-3 cursor-pointer hover:border-[#173860] has-[:checked]:bg-blue-50 has-[:checked]:border-[#173860] transition">
                                                <input type="radio" name="jawaban[{{ $p->id_pertanyaan }}]"
                                                    value="{{ $o->id_opsi }}"
                                                    {{ old("jawaban.{$p->id_pertanyaan}") == $o->id_opsi ? 'checked' : '' }}
                                                    class="w-4 h-4 accent-[#173860]">
                                                <span class="text-sm text-gray-800">{{ $o->opsi }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error("jawaban.{$p->id_pertanyaan}")
                                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ================= SLIDE 2: Rating ================= --}}
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6" style="display: none;">
                    <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Penilaian & Rating Layanan</h2>
                    
                    @php
                        $ratings = $pertanyaans->where('tipe_pertanyaan', 'rating');
                    @endphp

                    @foreach ($ratings as $i => $p)
                        <div class="pb-4 border-b border-gray-100">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                {{ $i + 1 }}. {{ $p->pertanyaan }} <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($p->opsi as $o)
                                    <label class="flex items-center gap-2 border border-gray-300 rounded-xl px-4 py-2.5 cursor-pointer hover:border-[#173860] has-[:checked]:bg-[#173860] has-[:checked]:text-white has-[:checked]:border-[#173860] transition">
                                        <input type="radio" name="jawaban[{{ $p->id_pertanyaan }}]"
                                            value="{{ $o->id_opsi }}"
                                            {{ old("jawaban.{$p->id_pertanyaan}") == $o->id_opsi ? 'checked' : '' }}
                                            class="hidden">
                                        <span class="text-sm font-medium">{{ $o->opsi }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error("jawaban.{$p->id_pertanyaan}")
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                {{-- ================= SLIDE 3: Textarea / Masukan ================= --}}
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6" style="display: none;">
                    <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Saran & Masukan</h2>

                    @php
                        $textareas = $pertanyaans->whereNotIn('tipe_pertanyaan', ['pilihan_ganda', 'rating']);
                    @endphp

                    @foreach ($textareas as $i => $p)
                        <div class="pb-4 border-b border-gray-100">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                {{ $i + 1 }}. {{ $p->pertanyaan }}
                            </label>
                            <textarea name="jawaban[{{ $p->id_pertanyaan }}]" rows="4" placeholder="Tulis jawaban Anda di sini..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860]">{{ old("jawaban.{$p->id_pertanyaan}") }}</textarea>
                            @error("jawaban.{$p->id_pertanyaan}")
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                {{-- Navigasi Tombol Slider --}}
                <div class="flex justify-between items-center pt-6 mt-6 border-t border-gray-100">
                    <button type="button" 
                            x-show="step > 1" 
                            @click="step--" 
                            class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition">
                        &larr; Kembali
                    </button>

                    <div x-show="step === 1"></div> {{-- Spacer jika tombol kembali tidak ada --}}

                    <button type="button" 
                            x-show="step < totalSteps" 
                            @click="step++" 
                            class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold text-sm transition">
                        Lanjut &rarr;
                    </button>

                    <button type="submit" 
                            x-show="step === totalSteps" 
                            class="px-6 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold text-sm transition">
                        Kirim Survei
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection