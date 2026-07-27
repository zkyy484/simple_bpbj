@extends('tamu.layouts.app')

@section('title', 'Form Kunjungan Tamu')

@section('content')
    <!-- White Form Card -->
    <div class="bg-white rounded-lg shadow-xl p-8 md:p-12 mb-10">
            <!-- Form Title and Instruction -->
            <div class="text-center mb-10">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Form Kunjungan Tamu</h2>
                <p class="text-sm text-gray-600 max-w-2xl mx-auto">Lengkapi data di bawah ini sesuai identitas Anda. Data akan digunakan untuk keperluan administrasi kunjungan.</p>

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
    <main class="container mx-auto mt-8 mb-12 px-4 max-w-5xl font-['Poppins']">
        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            
            <!-- Header Section with Theme Gradient -->
            <div class="bg-gradient-to-r from-[#173860] to-[#080d1a] px-8 py-10 text-center text-white">
                <h2 class="text-3xl font-extrabold tracking-tight">Form Kunjungan Tamu</h2>
                <p class="mt-2 text-sm text-gray-200 max-w-xl mx-auto leading-relaxed font-light">
                    Lengkapi data di bawah ini sesuai identitas Anda. Data akan digunakan untuk keperluan administrasi kunjungan.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md p-4">
                    <p class="font-semibold mb-1">Mohon periksa kembali isian Anda:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Start -->
            <form action="{{ route('tamu.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                @csrf

                <!-- NIK/NIP -->
                <div>
                    <label for="nik_nip" class="block text-sm font-medium text-gray-700">NIK/NIP</label>
                    <input type="text" id="nik_nip" name="nik_nip" value="{{ old('nik_nip') }}" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Jenis Permohonan -->
                <div>
                    <label for="jenis_permohonan" class="block text-sm font-medium text-gray-700">Jenis Permohonan</label>
                    <select id="jenis_permohonan" name="jenis_permohonan" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Jenis Permohonan</option>
                        @foreach (['Konsultasi', 'Pengaduan', 'Permohonan Informasi', 'Lainnya'] as $jenis)
                            <option value="{{ $jenis }}" @selected(old('jenis_permohonan') === $jenis)>{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Perusahaan -->
                <div>
                    <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                    <input type="text" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Sub Bagian -->
                <div>
                    <label for="id_sub_bagian" class="block text-sm font-medium text-gray-700">Sub Bagian</label>
                    <select id="id_sub_bagian" name="id_sub_bagian" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Sub Bagian</option>
                        @foreach ($subBagians as $subBagian)
                            <option value="{{ $subBagian->id_sub_bagian }}" @selected((int) old('id_sub_bagian') === $subBagian->id_sub_bagian)>
                                {{ $subBagian->nama_sub_bagian }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tujuan -->
                <div>
                    <label for="id_tujuan" class="block text-sm font-medium text-gray-700">Tujuan</label>
                    <select id="id_tujuan" name="id_tujuan" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Tujuan</option>
                        @foreach ($tujuans as $tujuan)
                            <option value="{{ $tujuan->id_tujuan }}" @selected((int) old('id_tujuan') === $tujuan->id_tujuan)>
                                {{ $tujuan->nama_tujuan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Permasalahan -->
                <div class="md:col-span-1">
                    <label for="permasalahan" class="block text-sm font-medium text-gray-700">Permasalahan</label>
                    <textarea id="permasalahan" name="permasalahan" rows="5" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">{{ old('permasalahan') }}</textarea>
            <form action="{{ route('thanks.page') }}" method="POST" class="p-8 md:p-10 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- NIK/NIP -->
                    <div>
                        <label for="nik_nip" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            NIK / NIP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nik_nip" name="nik_nip" required
                            placeholder="Masukkan NIK atau NIP Anda"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition placeholder:text-gray-400">
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required
                            placeholder="Masukkan nama lengkap"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition placeholder:text-gray-400">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" required
                            placeholder="nama@email.com"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition placeholder:text-gray-400">
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="nomor_telepon" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="nomor_telepon" name="nomor_telepon" required
                            placeholder="08123456789"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition placeholder:text-gray-400">
                    </div>

                    <!-- Jenis Permohonan -->
                    <div>
                        <label for="jenis_permohonan" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Jenis Permohonan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="jenis_permohonan" name="jenis_permohonan" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition appearance-none">
                                <option value="" disabled selected>Pilih Jenis Permohonan</option>
                                <option value="Informasi">Informasi / Konsultasi</option>
                                <option value="Pengaduan">Pengaduan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Nama Perusahaan -->
                    <div>
                        <label for="nama_perusahaan" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Perusahaan / Instansi
                        </label>
                        <input type="text" id="nama_perusahaan" name="nama_perusahaan"
                            placeholder="Contoh: PT. Sumber Makmur (Opsional)"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition placeholder:text-gray-400">
                    </div>

                    <!-- Sub Bagian -->
                    <div>
                        <label for="sub_bagian" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Sub Bagian Dituju <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="sub_bagian" name="sub_bagian" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition appearance-none">
                                <option value="" disabled selected>Pilih Sub Bagian</option>
                                <option value="Umum">Sub Bagian Umum</option>
                                <option value="Keuangan">Sub Bagian Keuangan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tujuan Kunjungan -->
                    <div>
                        <label for="tujuan" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Tujuan Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="tujuan" name="tujuan" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition appearance-none">
                                <option value="" disabled selected>Pilih Tujuan</option>
                                <option value="Koordinasi">Koordinasi / Rapat</option>
                                <option value="Penyerahan Berkas">Penyerahan Berkas</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <!-- Permasalahan -->
                    <div class="flex flex-col">
                        <label for="permasalahan" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Detail Permasalahan / Keperluan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="permasalahan" name="permasalahan" rows="5" required
                            placeholder="Jelaskan secara singkat maksud dan tujuan atau permasalahan kunjungan Anda..."
                            class="w-full h-full min-h-[140px] px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-900 focus:bg-white focus:ring-2 focus:ring-[#173860] focus:border-[#173860] outline-none transition resize-none placeholder:text-gray-400"></textarea>
                    </div>

                    <!-- Paraf Area -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-sm font-semibold text-gray-700">
                                Paraf / Tanda Tangan Digital <span class="text-red-500">*</span>
                            </label>
                            <button type="button" id="clear-signature" class="text-xs text-red-600 hover:underline font-medium">
                                Bersihkan
                            </button>
                        </div>
                        <div class="relative w-full h-[140px] bg-gray-50 border border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden hover:border-[#173860] transition">
                            <canvas id="signature-pad" class="w-full h-full cursor-crosshair"></canvas>
                            <span id="signature-placeholder" class="absolute pointer-events-none text-xs text-gray-400 font-light">
                                Silakan tanda tangan di dalam area ini
                            </span>
                        </div>
                        <input type="hidden" name="paraf_data" id="paraf_data">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2 text-center mt-10">
                    <button type="submit" class="bg-[#112D55] text-white px-8 py-2.5 rounded-md hover:bg-opacity-90 transition duration-150 text-sm font-semibold">
                        Kirim Data Kunjungan
                    </button>
                </div>
        </form>
        <!-- Form End -->
    </div>
                <!-- Action Button -->
                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                    <button type="reset" 
                        class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                        Reset Form
                    </button>
                    <button type="submit" 
                        class="w-full sm:w-auto px-8 py-2.5 rounded-xl bg-[#173860] hover:bg-[#080d1a] text-white text-sm font-semibold shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        Kirim Data Kunjungan
                    </button>
                </div>

            </form>
            <!-- Form End -->

        </div>
    </main>
@endsection