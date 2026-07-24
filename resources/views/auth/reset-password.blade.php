<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Buku Tamu Digital Bagian PBJ</title>

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
                Reset Password
            </h1>

            <p class="text-gray-700 text-[14px] mt-1 font-normal">
                Buku Tamu Digital - Bagian PBJ
            </p>
        </div>

        <!-- ERROR HANDLING (BACKEND VALIDATION) -->
        @if($errors->any())
            <div class="mb-5 rounded-xl bg-red-100 border border-red-300 text-red-700 px-4 py-3 text-xs text-left">
                <ul class="list-disc ml-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM RESET PASSWORD -->
        <form method="POST" action="{{ route('password.store') }}" class="text-left">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="mb-4 hidden">
                <label for="email" class="block mb-2 text-[13.5px] font-medium text-gray-800">
                    Email
                </label>

                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $request->email) }}"
                       required
                       autofocus
                       autocomplete="username"
                       placeholder="Masukkan email Anda"
                       class="w-full rounded-[10px] bg-[#e5e5e5] px-4 py-3.5 text-sm text-gray-800 border-2 border-transparent focus:outline-none focus:bg-[#f5f5f5] focus:border-[#173860] transition-all">
            </div>

            <!-- Password Baru -->
            <div class="mb-4">
                <label for="password" class="block mb-2 text-[13.5px] font-medium text-gray-800">
                    Password Baru
                </label>

                <input type="password"
                       id="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Masukkan password baru"
                       class="w-full rounded-[10px] bg-[#e5e5e5] px-4 py-3.5 text-sm text-gray-800 border-2 border-transparent focus:outline-none focus:bg-[#f5f5f5] focus:border-[#173860] transition-all">
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2 text-[13.5px] font-medium text-gray-800">
                    Konfirmasi Password
                </label>

                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Ulangi password baru"
                       class="w-full rounded-[10px] bg-[#e5e5e5] px-4 py-3.5 text-sm text-gray-800 border-2 border-transparent focus:outline-none focus:bg-[#f5f5f5] focus:border-[#173860] transition-all">
            </div>

            <!-- SUBMIT BUTTON -->
            <button type="submit"
                    class="w-full bg-[#173860] hover:bg-[#0f2744] text-white font-semibold py-3.5 rounded-[10px] text-base transition-all active:scale-[0.99] shadow-sm mb-4">
                Reset Password
            </button>


        </form>

    </div>

</body>
</html>