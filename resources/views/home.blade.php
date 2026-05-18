@extends('layouts.app')

@section('title', 'WasteGuard — Deteksi Sampah B3 & Non-B3')

@push('styles')
<style>
/* ============================================================
   HERO
============================================================ */
.hero {
    min-height: calc(100vh - 68px);
    background: linear-gradient(160deg, #f0fdf4 0%, #dcfce7 40%, #ecfdf5 70%, #f0fdf4 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
}

.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 30%, rgba(34,197,94,.12) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(163,230,53,.10) 0%, transparent 45%),
        radial-gradient(circle at 60% 10%, rgba(251,191,36,.08) 0%, transparent 40%);
    pointer-events: none;
}

/* floating circles */
.hero-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    opacity: .35;
    animation: blobFloat 8s ease-in-out infinite;
    pointer-events: none;
}

.hero-blob-1 { width: 420px; height: 420px; background: var(--green-400); top: -120px; right: -80px; animation-delay: 0s; }
.hero-blob-2 { width: 300px; height: 300px; background: var(--lime-400); bottom: -60px; left: 5%; animation-delay: 3s; }
.hero-blob-3 { width: 200px; height: 200px; background: var(--amber-400); top: 40%; right: 20%; animation-delay: 1.5s; }

@keyframes blobFloat {
    0%,100% { transform: translateY(0) scale(1); }
    50%      { transform: translateY(-24px) scale(1.04); }
}

.hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 5rem 2rem 4rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    position: relative;
    z-index: 1;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(34,197,94,.15);
    border: 1.5px solid rgba(34,197,94,.3);
    color: var(--green-700);
    font-size: .8rem;
    font-weight: 700;
    padding: .35rem 1rem;
    border-radius: var(--radius-full);
    margin-bottom: 1.5rem;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.hero-badge i { font-size: .75rem; animation: pulse-dot 2s infinite; }

@keyframes pulse-dot {
    0%,100% { opacity: 1; }
    50%      { opacity: .4; }
}

.hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.2rem, 5vw, 3.4rem);
    font-weight: 800;
    line-height: 1.15;
    color: var(--slate-900);
    margin-bottom: 1.25rem;
}

.hero-title .highlight {
    position: relative;
    color: var(--green-600);
    display: inline-block;
}

.hero-title .highlight::after {
    content: '';
    position: absolute;
    bottom: 4px; left: 0; right: 0;
    height: 8px;
    background: linear-gradient(90deg, var(--lime-400), var(--green-400));
    border-radius: 4px;
    opacity: .4;
    z-index: -1;
}

.hero-subtitle {
    font-size: 1.05rem;
    color: var(--slate-600);
    line-height: 1.7;
    margin-bottom: 2.25rem;
    max-width: 520px;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .85rem 1.8rem;
    border-radius: var(--radius-full);
    font-weight: 700;
    font-size: .95rem;
    text-decoration: none;
    cursor: pointer;
    border: none;
    transition: all .25s;
    white-space: nowrap;
}

.btn-primary {
    background: linear-gradient(135deg, var(--green-500), var(--green-600));
    color: white;
    box-shadow: 0 6px 24px rgba(34,197,94,.4);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 32px rgba(34,197,94,.5);
}

.btn-outline {
    background: white;
    color: var(--slate-700);
    border: 2px solid var(--slate-200);
    box-shadow: var(--shadow-sm);
}

.btn-outline:hover {
    border-color: var(--green-400);
    color: var(--green-700);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.hero-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1.5px solid rgba(34,197,94,.2);
}

.hero-stat-num {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 1.6rem;
    color: var(--slate-900);
    line-height: 1;
}

.hero-stat-num span { color: var(--green-500); }

.hero-stat-label {
    font-size: .8rem;
    color: var(--slate-500);
    margin-top: .25rem;
}

/* Hero visual card */
.hero-visual {
    position: relative;
}

.hero-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: 1.75rem;
    box-shadow: var(--shadow-xl), 0 0 0 1px rgba(34,197,94,.1);
    position: relative;
    overflow: hidden;
}

.hero-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--green-400), var(--lime-400), var(--amber-400));
}

.hero-preview-img {
    width: 100%;
    aspect-ratio: 4/3;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-radius: var(--radius-lg);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    border: 2px dashed rgba(34,197,94,.25);
}

.hero-preview-scan {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--green-500), transparent);
    animation: scanLine 2.5s ease-in-out infinite;
}

@keyframes scanLine {
    0%   { top: 0%; opacity: 1; }
    100% { top: 100%; opacity: 0; }
}

.hero-preview-icon {
    width: 80px; height: 80px;
    background: linear-gradient(135deg, var(--green-500), var(--lime-500));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    box-shadow: 0 12px 32px rgba(34,197,94,.35);
    margin-bottom: 1rem;
    animation: iconPulse 2s ease-in-out infinite;
}

@keyframes iconPulse {
    0%,100% { box-shadow: 0 12px 32px rgba(34,197,94,.35), 0 0 0 0 rgba(34,197,94,.25); }
    50%      { box-shadow: 0 12px 32px rgba(34,197,94,.35), 0 0 0 20px rgba(34,197,94,0); }
}

.hero-preview-text { font-size: .875rem; color: var(--slate-500); font-weight: 500; }

/* floating badges */
.hero-float-badge {
    position: absolute;
    background: white;
    border-radius: var(--radius-md);
    padding: .65rem 1rem;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .8rem;
    font-weight: 600;
    animation: badgeFloat 4s ease-in-out infinite;
    z-index: 2;
}

.hero-float-badge.b3 {
    bottom: -1.5rem; left: -1.5rem;
    border-left: 3px solid var(--red-500);
    color: var(--red-600);
    animation-delay: 1s;
}

.hero-float-badge.nonb3 {
    top: -1rem; right: -1.5rem;
    border-left: 3px solid var(--green-500);
    color: var(--green-600);
}

@keyframes badgeFloat {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-8px); }
}

/* ============================================================
   HOW IT WORKS
============================================================ */
.section {
    padding: 5rem 2rem;
}

.section-inner {
    max-width: 1200px;
    margin: 0 auto;
}

.section-label {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--green-600);
    background: var(--green-50);
    border: 1px solid var(--green-100);
    padding: .35rem .9rem;
    border-radius: var(--radius-full);
    margin-bottom: 1rem;
}

.section-title {
    font-family: var(--font-display);
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 800;
    color: var(--slate-900);
    line-height: 1.2;
    margin-bottom: .75rem;
}

.section-sub {
    font-size: 1rem;
    color: var(--slate-500);
    max-width: 520px;
    line-height: 1.7;
}

.how-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-top: 3rem;
}

.how-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: 2rem 1.5rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
    border: 1.5px solid var(--slate-100);
    position: relative;
    transition: all .3s;
}

.how-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: var(--green-200);
}

.how-card-num {
    position: absolute;
    top: -14px; left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, var(--green-500), var(--lime-500));
    color: white;
    width: 28px; height: 28px;
    border-radius: 50%;
    font-size: .75rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(34,197,94,.4);
}

.how-card-icon {
    width: 64px; height: 64px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin: 0 auto 1.25rem;
}

.how-card-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1rem;
    color: var(--slate-800);
    margin-bottom: .5rem;
}

.how-card-desc {
    font-size: .85rem;
    color: var(--slate-500);
    line-height: 1.6;
}

/* ============================================================
   WASTE TYPES
============================================================ */
.waste-section { background: white; }

.waste-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 3rem;
}

.waste-card {
    border-radius: var(--radius-xl);
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
    transition: all .3s;
}

.waste-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-xl); }

.waste-card.b3 {
    background: linear-gradient(135deg, #fff1f2, #ffe4e6);
    border: 2px solid rgba(239,68,68,.15);
}

.waste-card.nonb3 {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 2px solid rgba(34,197,94,.15);
}

.waste-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.waste-card-icon {
    width: 56px; height: 56px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.waste-card.b3    .waste-card-icon { background: rgba(239,68,68,.15); color: var(--red-600); }
.waste-card.nonb3 .waste-card-icon { background: rgba(34,197,94,.15); color: var(--green-700); }

.waste-card-title {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 1.25rem;
}

.waste-card.b3    .waste-card-title { color: var(--red-600); }
.waste-card.nonb3 .waste-card-title { color: var(--green-700); }

.waste-card-subtitle {
    font-size: .8rem;
    font-weight: 500;
    opacity: .7;
}

.waste-items {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .5rem;
    margin-bottom: 1.5rem;
}

.waste-item {
    display: flex;
    align-items: center;
    gap: .5rem;
    background: rgba(255,255,255,.7);
    padding: .6rem .85rem;
    border-radius: var(--radius-sm);
    font-size: .82rem;
    font-weight: 500;
    color: var(--slate-700);
}

.waste-item i { font-size: .75rem; }
.waste-card.b3    .waste-item i { color: var(--red-500); }
.waste-card.nonb3 .waste-item i { color: var(--green-500); }

/* ============================================================
   STATS SECTION
============================================================ */
.stats-section {
    background: linear-gradient(135deg, var(--slate-900), var(--slate-800));
    padding: 5rem 2rem;
    position: relative;
    overflow: hidden;
}

.stats-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 50%, rgba(34,197,94,.12) 0%, transparent 50%),
        radial-gradient(circle at 80% 30%, rgba(163,230,53,.08) 0%, transparent 40%);
    pointer-events: none;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.stat-box {
    text-align: center;
}

.stat-box-num {
    font-family: var(--font-display);
    font-size: 2.8rem;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: .35rem;
}

.stat-box-num span { color: var(--green-400); }

.stat-box-label {
    font-size: .85rem;
    color: #94a3b8;
    line-height: 1.4;
}

/* ============================================================
   CTA SECTION
============================================================ */
.cta-section {
    padding: 5rem 2rem;
    background: var(--green-50);
    text-align: center;
}

.cta-card {
    max-width: 680px;
    margin: 0 auto;
    background: white;
    border-radius: var(--radius-xl);
    padding: 3.5rem 3rem;
    box-shadow: var(--shadow-xl);
    border: 2px solid rgba(34,197,94,.12);
    position: relative;
    overflow: hidden;
}

.cta-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 5px;
    background: linear-gradient(90deg, var(--green-400), var(--lime-400), var(--amber-400), var(--green-400));
    background-size: 200%;
    animation: gradientMove 3s linear infinite;
}

@keyframes gradientMove {
    0%   { background-position: 0% 0%; }
    100% { background-position: 200% 0%; }
}

.cta-emoji { font-size: 3rem; margin-bottom: 1rem; display: block; }

.cta-title {
    font-family: var(--font-display);
    font-size: 1.9rem;
    font-weight: 800;
    color: var(--slate-900);
    margin-bottom: .75rem;
}

.cta-subtitle {
    color: var(--slate-500);
    margin-bottom: 2rem;
    line-height: 1.7;
}

/* ============================================================
   RESPONSIVE
============================================================ */
@media (max-width: 1024px) {
    .how-grid { grid-template-columns: repeat(2, 1fr); }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .hero-inner { grid-template-columns: 1fr; gap: 2rem; padding: 3rem 1.25rem; }
    .hero-visual { order: -1; }
    .hero-float-badge.b3   { bottom: -.75rem; left: .5rem; }
    .hero-float-badge.nonb3 { top: -.75rem; right: .5rem; }
    .waste-grid { grid-template-columns: 1fr; }
    .waste-items { grid-template-columns: 1fr; }
    .how-grid { grid-template-columns: 1fr 1fr; }
    .hero-stats { gap: 1.5rem; }
    .cta-card { padding: 2.5rem 1.5rem; }
}

@media (max-width: 480px) {
    .how-grid { grid-template-columns: 1fr; }
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .hero-actions { flex-direction: column; }
    .btn { justify-content: center; }
}
</style>
@endpush

@section('content')

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>
    <div class="hero-blob hero-blob-3"></div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-circle-dot"></i>
                AI-Powered Detection • YOLO v8
            </div>

            <h1 class="hero-title">
                Deteksi Sampah<br>
                <span class="highlight">B3 & Non-B3</span><br>
                Dengan Kecerdasan Buatan
            </h1>

            <p class="hero-subtitle">
                Identifikasi jenis sampah berbahaya dan beracun (B3) secara otomatis menggunakan kamera atau unggah foto. Teknologi YOLO v8 mendeteksi dengan akurasi tinggi untuk lingkungan yang lebih aman.
            </p>

            <div class="hero-actions">
                <a href="{{ url('/deteksi') }}" class="btn btn-primary">
                    <i class="fas fa-camera"></i>
                    Mulai Deteksi Sekarang
                </a>
                <a href="{{ url('/edukasi') }}" class="btn btn-outline">
                    <i class="fas fa-book-open"></i>
                    Pelajari B3
                </a>
            </div>

            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">98<span>%</span></div>
                    <div class="hero-stat-label">Akurasi Deteksi</div>
                </div>
                <div>
                    <div class="hero-stat-num">20<span>+</span></div>
                    <div class="hero-stat-label">Jenis Sampah</div>
                </div>
                <div>
                    <div class="hero-stat-num">0.3<span>s</span></div>
                    <div class="hero-stat-label">Waktu Deteksi</div>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-float-badge nonb3">
                <i class="fas fa-check-circle" style="color:var(--green-500)"></i>
                Non-B3 Terdeteksi ✓
            </div>
            <div class="hero-card">
                <div class="hero-preview-img">
                    <div class="hero-preview-scan"></div>
                    <div class="hero-preview-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <p class="hero-preview-text">Arahkan kamera ke sampah</p>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-top:1.25rem">
                    <div style="background:var(--green-50);border-radius:var(--radius-sm);padding:.85rem;border:1.5px solid rgba(34,197,94,.2)">
                        <div style="font-size:.7rem;color:var(--green-600);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.25rem">Kepercayaan</div>
                        <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:800;color:var(--green-600)">97.8%</div>
                    </div>
                    <div style="background:#fff1f2;border-radius:var(--radius-sm);padding:.85rem;border:1.5px solid rgba(239,68,68,.15)">
                        <div style="font-size:.7rem;color:var(--red-600);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.25rem">Kategori</div>
                        <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:800;color:var(--red-600)">B3 ⚠️</div>
                    </div>
                </div>
            </div>
            <div class="hero-float-badge b3">
                <i class="fas fa-exclamation-triangle" style="color:var(--red-500)"></i>
                B3 Terdeteksi — Hati-hati!
            </div>
        </div>
    </div>
</section>

<!-- ===== HOW IT WORKS ===== -->
<section class="section" id="cara-kerja">
    <div class="section-inner">
        <div style="text-align:center;margin-bottom:0">
            <div class="section-label"><i class="fas fa-cogs"></i> Cara Kerja</div>
            <h2 class="section-title">Proses Deteksi dalam 4 Langkah</h2>
            <p class="section-sub" style="margin:0 auto">Teknologi kami menganalisis sampah secara real-time menggunakan model YOLO v8 yang terlatih.</p>
        </div>

        <div class="how-grid">
            <div class="how-card">
                <div class="how-card-num">1</div>
                <div class="how-card-icon" style="background:#eff6ff;color:#3b82f6">
                    <i class="fas fa-camera"></i>
                </div>
                <div class="how-card-title">Ambil Gambar</div>
                <p class="how-card-desc">Gunakan kamera secara langsung atau unggah foto sampah yang ingin diidentifikasi.</p>
            </div>

            <div class="how-card">
                <div class="how-card-num">2</div>
                <div class="how-card-icon" style="background:#fef9c3;color:#ca8a04">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="how-card-title">Analisis AI</div>
                <p class="how-card-desc">Model YOLO v8 memproses gambar dan mengidentifikasi objek sampah yang ada.</p>
            </div>

            <div class="how-card">
                <div class="how-card-num">3</div>
                <div class="how-card-icon" style="background:#fdf4ff;color:#a855f7">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="how-card-title">Klasifikasi</div>
                <p class="how-card-desc">Sistem Python mengklasifikasikan setiap objek ke kategori B3 atau Non-B3.</p>
            </div>

            <div class="how-card">
                <div class="how-card-num">4</div>
                <div class="how-card-icon" style="background:var(--green-50);color:var(--green-600)">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="how-card-title">Hasil & Saran</div>
                <p class="how-card-desc">Dapatkan hasil deteksi lengkap dengan saran penanganan yang tepat dan aman.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== WASTE TYPES ===== -->
<section class="section waste-section" id="jenis-sampah">
    <div class="section-inner">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;align-items:end;margin-bottom:0">
            <div>
                <div class="section-label"><i class="fas fa-recycle"></i> Kategori Sampah</div>
                <h2 class="section-title">B3 vs Non-B3</h2>
                <p class="section-sub">Kenali perbedaan sampah berbahaya dan tidak berbahaya agar bisa ditangani dengan benar.</p>
            </div>
            <div style="text-align:right">
                <a href="{{ url('/edukasi') }}" class="btn btn-outline" style="font-size:.85rem;padding:.65rem 1.4rem">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="waste-grid">
            <!-- B3 Card -->
            <div class="waste-card b3">
                <div class="waste-card-header">
                    <div class="waste-card-icon"><i class="fas fa-biohazard"></i></div>
                    <div>
                        <div class="waste-card-title">Sampah B3</div>
                        <div class="waste-card-subtitle" style="color:var(--red-500)">Bahan Berbahaya & Beracun</div>
                    </div>
                </div>
                <p style="font-size:.875rem;color:var(--slate-600);margin-bottom:1.5rem;line-height:1.7">
                    Sampah yang mengandung zat berbahaya, beracun, atau berpotensi mencemari lingkungan dan membahayakan kesehatan manusia.
                </p>
                <div class="waste-items">
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Baterai Bekas</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Lampu Neon</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Tinta Printer</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Cat & Solvent</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Obat Kadaluarsa</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Elektronik Rusak</div>
                </div>
                <div style="background:rgba(239,68,68,.08);border-radius:var(--radius-sm);padding:.85rem;display:flex;align-items:center;gap:.6rem">
                    <i class="fas fa-exclamation-triangle" style="color:var(--red-500)"></i>
                    <span style="font-size:.8rem;color:var(--red-700);font-weight:500">Jangan dibuang sembarangan! Perlu penanganan khusus.</span>
                </div>
            </div>

            <!-- Non-B3 Card -->
            <div class="waste-card nonb3">
                <div class="waste-card-header">
                    <div class="waste-card-icon"><i class="fas fa-leaf"></i></div>
                    <div>
                        <div class="waste-card-title">Sampah Non-B3</div>
                        <div class="waste-card-subtitle" style="color:var(--green-600)">Tidak Berbahaya</div>
                    </div>
                </div>
                <p style="font-size:.875rem;color:var(--slate-600);margin-bottom:1.5rem;line-height:1.7">
                    Sampah rumah tangga biasa yang tidak mengandung zat berbahaya dan dapat didaur ulang atau dibuang melalui jalur normal.
                </p>
                <div class="waste-items">
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Botol Plastik</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Kertas & Kardus</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Sisa Makanan</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Kaleng Minuman</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Kaca Botol</div>
                    <div class="waste-item"><i class="fas fa-circle-dot"></i> Daun & Ranting</div>
                </div>
                <div style="background:rgba(34,197,94,.1);border-radius:var(--radius-sm);padding:.85rem;display:flex;align-items:center;gap:.6rem">
                    <i class="fas fa-check-circle" style="color:var(--green-500)"></i>
                    <span style="font-size:.8rem;color:var(--green-700);font-weight:500">Dapat didaur ulang dan dikelola secara normal.</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATS ===== -->
<section class="stats-section" id="statistik">
    <div style="text-align:center;margin-bottom:3rem;position:relative;z-index:1">
        <div class="section-label" style="background:rgba(34,197,94,.15);border-color:rgba(34,197,94,.3);color:var(--green-400)">
            <i class="fas fa-chart-bar"></i> Statistik Platform
        </div>
        <h2 class="section-title" style="color:white">WasteGuard dalam Angka</h2>
    </div>
    <div class="stats-grid" style="position:relative;z-index:1">
        <div class="stat-box">
            <div class="stat-box-num" data-count="15420">0<span>+</span></div>
            <div class="stat-box-label">Deteksi Berhasil<br>Dilakukan</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-num">98<span>%</span></div>
            <div class="stat-box-label">Akurasi Model<br>YOLO v8</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-num">20<span>+</span></div>
            <div class="stat-box-label">Kategori Sampah<br>Terklasifikasi</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-num">3<span>k+</span></div>
            <div class="stat-box-label">Pengguna Aktif<br>Setiap Bulan</div>
        </div>
    </div>
</section>

<!-- ===== CTA ===== -->
<section class="cta-section">
    <div class="cta-card">
        <span class="cta-emoji">♻️</span>
        <h2 class="cta-title">Siap Mulai Deteksi?</h2>
        <p class="cta-subtitle">Gunakan kamera perangkat Anda untuk mendeteksi sampah B3 secara langsung, gratis, dan tanpa perlu instal aplikasi apapun.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
            <a href="{{ url('/deteksi') }}" class="btn btn-primary" style="font-size:1rem;padding:1rem 2.25rem">
                <i class="fas fa-camera"></i> Buka Kamera Sekarang
            </a>
            <a href="{{ url('/deteksi') }}#upload" class="btn btn-outline" style="font-size:1rem;padding:1rem 2.25rem">
                <i class="fas fa-upload"></i> Upload Foto
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
/* Animated counter */
const counters = document.querySelectorAll('[data-count]');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el   = e.target;
        const end  = parseInt(el.dataset.count);
        const dur  = 2000;
        const step = end / (dur / 16);
        let cur    = 0;
        const t    = setInterval(() => {
            cur = Math.min(cur + step, end);
            el.childNodes[0].textContent = Math.floor(cur).toLocaleString('id-ID');
            if (cur >= end) clearInterval(t);
        }, 16);
        observer.unobserve(el);
    });
}, { threshold: .5 });
counters.forEach(c => observer.observe(c));
</script>
@endpush