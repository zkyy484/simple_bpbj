@extends('tamu.layouts.app')

@section('title', 'Survei Kepuasan Pelayanan')

@push('styles')
<style>

/* =======================
   CARD
======================= */

.success-card{
    opacity:0;
    transform:translateY(35px) scale(.95);
    animation:cardShow .7s ease forwards;
}

@keyframes cardShow{
    to{
        opacity:1;
        transform:translateY(0) scale(1);
    }
}

/* =======================
   ICON
   PENTING: bounce dan pulse digabung dalam SATU deklarasi
   "animation" (comma-separated). Jangan dipisah ke class
   ".pulse" terpisah pada elemen yang sama — shorthand
   "animation" akan saling menimpa antar class dengan
   spesifisitas sama (yang terakhir di CSS menang), sehingga
   animasi bounce tidak pernah jalan dan icon tetap
   tersembunyi di scale(0).
======================= */

.icon-wrapper{
    transform:scale(0);
    animation:
        bounce .6s ease forwards,
        pulse 2s ease-in-out infinite;
    animation-delay:.35s, 1.2s;
}

@keyframes bounce{

    0%{
        transform:scale(0);
    }

    60%{
        transform:scale(1.15);
    }

    80%{
        transform:scale(.95);
    }

    100%{
        transform:scale(1);
    }

}

/* =======================
   SVG DRAW (CSS-driven, tidak bergantung pada JS)
======================= */

.circle{

    stroke-dasharray:145;
    stroke-dashoffset:145;
    animation:drawCircle .7s ease forwards;
    animation-delay:.35s;

}

@keyframes drawCircle{
    to{
        stroke-dashoffset:0;
    }
}

.check{

    stroke-dasharray:45;
    stroke-dashoffset:45;
    animation:drawCheck .45s ease-out forwards;
    animation-delay:.95s;

}

@keyframes drawCheck{
    to{
        stroke-dashoffset:0;
    }
}

/* =======================
   PULSE (dipakai sebagai bagian animasi .icon-wrapper di atas)
======================= */

@keyframes pulse{

    0%{
        box-shadow:0 0 0 0 rgba(34,197,94,.45);
    }

    70%{
        box-shadow:0 0 0 20px rgba(34,197,94,0);
    }

    100%{
        box-shadow:0 0 0 0 rgba(34,197,94,0);
    }

}

/* =======================
   TEXT
======================= */

.fade-up{

    opacity:0;
    transform:translateY(18px);

}

.show{

    animation:fadeUp .6s ease forwards;

}

@keyframes fadeUp{

    to{

        opacity:1;
        transform:translateY(0);

    }

}

</style>
@endpush

@section('content')

<div class="flex justify-center items-center">

    <div class="success-card bg-white rounded-xl shadow-2xl p-10 max-w-xl w-full text-center">

        <div class="icon-wrapper mx-auto bg-green-100 rounded-full h-24 w-24 flex justify-center items-center mb-6"
             style="width:6rem;height:6rem;">

            <svg width="70" height="70" viewBox="0 0 52 52" style="width:3.5rem;height:3.5rem;">

                <circle
                    class="circle"
                    cx="26"
                    cy="26"
                    r="23"
                    fill="none"
                    stroke="#16a34a"
                    stroke-width="3"/>

                <path
                    class="check"
                    fill="none"
                    stroke="#16a34a"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M14 27l7 7 16-16"/>

            </svg>

        </div>

        <h2 class="fade-up text-3xl font-bold text-gray-800 mb-3">
            Terima Kasih!
        </h2>

        <p class="fade-up text-gray-600 leading-relaxed mb-8">
            Survei kepuasan pelayanan berhasil dikirim.
            Masukan Anda sangat berarti bagi kami untuk terus
            meningkatkan kualitas pelayanan Bagian Pengadaan Barang dan Jasa.
        </p>

        <div class="fade-up bg-gray-50 border rounded-lg p-4 text-left mb-8">

            <div class="flex justify-between border-b pb-2 mb-2">

                <span class="text-gray-500">
                    Status
                </span>

                <span class="text-green-600 font-semibold">
                    ✔ Berhasil
                </span>

            </div>

            <div class="flex justify-between">

                <span class="text-gray-500">
                    Waktu
                </span>

                <span class="font-semibold">
                    {{ now()->translatedFormat('l, d F Y H:i') }}
                </span>

            </div>

        </div>

        <div class="fade-up">

            <a href="/"
               class="inline-block bg-[#112D55] hover:bg-[#0d2342] text-white font-semibold px-8 py-3 rounded-lg transition">

                Kembali ke Beranda

            </a>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded',()=>{

    document.querySelectorAll('.fade-up').forEach((el,index)=>{

        setTimeout(()=>{

            el.classList.add('show');

        },700+(index*180));

    });

});

</script>

@endpush
