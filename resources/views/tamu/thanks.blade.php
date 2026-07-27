@extends('tamu.layouts.app')

@section('title', 'Data Kunjungan Berhasil Dikirim')

@push('styles')
    <style>
        /* ==========================
           CARD ANIMATION
        ========================== */
        .success-card {
            opacity: 0;
            transform: translateY(35px) scale(.96);
            animation: cardShow .8s ease forwards;
        }

        @keyframes cardShow {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ==========================
           ICON BOUNCE
        ========================== */

        .js-checkmark-bg {
            transform: scale(0);
            /* PENTING: bounceIn dan pulse digabung dalam SATU deklarasi animation
               (comma-separated). Jangan dipisah ke class ".pulse" terpisah,
               karena shorthand "animation" akan saling menimpa antar class
               dengan spesifisitas sama, membuat bounceIn tidak pernah jalan
               dan elemen tetap tersembunyi di scale(0). */
            animation:
                bounceIn .6s ease forwards,
                pulse 2s ease-in-out infinite;
            animation-delay: .35s, 1.2s;
        }

        @keyframes bounceIn {

            0% {
                transform: scale(0);
            }

            60% {
                transform: scale(1.15);
            }

            80% {
                transform: scale(.95);
            }

            100% {
                transform: scale(1);
            }

        }

        /* ==========================
           SVG DRAW (CSS-driven, tidak bergantung pada JS)
        ========================== */

        .js-checkmark-circle {
            stroke-dasharray: 145;
            stroke-dashoffset: 145;
            animation: drawCircle .7s ease forwards;
            animation-delay: .35s;
        }

        @keyframes drawCircle {
            to {
                stroke-dashoffset: 0;
            }
        }

        .js-checkmark-check {
            stroke-dasharray: 45;
            stroke-dashoffset: 45;
            animation: drawCheck .45s ease-out forwards;
            animation-delay: .95s;
        }

        @keyframes drawCheck {
            to {
                stroke-dashoffset: 0;
            }
        }

        /* ==========================
           TEXT ANIMATION
        ========================== */

        .fade-up {
            opacity: 0;
            transform: translateY(18px);
        }

        .show {
            animation: fadeUp .6s ease forwards;
        }

        @keyframes fadeUp {

            to {

                opacity: 1;
                transform: translateY(0);

            }

        }

        /* ==========================
           GREEN PULSE (dipakai sebagai bagian animasi .js-checkmark-bg di atas)
        ========================== */

        @keyframes pulse {

            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, .45);
            }

            70% {
                box-shadow: 0 0 0 20px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }

        }
    </style>
@endpush

@section('content')

        <main class="container mx-auto py-12 px-4 flex justify-center items-center">

            <div class="success-card bg-white rounded-xl shadow-xl p-8 md:p-12 max-w-lg w-full text-center">

                <div id="iconBg"
                    class="js-checkmark-bg mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6 shadow-inner"
                    style="width:6rem;height:6rem;">

                    <svg class="h-14 w-14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"
                        style="width:3.5rem;height:3.5rem;">

                        <circle class="js-checkmark-circle" cx="26" cy="26" r="23" fill="none"
                            stroke="#16a34a" stroke-width="3" />

                        <path class="js-checkmark-check" fill="none" stroke="#16a34a" stroke-width="4"
                            stroke-linecap="round" stroke-linejoin="round" d="M14 27l7 7 16-16" />

                    </svg>

                </div>

                <h2 class="fade-up text-2xl font-extrabold text-gray-900 mb-3 tracking-tight">
                    Data Kunjungan Berhasil Dikirim!
                </h2>

                <p class="fade-up text-sm text-gray-600 mb-6 leading-relaxed">
                    Terima kasih telah mengisi Buku Tamu Digital.
                    Data kunjungan Anda telah berhasil tersimpan dalam sistem administrasi kami.
                </p>

                <div class="fade-up bg-gray-50 border border-gray-200 rounded-md p-4 mb-8 text-left text-xs space-y-2">

                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <span class="text-gray-500">
                            Waktu Pengisian:
                        </span>

                        <span class="font-semibold text-gray-700">
                            {{ now()->translatedFormat('l, d F Y H:i') }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-gray-200 pb-2">

                        <span class="text-gray-500">
                            Status:
                        </span>

                        <span class="font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded">
                            Terverifikasi
                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Tujuan:
                        </span>

                        <span class="font-semibold text-gray-700">
                            Bagian Pengadaan Barang & Jasa
                        </span>

                    </div>

                </div>

                <div class="fade-up">

                    <a href="{{ route('sur.page') }}"
                        class="inline-block bg-[#112D55] text-white px-8 py-2.5 rounded-md hover:bg-[#0d2342] transition duration-200 text-sm font-semibold tracking-wide shadow">

                        Kembali ke Halaman Utama

                    </a>

                </div>

            </div>

        </main>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            document.querySelectorAll(".fade-up").forEach((item, index) => {

                setTimeout(() => {

                    item.classList.add("show");

                }, 700 + (index * 180));

            });

        });
    </script>
@endpush