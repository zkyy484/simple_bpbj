@extends('tamu.layouts.app')

@section('title', 'Survei Kepuasan Pelayanan')

@push('styles')
    <!-- Import Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body, input, select, textarea, button {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
@endpush

@section('content')
    <main class="container mx-auto mt-8 mb-12 px-4 max-w-4xl font-['Poppins']">
        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            
            <!-- Header Section with Theme Gradient -->
            <div class="bg-gradient-to-r from-[#173860] to-[#080d1a] px-8 py-10 text-center text-white">
                <h2 class="text-3xl font-extrabold tracking-tight">Survei Kepuasan Pelayanan</h2>
                <p class="mt-2 text-sm text-gray-200 max-w-xl mx-auto leading-relaxed font-light">
                    Bantu kami meningkatkan kualitas pelayanan dengan memberikan penilaian dan masukan Anda mengenai layanan Pengadaan Barang dan Jasa Kota Denpasar.
                </p>
            </div>

            <!-- Form Start -->
            <form action="{{ route('thanksur.page') }}" method="POST" id="surveyForm" class="p-8 md:p-10 space-y-8">
                @csrf

                <!-- BAGIAN 1: DATA DIRI RESPONDEN --> 
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-base font-bold text-[#173860] mb-5 flex items-center gap-2.5">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-[#173860]/10 text-[#173860] text-xs font-bold">1</span>
                        Data Diri Responden
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" required 
                                placeholder="Masukkan nama"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition placeholder:text-gray-400">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required 
                                placeholder="nama@email.com"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition placeholder:text-gray-400">
                        </div>

                        <!-- Instansi / Perusahaan -->
                        <div>
                            <label for="instansi" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Instansi / Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="instansi" name="instansi" required 
                                placeholder="Nama instansi"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition placeholder:text-gray-400">
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 2: PERTANYAAN SURVEI -->
                <div class="border-b border-gray-200 pb-8 space-y-6">
                    <h3 class="text-base font-bold text-[#173860] mb-5 flex items-center gap-2.5">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-[#173860]/10 text-[#173860] text-xs font-bold">2</span>
                        Penilaian Pelayanan
                    </h3>

                    <!-- Q1 -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-800 mb-4 leading-relaxed">
                            1. Bagaimana kemudahan akses informasi dan prosedur layanan di Bagian Pengadaan Barang dan Jasa? <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            @foreach(['Sangat Kurang', 'Kurang', 'Baik', 'Sangat Baik'] as $option)
                                <label class="flex items-center p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-[#173860] hover:bg-blue-50/50 transition">
                                    <input type="radio" name="q1" value="{{ $option }}" required class="w-4 h-4 text-[#173860] focus:ring-[#173860]">
                                    <span class="ml-2.5 text-gray-700 font-medium">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-800 mb-4 leading-relaxed">
                            2. Bagaimana keramahan, kesopanan, dan profesionalisme petugas dalam memberikan konsultasi/layanan? <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            @foreach(['Sangat Kurang', 'Kurang', 'Baik', 'Sangat Baik'] as $option)
                                <label class="flex items-center p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-[#173860] hover:bg-blue-50/50 transition">
                                    <input type="radio" name="q2" value="{{ $option }}" required class="w-4 h-4 text-[#173860] focus:ring-[#173860]">
                                    <span class="ml-2.5 text-gray-700 font-medium">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-800 mb-4 leading-relaxed">
                            3. Bagaimana ketepatan waktu dan kejelasan solusi yang diberikan oleh tim? <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            @foreach(['Sangat Kurang', 'Kurang', 'Baik', 'Sangat Baik'] as $option)
                                <label class="flex items-center p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-[#173860] hover:bg-blue-50/50 transition">
                                    <input type="radio" name="q3" value="{{ $option }}" required class="w-4 h-4 text-[#173860] focus:ring-[#173860]">
                                    <span class="ml-2.5 text-gray-700 font-medium">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 3: SARAN & MASUKAN -->
                <div>
                    <label for="saran" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Kritik, Saran, atau Masukan (Opsional)
                    </label>
                    <textarea id="saran" name="saran" rows="4" 
                        placeholder="Tuliskan masukan Anda untuk peningkatan kualitas layanan kami..." 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition resize-none placeholder:text-gray-400"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3 rounded-xl bg-[#173860] hover:bg-[#080d1a] text-white text-sm font-semibold shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        Kirim Survei Kepuasan
                    </button>
                </div>
            </form>
            <!-- Form End -->

        </div>
    </main>
@endsection