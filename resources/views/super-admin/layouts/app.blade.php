<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Buku Tamu Digital - Kota Denpasar')</title>

    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-['Poppins'] bg-[#cddcfd] text-gray-800 min-h-screen flex flex-col antialiased">

    <!-- HEADER INCLUDE -->
    @include('super-admin.layouts.header')

    <!-- MAIN WRAPPER (SIDEBAR + CONTENT) -->
    <div class="flex flex-1">
        <!-- SIDEBAR INCLUDE -->
        @include('super-admin.layouts.sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 p-6 md:p-8">
            @yield('content')
        </main>
    </div>

    <!-- FOOTER INCLUDE -->
    @include('super-admin.layouts.footer')

    @stack('scripts')

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl',
                    }
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#173860',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-5 py-2.5 font-semibold'
                    }
                });
            });
        </script>
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs/dist/cdn.min.js"></script>
    <!-- SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
