<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Buku Tamu Digital - Kota Denpasar')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script>

        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-blue-100 min-h-screen flex flex-col font-sans antialiased">

    @include('tamu.layouts.header')

    <main class="container mx-auto mt-10 px-4 mb-12">
        @yield('content')
    </main>

    @include('tamu.layouts.footer')

    @stack('scripts')
</body>
</html>
