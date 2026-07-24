
@extends('tamu.layouts.app')

@section('title', 'Survei Kepuasan Pelayanan')

@section('content')
        <!-- Main Content Area -->
    <main class="container mx-auto mt-10 px-4">
        <!-- White Form Card -->
        <div class="bg-white rounded-lg shadow-xl p-8 md:p-12 mb-10">
            <!-- Form Title and Instruction -->
            <div class="text-center mb-10">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Form Kunjungan Tamu</h2>
                <p class="text-sm text-gray-600 max-w-2xl mx-auto">Lengkapi data di bawah ini sesuai identitas Anda. Data akan digunakan untuk keperluan administrasi kunjungan.</p>
            </div>

            <!-- Form Start -->
            <form action="{{ route('thanks.page') }}"  class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                <!-- NIK/NIP -->
                <div>
                    <label for="nik_nip" class="block text-sm font-medium text-gray-700">NIK/NIP</label>
                    <input type="text" id="nik_nip" name="nik_nip" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="tel" id="nomor_telepon" name="nomor_telepon" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Jenis Permohonan -->
                <div>
                    <label for="jenis_permohonan" class="block text-sm font-medium text-gray-700">Jenis Permohonan</label>
                    <select id="jenis_permohonan" name="jenis_permohonan" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Jenis Permohonan</option>
                        <!-- Tambahkan opsi lainnya di sini -->
                    </select>
                </div>

                <!-- Nama Perusahaan -->
                <div>
                    <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                    <input type="text" id="nama_perusahaan" name="nama_perusahaan" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Sub Bagian -->
                <div>
                    <label for="sub_bagian" class="block text-sm font-medium text-gray-700">Sub Bagian</label>
                    <select id="sub_bagian" name="sub_bagian" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Sub Bagian</option>
                        <!-- Tambahkan opsi lainnya di sini -->
                    </select>
                </div>

                <!-- Tujuan -->
                <div>
                    <label for="tujuan" class="block text-sm font-medium text-gray-700">Tujuan</label>
                    <select id="tujuan" name="tujuan" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Tujuan</option>
                        <!-- Tambahkan opsi lainnya di sini -->
                    </select>
                </div>

                <!-- Permasalahan -->
                <div class="md:col-span-1">
                    <label for="permasalahan" class="block text-sm font-medium text-gray-700">Permasalahan</label>
                    <textarea id="permasalahan" name="permasalahan" rows="5" class="mt-1 block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
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
                    <button href="{{ route('thanks.page') }}" class="bg-[#112D55] text-white px-8 py-2.5 rounded-md hover:bg-opacity-90 transition duration-150 text-sm font-semibold">
                        Kirim Data Kunjungan
                    </button>
                </div>
            </form>
            <!-- Form End -->
    </main>
    </div>
@endsection