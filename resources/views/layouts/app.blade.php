<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WasteGuard — Deteksi Sampah B3')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --green-50:  #f0fdf4;
            --green-100: #dcfce7;
            --green-400: #4ade80;
            --green-500: #22c55e;
            --green-600: #16a34a;
            --green-700: #15803d;

            --lime-400:  #a3e635;
            --lime-500:  #84cc16;

            --amber-400: #fbbf24;
            --amber-500: #f59e0b;

            --red-400:   #f87171;
            --red-500:   #ef4444;
            --red-600:   #dc2626;

            --sky-400:   #38bdf8;
            --sky-500:   #0ea5e9;

            --slate-50:  #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;

            --white: #ffffff;

            --font-display: 'Plus Jakarta Sans', sans-serif;
            --font-body:    'Space Grotesk', sans-serif;

            --shadow-sm:  0 1px 3px rgba(0,0,0,.08);
            --shadow-md:  0 4px 16px rgba(0,0,0,.10);
            --shadow-lg:  0 12px 40px rgba(0,0,0,.14);
            --shadow-xl:  0 24px 64px rgba(0,0,0,.18);

            --radius-sm:  8px;
            --radius-md:  14px;
            --radius-lg:  22px;
            --radius-xl:  32px;
            --radius-full: 9999px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background-color: var(--slate-50);
            color: var(--slate-800);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ============================================================
           NAVBAR
        ============================================================ */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 0 2rem;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,255,255,.88);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1.5px solid rgba(34,197,94,.15);
            transition: all .3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            text-decoration: none;
        }

        .nav-brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--green-500), var(--lime-500));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(34,197,94,.4);
        }

        .nav-brand-text {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--slate-800);
        }

        .nav-brand-text span {
            color: var(--green-500);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: .25rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--slate-600);
            font-weight: 500;
            font-size: .9rem;
            padding: .45rem .85rem;
            border-radius: var(--radius-full);
            transition: all .2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: var(--green-100);
            color: var(--green-700);
        }

        .nav-cta {
            background: linear-gradient(135deg, var(--green-500), var(--green-600)) !important;
            color: white !important;
            box-shadow: 0 4px 14px rgba(34,197,94,.35);
            font-weight: 600 !important;
        }

        .nav-cta:hover {
            background: linear-gradient(135deg, var(--green-600), var(--green-700)) !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(34,197,94,.45) !important;
        }

        .nav-hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: .5rem;
            border: none;
            background: transparent;
        }

        .nav-hamburger span {
            display: block;
            width: 22px; height: 2px;
            background: var(--slate-700);
            border-radius: 2px;
            transition: all .3s;
        }

        /* ============================================================
           MAIN CONTENT WRAPPER
        ============================================================ */
        .page-wrapper {
            padding-top: 68px;
            min-height: 100vh;
        }

        /* ============================================================
           FOOTER
        ============================================================ */
        .footer {
            background: var(--slate-900);
            color: #94a3b8;
            padding: 3rem 2rem 2rem;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
        }

        .footer-brand p {
            margin-top: .75rem;
            font-size: .875rem;
            line-height: 1.7;
            max-width: 280px;
        }

        .footer-col h4 {
            color: white;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: .95rem;
            margin-bottom: 1rem;
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .footer-col ul a {
            color: #94a3b8;
            text-decoration: none;
            font-size: .875rem;
            transition: color .2s;
        }

        .footer-col ul a:hover { color: var(--green-400); }

        .footer-bottom {
            max-width: 1200px;
            margin: 2rem auto 0;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .8rem;
        }

        /* ============================================================
           TOAST NOTIFICATION
        ============================================================ */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .75rem;
            pointer-events: none;
        }

        .toast {
            background: white;
            border-radius: var(--radius-md);
            padding: 1rem 1.25rem;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: .75rem;
            min-width: 280px;
            max-width: 360px;
            pointer-events: all;
            border-left: 4px solid var(--green-500);
            animation: toastIn .35s cubic-bezier(.22,.68,0,1.2) both;
        }

        .toast.error { border-left-color: var(--red-500); }
        .toast.warning { border-left-color: var(--amber-500); }

        .toast-icon {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--green-100);
            color: var(--green-600);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            flex-shrink: 0;
        }

        .toast.error .toast-icon { background: #fef2f2; color: var(--red-600); }

        .toast-text { flex: 1; }
        .toast-title { font-weight: 600; font-size: .875rem; color: var(--slate-800); }
        .toast-msg   { font-size: .8rem; color: var(--slate-600); }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(120%); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 768px) {
            .nav-links  { display: none; }
            .nav-hamburger { display: flex; }

            .nav-links.open {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 68px; left: 0; right: 0;
                background: white;
                padding: 1rem;
                box-shadow: var(--shadow-md);
                gap: .25rem;
            }

            .footer-inner {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }

            .footer-bottom {
                flex-direction: column;
                gap: .5rem;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .footer-inner {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ============================================================ NAVBAR -->
<nav class="navbar" id="mainNav">
    <a href="{{ url('/') }}" class="nav-brand">
        <div class="nav-brand-icon"><i class="fas fa-recycle"></i></div>
        <span class="nav-brand-text">Waste<span>Guard</span></span>
    </a>

    <ul class="nav-links" id="navLinks">
        <li><a href="{{ url('/') }}"       class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a></li>
        <li><a href="{{ url('/deteksi') }}" class="{{ request()->is('deteksi') ? 'active' : '' }}">Deteksi</a></li>
        <li><a href="{{ url('/riwayat') }}" class="{{ request()->is('riwayat') ? 'active' : '' }}">Riwayat</a></li>
        <li><a href="{{ url('/edukasi') }}" class="{{ request()->is('edukasi') ? 'active' : '' }}">Edukasi</a></li>
        <li><a href="{{ url('/deteksi') }}" class="nav-cta">
            <i class="fas fa-camera" style="margin-right:.4rem"></i>Mulai Deteksi
        </a></li>
    </ul>

    <button class="nav-hamburger" id="hamburgerBtn" onclick="toggleNav()">
        <span></span><span></span><span></span>
    </button>
</nav>

<!-- ============================================================ PAGE CONTENT -->
<main class="page-wrapper">
    @yield('content')
</main>

<!-- ============================================================ FOOTER -->
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="{{ url('/') }}" class="nav-brand" style="text-decoration:none">
                <div class="nav-brand-icon" style="background:linear-gradient(135deg,#4ade80,#a3e635)">
                    <i class="fas fa-recycle"></i>
                </div>
                <span class="nav-brand-text" style="color:white">Waste<span>Guard</span></span>
            </a>
            <p>Platform deteksi sampah B3 dan Non-B3 berbasis AI untuk lingkungan yang lebih bersih dan aman bagi masyarakat Indonesia.</p>
        </div>

        <div class="footer-col">
            <h4>Fitur</h4>
            <ul>
                <li><a href="{{ url('/deteksi') }}">Deteksi Kamera</a></li>
                <li><a href="{{ url('/deteksi') }}">Upload Gambar</a></li>
                <li><a href="{{ url('/riwayat') }}">Riwayat Deteksi</a></li>
                <li><a href="{{ url('/') }}#statistik">Statistik</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Edukasi</h4>
            <ul>
                <li><a href="{{ url('/edukasi') }}">Apa itu B3?</a></li>
                <li><a href="{{ url('/edukasi') }}#jenis">Jenis Sampah B3</a></li>
                <li><a href="{{ url('/edukasi') }}#cara">Cara Pembuangan</a></li>
                <li><a href="{{ url('/edukasi') }}#bahaya">Bahaya B3</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Tentang</h4>
            <ul>
                <li><a href="#">Tim Kami</a></li>
                <li><a href="#">Hubungi Kami</a></li>
                <li><a href="#">Kebijakan Privasi</a></li>
                <li><a href="#">API Docs</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <span>© 2025 WasteGuard. Dibuat dengan <i class="fas fa-heart" style="color:#f87171"></i> untuk lingkungan Indonesia.</span>
        <span>Didukung oleh YOLO v8 & Python AI Engine</span>
    </div>
</footer>

<!-- ============================================================ TOAST -->
<!-- ============================================================ TOAST -->
<div class="toast-container" id="toastContainer"></div>

<!-- 1. Tambahkan div tersembunyi ini untuk menyimpan data session dari Laravel -->
<div id="flash-messages" 
     data-success="{{ session('success') }}" 
     data-error="{{ session('error') }}" 
     style="display: none;">
</div>

<script>
    /* Navbar scroll effect */
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 20);
    });

    /* Mobile nav toggle */
    function toggleNav() {
        document.getElementById('navLinks').classList.toggle('open');
    }

    /* Toast utility */
    function showToast(title, msg = '', type = 'success') {
        const c = document.getElementById('toastContainer');
        const icons = { success: 'fa-check', error: 'fa-times', warning: 'fa-exclamation' };
        const t = document.createElement('div');
        t.className = `toast ${type !== 'success' ? type : ''}`;
        t.innerHTML = `
            <div class="toast-icon"><i class="fas ${icons[type] || icons.success}"></i></div>
            <div class="toast-text">
                <div class="toast-title">${title}</div>
                ${msg ? `<div class="toast-msg">${msg}</div>` : ''}
            </div>
            <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:.8rem;padding:.25rem">✕</button>`;
        c.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateX(120%)'; t.style.transition = 'all .3s'; setTimeout(() => t.remove(), 300); }, 4500);
    }

    // 2. Baca data dari div tersembunyi menggunakan JavaScript murni (Bebas Error Linter VS Code)
    const flashMessages = document.getElementById('flash-messages');
    const successMsg = flashMessages.getAttribute('data-success');
    const errorMsg = flashMessages.getAttribute('data-error');

    if (successMsg) {
        showToast('Berhasil', successMsg, 'success');
    }
    
    if (errorMsg) {
        showToast('Terjadi Kesalahan', errorMsg, 'error');
    }
</script>

@stack('scripts')
</body>
</html>