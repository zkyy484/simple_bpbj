@extends('tamu.layouts.app')

@section('title', 'Form Kunjungan Tamu')

@push('styles')
<!-- Import Google Font: Poppins -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body,
    input,
    select,
    textarea,
    button {
        font-family: 'Poppins', sans-serif !important;
    }
</style>
@endpush

@section('content')
<main class="container mx-auto mt-6 mb-12 px-4 max-w-4xl">
    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-[#173860] to-[#080d1a] px-6 py-10 text-center text-white sm:px-10">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Form Kunjungan Tamu</h2>
            <p class="mt-2 text-sm text-gray-200 max-w-lg mx-auto font-light leading-relaxed">
                Lengkapi data di bawah ini sesuai identitas Anda. Data akan digunakan untuk keperluan administrasi
                kunjungan.
            </p>
        </div>

        <div class="p-6 sm:p-10">
            <!-- Alert Error Validation -->
            @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-md shadow-sm"
                role="alert">
                <p class="font-semibold text-sm mb-1">Mohon periksa kembali isian Anda:</p>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form Start -->
            <form id="formKunjungan" action="{{ route('tamu.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Input Hidden Utama untuk Kolom Foto -->
                <input type="hidden" name="foto" id="fotoInput">

                <!-- Form Inputs Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- NIK/NIP -->
                    <div>
                        <label for="nik_nip" class="block text-sm font-semibold text-gray-700 mb-1">NIK/NIP</label>
                        <input type="text" id="nik_nip" name="nik_nip" value="{{ old('nik_nip') }}"
                            placeholder="Masukkan NIK atau NIP"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500/20 focus:border-[#173860] outline-none">
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                            required placeholder="Masukkan nama lengkap Anda"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500/20 focus:border-[#173860] outline-none">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500/20 focus:border-[#173860] outline-none">
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="nomor_telepon" class="block text-sm font-semibold text-gray-700 mb-1">Nomor
                            Telepon</label>
                        <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                            placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500/20 focus:border-[#173860] outline-none">
                    </div>

                    <!-- Jenis Permohonan -->
                    <div>
                        <label for="id_jenis_permohonan" class="block text-sm font-semibold text-gray-700 mb-1">Jenis
                            Permohonan</label>
                        <select id="id_jenis_permohonan" name="id_jenis_permohonan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500/20 focus:border-[#173860] outline-none bg-white">
                            <option value="">-- Pilih Jenis Permohonan --</option>
                            @foreach ($jenisPermohonans as $jenis)
                            <option value="{{ $jenis->id_jenis_permohonan }}" @selected((int) old('id_jenis_permohonan')===$jenis->id_jenis_permohonan)>
                                {{ $jenis->nama_jenis_permohonan }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Perusahaan -->
                    <div>
                        <label for="nama_perusahaan" class="block text-sm font-semibold text-gray-700 mb-1">Nama
                            Perusahaan / Instansi</label>
                        <input type="text" id="nama_perusahaan" name="nama_perusahaan"
                            value="{{ old('nama_perusahaan') }}" placeholder="Nama instansi atau organisasi"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500/20 focus:border-[#173860] outline-none">
                    </div>

                    <!-- Sub Bagian -->
                    <div>
                        <label for="id_sub_bagian" class="block text-sm font-semibold text-gray-700 mb-1">Sub Bagian
                            Tujuan</label>
                        <select id="id_sub_bagian" name="id_sub_bagian"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500/20 focus:border-[#173860] outline-none bg-white">
                            <option value="">-- Pilih Sub Bagian --</option>
                            @foreach ($subBagians as $subBagian)
                            <option value="{{ $subBagian->id_sub_bagian }}" @selected((int) old('id_sub_bagian')===$subBagian->id_sub_bagian)>
                                {{ $subBagian->nama_sub_bagian }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tujuan -->
                    <div>
                        <label for="id_tujuan" class="block text-sm font-semibold text-gray-700 mb-1">Tujuan
                            Kunjungan</label>
                        <select id="id_tujuan" name="id_tujuan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500/20 focus:border-[#173860] outline-none bg-white">
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach ($tujuans as $tujuan)
                            <option value="{{ $tujuan->id_tujuan }}" @selected((int) old('id_tujuan')===$tujuan->id_tujuan)>
                                {{ $tujuan->nama_tujuan }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Permasalahan -->
                    <div class="md:col-span-2">
                        <label for="permasalahan" class="block text-sm font-semibold text-gray-700 mb-1">Permasalahan /
                            Maksud Kunjungan</label>
                        <textarea id="permasalahan" name="permasalahan" rows="3"
                            placeholder="Jelaskan secara singkat maksud atau permasalahan kunjungan Anda..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500/20 focus:border-[#173860] outline-none resize-none">{{ old('permasalahan') }}</textarea>
                    </div>

                    <!-- SECTION VERIFIKASI (PARAF ATAU FOTO KAMERA - OPSIONAL) -->
                    <div class="md:col-span-2 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <label class="block text-sm font-semibold text-gray-700">
                                Verifikasi Kehadiran <span class="text-red-500">*</span>
                                <span class="text-xs font-normal text-gray-500 ml-1">(Pilih salah satu: Paraf atau Foto)</span>
                            </label>
                            <!-- Indicator status kelengkapan -->
                            <div class="flex gap-2">
                                <span id="statusParaf" class="text-[11px] px-2 py-0.5 rounded bg-amber-50 text-amber-600 border border-amber-200">Paraf: Belum</span>
                                <span id="statusFoto" class="text-[11px] px-2 py-0.5 rounded bg-amber-50 text-amber-600 border border-amber-200">Foto: Belum</span>
                            </div>
                        </div>

                        <!-- Sub-Navigation Switcher Button -->
                        <div class="flex p-1 bg-gray-100 rounded-xl border border-gray-200">
                            <button type="button" id="tabBtnSignature"
                                class="flex-1 py-2 px-4 rounded-lg text-xs font-semibold transition-all flex items-center justify-center gap-2 bg-white text-[#173860] shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                <span>Opsi 1: Paraf / Tanda Tangan</span>
                            </button>
                            <button type="button" id="tabBtnCamera"
                                class="flex-1 py-2 px-4 rounded-lg text-xs font-semibold transition-all flex items-center justify-center gap-2 text-gray-500 hover:text-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Opsi 2: Ambil Foto Kamera</span>
                            </button>
                        </div>

                        <!-- Container Isi Switch Tab -->
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 bg-gray-50">

                            <!-- 1. OPSI TAB: PARAF / TANDA TANGAN -->
                            <div id="tabContentSignature" class="flex flex-col items-center">
                                <canvas id="signaturePad"
                                    class="w-full h-48 bg-white rounded-lg border border-gray-200 cursor-crosshair touch-none shadow-inner"></canvas>

                                <div class="w-full flex justify-between items-center mt-3">
                                    <span class="text-xs text-gray-500">Gunakan mouse atau sentuhan jari untuk memaraf.</span>
                                    <div class="flex gap-2">
                                        <button type="button" id="clearSignature"
                                            class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded text-xs font-medium transition">
                                            Hapus Paraf
                                        </button>
                                        <button type="button" id="switchToCamBtn"
                                            class="px-3 py-1 bg-[#173860] hover:bg-[#0f2646] text-white rounded text-xs font-medium transition">
                                            Atau Pakai Kamera &rarr;
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. OPSI TAB: FOTO KAMERA -->
                            <div id="tabContentCamera" class="hidden flex flex-col items-center">
                                <!-- Preview Area -->
                                <div class="relative w-full max-w-sm aspect-[4/3] bg-black/90 rounded-lg overflow-hidden flex items-center justify-center shadow-inner">
                                    <video id="cameraVideo" autoplay playsinline muted
                                        class="hidden w-full h-full object-cover"></video>
                                    <img id="fotoPreview" src="" alt="Pratinjau foto tamu"
                                        class="hidden absolute inset-0 w-full h-full object-cover">
                                    <p id="cameraPlaceholder"
                                        class="absolute inset-0 flex items-center justify-center text-gray-400 text-xs px-4 text-center">
                                        Kamera belum aktif. Klik "Aktifkan Kamera" di bawah.
                                    </p>
                                </div>

                                <canvas id="cameraCanvas" class="hidden"></canvas>

                                <!-- Kontrol Kamera -->
                                <div class="w-full flex flex-wrap justify-between items-center gap-2 mt-3">
                                    <button type="button" id="switchToSigBtn"
                                        class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded text-xs font-medium transition">
                                        &larr; Atau Pakai Paraf
                                    </button>

                                    <div class="flex gap-2">
                                        <button type="button" id="startCamera"
                                            class="px-3 py-1 bg-[#173860] hover:bg-[#0f2646] text-white rounded text-xs font-medium transition">
                                            Aktifkan Kamera
                                        </button>
                                        <button type="button" id="captureFoto" disabled
                                            class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded text-xs font-medium transition">
                                            Ambil Foto
                                        </button>
                                        <button type="button" id="retakeFoto"
                                            class="hidden px-3 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded text-xs font-medium transition">
                                            Ambil Ulang
                                        </button>
                                    </div>
                                </div>
                                <span id="cameraError" class="hidden text-xs text-red-500 mt-2 text-center"></span>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Action Buttons Section -->
                <div class="pt-6 border-t border-gray-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <button type="reset" id="resetBtn"
                        class="w-full sm:w-auto px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none transition">
                        Reset Form
                    </button>
                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-2.5 rounded-lg bg-[#173860] hover:bg-[#080d1a] text-white text-sm font-semibold shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        Kirim Data Kunjungan
                    </button>
                </div>
            </form>
            <!-- Form End -->
        </div>
    </div>
</main>
@endsection

@push('scripts')
<!-- CDN Signature Pad -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signaturePad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        const statusParaf = document.getElementById('statusParaf');
        const statusFoto = document.getElementById('statusFoto');
        const fotoInput = document.getElementById('fotoInput');
        let fotoKameraBase64 = ''; // Menyimpan foto kamera sementara

        const tabBtnSignature = document.getElementById('tabBtnSignature');
        const tabBtnCamera = document.getElementById('tabBtnCamera');
        const tabContentSignature = document.getElementById('tabContentSignature');
        const tabContentCamera = document.getElementById('tabContentCamera');
        const switchToCamBtn = document.getElementById('switchToCamBtn');
        const switchToSigBtn = document.getElementById('switchToSigBtn');

        // Update indikator status
        function updateStatusIndicators() {
            if (!signaturePad.isEmpty()) {
                statusParaf.textContent = 'Paraf: Sudah';
                statusParaf.className = 'text-[11px] px-2 py-0.5 rounded bg-emerald-50 text-emerald-600 border border-emerald-200 font-semibold';
            } else {
                statusParaf.textContent = 'Paraf: Belum';
                statusParaf.className = 'text-[11px] px-2 py-0.5 rounded bg-amber-50 text-amber-600 border border-amber-200';
            }

            if (fotoKameraBase64) {
                statusFoto.textContent = 'Foto: Sudah';
                statusFoto.className = 'text-[11px] px-2 py-0.5 rounded bg-emerald-50 text-emerald-600 border border-emerald-200 font-semibold';
            } else {
                statusFoto.textContent = 'Foto: Belum';
                statusFoto.className = 'text-[11px] px-2 py-0.5 rounded bg-amber-50 text-amber-600 border border-amber-200';
            }
        }

        // Fungsi Switcher Tab
        function showSignatureTab() {
            tabContentSignature.classList.remove('hidden');
            tabContentCamera.classList.add('hidden');
            
            tabBtnSignature.className = 'flex-1 py-2 px-4 rounded-lg text-xs font-semibold transition-all flex items-center justify-center gap-2 bg-white text-[#173860] shadow-sm';
            tabBtnCamera.className = 'flex-1 py-2 px-4 rounded-lg text-xs font-semibold transition-all flex items-center justify-center gap-2 text-gray-500 hover:text-gray-800';
        }

        function showCameraTab() {
            tabContentCamera.classList.remove('hidden');
            tabContentSignature.classList.add('hidden');

            tabBtnCamera.className = 'flex-1 py-2 px-4 rounded-lg text-xs font-semibold transition-all flex items-center justify-center gap-2 bg-white text-[#173860] shadow-sm';
            tabBtnSignature.className = 'flex-1 py-2 px-4 rounded-lg text-xs font-semibold transition-all flex items-center justify-center gap-2 text-gray-500 hover:text-gray-800';
        }

        if (tabBtnSignature) tabBtnSignature.addEventListener('click', showSignatureTab);
        if (tabBtnCamera) tabBtnCamera.addEventListener('click', showCameraTab);
        if (switchToCamBtn) switchToCamBtn.addEventListener('click', showCameraTab);
        if (switchToSigBtn) switchToSigBtn.addEventListener('click', showSignatureTab);

        // Adjust Canvas Resolution
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
            updateStatusIndicators();
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        signaturePad.addEventListener("endStroke", updateStatusIndicators);

        // Clear Button Paraf
        document.getElementById('clearSignature').addEventListener('click', function() {
            signaturePad.clear();
            updateStatusIndicators();
        });

        // Fitur Kamera
        const video = document.getElementById('cameraVideo');
        const cameraCanvas = document.getElementById('cameraCanvas');
        const fotoPreview = document.getElementById('fotoPreview');
        const cameraPlaceholder = document.getElementById('cameraPlaceholder');
        const cameraError = document.getElementById('cameraError');
        const startCameraBtn = document.getElementById('startCamera');
        const captureFotoBtn = document.getElementById('captureFoto');
        const retakeFotoBtn = document.getElementById('retakeFoto');

        let cameraStream = null;

        function showCameraError(message) {
            cameraError.textContent = message;
            cameraError.classList.remove('hidden');
        }

        function hideCameraError() {
            cameraError.classList.add('hidden');
        }

        async function startCamera() {
            hideCameraError();

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showCameraError('Perangkat/browser Anda tidak mendukung akses kamera.');
                return;
            }

            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user' },
                    audio: false
                });

                video.srcObject = cameraStream;
                video.classList.remove('hidden');
                cameraPlaceholder.classList.add('hidden');
                fotoPreview.classList.add('hidden');

                startCameraBtn.textContent = 'Kamera Aktif';
                startCameraBtn.disabled = true;
                startCameraBtn.classList.add('opacity-60', 'cursor-not-allowed');
                captureFotoBtn.disabled = false;
            } catch (err) {
                showCameraError('Tidak dapat mengakses kamera. Mohon izinkan akses kamera pada browser Anda.');
            }
        }

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
        }

        function captureFoto() {
            if (!cameraStream) return;

            const width = video.videoWidth || 640;
            const height = video.videoHeight || 480;
            cameraCanvas.width = width;
            cameraCanvas.height = height;

            const ctx = cameraCanvas.getContext('2d');
            ctx.drawImage(video, 0, 0, width, height);

            fotoKameraBase64 = cameraCanvas.toDataURL('image/jpeg', 0.85);

            fotoPreview.src = fotoKameraBase64;
            fotoPreview.classList.remove('hidden');
            video.classList.add('hidden');

            stopCamera();

            captureFotoBtn.disabled = true;
            captureFotoBtn.classList.add('hidden');
            retakeFotoBtn.classList.remove('hidden');
            startCameraBtn.classList.add('hidden');
            
            updateStatusIndicators();
        }

        function resetCamera() {
            stopCamera();
            fotoKameraBase64 = '';
            fotoPreview.src = '';
            fotoPreview.classList.add('hidden');
            video.classList.add('hidden');
            cameraPlaceholder.classList.remove('hidden');
            hideCameraError();

            startCameraBtn.disabled = false;
            startCameraBtn.textContent = 'Aktifkan Kamera';
            startCameraBtn.classList.remove('opacity-60', 'cursor-not-allowed', 'hidden');

            captureFotoBtn.disabled = true;
            captureFotoBtn.classList.remove('hidden');

            retakeFotoBtn.classList.add('hidden');
            updateStatusIndicators();
        }

        startCameraBtn.addEventListener('click', startCamera);
        captureFotoBtn.addEventListener('click', captureFoto);
        retakeFotoBtn.addEventListener('click', function() {
            resetCamera();
            startCamera();
        });

        // Reset Form
        document.getElementById('resetBtn').addEventListener('click', function() {
            signaturePad.clear();
            fotoInput.value = '';
            resetCamera();
            showSignatureTab();
            updateStatusIndicators();
        });

        window.addEventListener('beforeunload', stopCamera);

        // Handle Form Submit & Validation
        const form = document.getElementById('formKunjungan');
        form.addEventListener('submit', function(e) {
            const isSignatureFilled = !signaturePad.isEmpty();
            const isFotoFilled = Boolean(fotoKameraBase64);

            // Validasi: Harus terisi minimal salah satu
            if (!isSignatureFilled && !isFotoFilled) {
                e.preventDefault();
                alert('Silakan lengkapi salah satu Verifikasi Kehadiran (Paraf atau Foto Kamera)!');
                return false;
            }

            // SIMPAN HASIL KE DALAM FIELD FOTO (Hidden Input)
            if (isSignatureFilled) {
                // Jika user memaraf, simpan data Base64 paraf ke field 'foto'
                fotoInput.value = signaturePad.toDataURL('image/png');
            } else if (isFotoFilled) {
                // Jika user memakai foto kamera, simpan foto kamera ke field 'foto'
                fotoInput.value = fotoKameraBase64;
            }

            const submitBtn = form.querySelector('button[type="submit"]');

            if (submitBtn.disabled) {
                e.preventDefault();
                return false;
            }

            // Loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengirim Data...
            `;
        });
    });
</script>
@endpush