@extends('tamu.layouts.app')

@section('title', 'Form Kunjungan Tamu')

@section('content')
    <!-- White Form Card -->
    <div class="bg-white rounded-lg shadow-xl p-8 md:p-12 mb-10">
            <!-- Form Title and Instruction -->
            <div class="text-center mb-10">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Form Kunjungan Tamu</h2>
                <p class="text-sm text-gray-600 max-w-2xl mx-auto">Lengkapi data di bawah ini sesuai identitas Anda. Data akan digunakan untuk keperluan administrasi kunjungan.</p>
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
                </div>

                <!-- Paraf (Placeholder for signature) -->
                <div>
                    <label for="paraf" class="block text-sm font-medium text-gray-700">Paraf</label>
                    <div id="paraf" class="mt-1 block w-full h-[155px] bg-gray-100 border border-gray-300 rounded-md shadow-sm text-center pt-16 text-gray-400 text-sm">
                        Kolom Paraf
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
@endsection