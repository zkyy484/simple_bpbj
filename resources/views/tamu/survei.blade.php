
@extends('tamu.layouts.app')

@section('title', 'Survei Kepuasan Pelayanan')

@section('content')
    <div class="bg-white rounded-lg p-8">
        <!-- Main Content Area -->
      <main class="container mx-auto mt-10 px-4 mb-12">
        <div class="bg-white rounded-lg shadow-xl p-8 md:p-12 max-w-4xl mx-auto">
            
            <!-- Title & Subtitle -->
            <div class="text-center mb-10">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2 tracking-tight">Survei Kepuasan Pelayanan</h2>
                <p class="text-sm text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Bantu kami meningkatkan kualitas pelayanan dengan memberikan penilaian dan masukan Anda mengenai layanan Pengadaan Barang dan Jasa Kota Denpasar.
                </p>
            </div>

            <!-- Form Survei -->
           {{-- <form id="surveyForm" onsubmit="handleSurveySubmit(event)" class="space-y-8"> --}}
             <form id="surveyForm" href="{{ route('thankSurvei.page') }}" class="space-y-8">
             
                <!-- BAGIAN 1: DATA DIRI RESPONDEN --> 
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-base font-bold text-[#112D55] mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-[#112D55] text-xs">1</span>
                        Data Diri Responden
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_lengkap" required class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" id="email" required class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <!-- Instansi / Perusahaan -->
                        <div>
                            <label for="instansi" class="block text-sm font-semibold text-gray-700">Instansi / Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" id="instansi" required class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 2: PERTANYAAN SURVEI -->
                <div class="border-b border-gray-200 pb-8 space-y-6">
                    <h3 class="text-base font-bold text-[#112D55] mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-[#112D55] text-xs">2</span>
                        Penilaian Pelayanan
                    </h3>

                    <!-- Q1 -->
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-800 mb-3">
                            1. Bagaimana kemudahan akses informasi dan prosedur layanan di Bagian Pengadaan Barang dan Jasa? <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q1" value="Sangat Kurang" required class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Sangat Kurang</span>
                            </label>
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q1" value="Kurang" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Kurang</span>
                            </label>
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q1" value="Baik" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Baik</span>
                            </label>
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q1" value="Sangat Baik" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Sangat Baik</span>
                            </label>
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-800 mb-3">
                            2. Bagaimana keramahan, kesopanan, dan profesionalisme petugas dalam memberikan konsultasi/layanan? <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q2" value="Sangat Kurang" required class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Sangat Kurang</span>
                            </label>
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q2" value="Kurang" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Kurang</span>
                            </label>
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q2" value="Baik" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Baik</span>
                            </label>
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q2" value="Sangat Baik" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Sangat Baik</span>
                            </label>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-800 mb-3">
                            3. Bagaimana ketepatan waktu dan kejelasan solusi yang diberikan oleh tim? <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q3" value="Sangat Kurang" required class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Sangat Kurang</span>
                            </label>
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q3" value="Kurang" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Kurang</span>
                            </label>
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q3" value="Baik" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Baik</span>
                            </label>
                            <label class="flex items-center p-2.5 bg-white rounded border border-gray-200 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="q3" value="Sangat Baik" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 font-medium">Sangat Baik</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 3: SARAN & MASUKAN -->
                <div>
                    <label for="saran" class="block text-sm font-semibold text-gray-700 mb-1">Kritik, Saran, atau Masukan (Opsional)</label>
                    <textarea id="saran" rows="4" placeholder="Tuliskan masukan Anda untuk peningkatan layanan kami..." class="block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm font-normal"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="text-center pt-4">
                    <button type="submit" class="bg-[#112D55] text-white px-10 py-3 rounded-md hover:bg-opacity-90 transition duration-150 text-sm font-semibold tracking-wide shadow">
                        Kirim Survei Kepuasan
                    </button>
                </div>
            </form>
        </div>
    </main>
    </div>
@endsection