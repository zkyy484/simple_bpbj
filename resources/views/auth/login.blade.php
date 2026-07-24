<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Buku Tamu Digital Bagian PBJ</title>

    <!-- Google Fonts untuk Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#cddcfd] min-h-screen flex items-center justify-center p-4 font-['Poppins'] antialiased">

    <div class="w-full max-w-[440px] bg-white rounded-[24px] shadow-sm p-8 md:p-10 text-center">

        <!-- LOGO & HEADER -->
        <div class="mb-7">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo PBJ"
                 class="w-[85px] h-auto mx-auto mb-4 object-contain">

            <h1 class="text-[22px] font-bold text-black tracking-tight leading-tight">
                Masuk ke sistem
            </h1>

            <p class="text-gray-700 text-[14px] mt-1 font-normal">
                Buku Tamu Digital - Bagian PBJ
            </p>
        </div>

        <!-- SESSION STATUS (SUKSES) -->
        @if(session('status'))
            <div class="mb-5 rounded-xl bg-green-100 border border-green-300 text-green-700 px-4 py-3 text-sm text-left">
                {{ session('status') }}
            </div>
        @endif

        <!-- ERROR HANDLING (BACKEND VALIDATION) -->
        @if($errors->any())
            <div class="mb-5 rounded-xl bg-red-100 border border-red-300 text-red-700 px-4 py-3 text-sm text-left">
                <ul class="list-disc ml-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM LOGIN -->
        <form method="POST" action="{{ route('login') }}" class="text-left">
            @csrf

            <!-- USERNAME / EMAIL -->
            <div class="mb-4">
                <label for="login" class="block mb-2 text-[13.5px] font-medium text-gray-800">
                    Username/email
                </label>

                <input type="text"
                       id="login"
                       name="login"
                       value="{{ old('login') }}"
                       required
                       autofocus
                       autocomplete="off"
                       class="w-full rounded-[10px] bg-[#e5e5e5] px-4 py-3.5 text-sm text-gray-800 border-2 border-transparent focus:outline-none focus:bg-[#f5f5f5] focus:border-[#173860] transition-all">
            </div>

            <!-- PASSWORD -->
            <div class="mb-3">
                <label for="password" class="block mb-2 text-[13.5px] font-medium text-gray-800">
                    Password
                </label>

                <input type="password"
                       id="password"
                       name="password"
                       required
                       class="w-full rounded-[10px] bg-[#e5e5e5] px-4 py-3.5 text-sm text-gray-800 border-2 border-transparent focus:outline-none focus:bg-[#f5f5f5] focus:border-[#173860] transition-all">
            </div>

            <!-- REMEMBER ME & LUPA PASSWORD LINK -->
            <div class="flex items-center justify-between mb-6">
                <!-- Checkbox Remember Me -->
                <label for="remember" class="inline-flex items-center cursor-pointer select-none">
                    <input type="checkbox"
                           id="remember"
                           name="remember"
                           class="w-4 h-4 rounded border-gray-300 text-[#173860] focus:ring-[#173860] cursor-pointer">
                    <span class="ml-2 text-xs text-gray-700 font-medium">Remember Me</span>
                </label>

                <!-- Link Lupa Password -->
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-[#2f53d7] underline hover:text-[#1a38a7] transition-colors">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- BUTTON LOGIN -->
            <button type="submit"
                    class="w-full bg-[#173860] hover:bg-[#0f2744] text-white font-semibold py-3.5 rounded-[10px] text-base transition-all active:scale-[0.99] shadow-sm">
                Masuk
            </button>
        </form>

    </div>

</body>
</html>