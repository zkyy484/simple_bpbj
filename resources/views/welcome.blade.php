{{--
    Splash Screen - Buku Tamu BPBJ
    Cara pakai di Laravel:
    1. Simpan gambar logo (PNG transparan) di: public/images/logo-lampung.png
       (sesuaikan nama file di baris background-image di bawah kalau berbeda)
    2. Simpan file ini di: resources/views/splash.blade.php
    3. Set sebagai halaman awal, misalnya di routes/web.php:
       Route::get('/', function () { return view('splash'); });
    4. Splash otomatis fade-out setelah animasi (atur di angka setTimeout di JS bawah),
       lalu bisa redirect ke halaman buku tamu (contoh baris redirect sudah disediakan).
--}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Splash Screen - Buku Tamu BPBJ</title>
<style>
  *{ box-sizing:border-box; }
  html,body{
    margin:0; padding:0; height:100%;
    background:#0F1B3A;
    display:flex; align-items:center; justify-content:center;
    overflow:hidden;
    font-family:'Segoe UI', Arial, sans-serif;
  }

  .splash-screen{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    width:100%;
    height:100%;
    opacity:1;
    transition:opacity 0.7s ease;
  }
  .splash-screen.hide{ opacity:0; pointer-events:none; }

  .logo-stage{
    width:55vmin;
    max-width:320px;
    aspect-ratio:1/1;
    background-image:url("{{ asset('images/logo.png') }}");
    background-repeat:no-repeat;
    background-position:center;
    background-size:contain;
    opacity:0;
    transform:scale(0.6);
    animation:fadeZoom 1.4s cubic-bezier(.22,.9,.32,1) forwards;
  }

  @keyframes fadeZoom{
    0%{ opacity:0; transform:scale(0.6); }
    100%{ opacity:1; transform:scale(1); }
  }

  .splash-text{
    margin-top:22px;
    text-align:center;
    opacity:0;
    animation:textFadeUp 0.8s ease-out forwards;
    animation-delay:1.1s;
  }
  .splash-text h1{
    color:#ffffff;
    font-size:clamp(20px, 4vmin, 30px);
    font-weight:700;
    letter-spacing:1.5px;
    margin:0 0 4px 0;
    text-shadow:0 2px 8px rgba(0,0,0,0.35);
  }
  .splash-text p{
    color:#ffd400;
    font-size:clamp(12px, 2.2vmin, 15px);
    letter-spacing:2px;
    margin:0;
    text-transform:uppercase;
    opacity:0.9;
  }

  .splash-loading{
    margin-top:26px;
    width:120px;
    height:4px;
    border-radius:4px;
    background:rgba(255,255,255,0.2);
    overflow:hidden;
    opacity:0;
    animation:textFadeUp 0.6s ease-out forwards;
    animation-delay:1.3s;
  }
  .splash-loading::after{
    content:"";
    display:block;
    width:40%;
    height:100%;
    background:#ffd400;
    border-radius:4px;
    animation:loadingBar 1.2s ease-in-out infinite;
  }
  @keyframes loadingBar{
    0%{ transform:translateX(-100%); }
    100%{ transform:translateX(340%); }
  }
  @keyframes textFadeUp{
    from{ opacity:0; transform:translateY(14px); }
    to{ opacity:1; transform:translateY(0); }
  }
</style>
</head>
<body>

<div class="splash-screen" id="splashScreen">
  <div class="logo-stage"></div>

  <div class="splash-text">
    <h1>Buku Tamu BPBJ</h1>
    <p>Sistem Informasi &amp; Layanan</p>
  </div>

  <div class="splash-loading"></div>
</div>

<script>
  window.addEventListener('load', function () {
    setTimeout(function () {
      const splash = document.getElementById('splashScreen');
      if (splash) {
        splash.classList.add('hide');
        setTimeout(() => {
          splash.style.display = 'none';
          window.location.href = "{{ route('tamu.form') }}";
        }, 700);
      }
    }, 3500);
  });
</script>

</body>
</html>
