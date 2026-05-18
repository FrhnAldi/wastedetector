@extends('layouts.app')

@section('title', 'Deteksi Sampah — WasteGuard')

@push('styles')
<style>
/* ============================================================
   DETECTION PAGE LAYOUT
============================================================ */
.detect-page {
    background: var(--slate-50);
    min-height: calc(100vh - 68px);
    padding: 2rem;
}

.detect-inner {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 1.75rem;
}

.page-header-top {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: .5rem;
}

.page-title {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--slate-900);
}

.page-subtitle {
    color: var(--slate-500);
    font-size: .95rem;
}

.status-chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .3rem .85rem;
    border-radius: var(--radius-full);
    font-size: .78rem;
    font-weight: 700;
}

.status-chip.ready      { background: var(--green-100); color: var(--green-700); }
.status-chip.detecting  { background: #fef9c3; color: #92400e; }
.status-chip.done       { background: #eff6ff; color: #1d4ed8; }
.status-chip.error      { background: #fef2f2; color: var(--red-700); }
.status-chip.connecting { background: #fef9c3; color: #92400e; }

.dot-indicator {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: currentColor;
    animation: blinkDot 1.5s infinite;
}

@keyframes blinkDot {
    0%,100% { opacity: 1; }
    50%      { opacity: .3; }
}

/* ============================================================
   MAIN GRID
============================================================ */
.detect-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.5rem;
}

/* ============================================================
   TAB SWITCHER
============================================================ */
.tab-bar {
    display: flex;
    gap: .25rem;
    background: white;
    border-radius: var(--radius-lg);
    padding: .35rem;
    box-shadow: var(--shadow-sm);
    border: 1.5px solid var(--slate-100);
    margin-bottom: 1rem;
}

.tab-btn {
    flex: 1;
    padding: .75rem 1.25rem;
    border-radius: var(--radius-md);
    border: none;
    background: transparent;
    color: var(--slate-500);
    font-weight: 600;
    font-size: .875rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    transition: all .2s;
}

.tab-btn.active {
    background: linear-gradient(135deg, var(--green-500), var(--green-600));
    color: white;
    box-shadow: 0 4px 14px rgba(34,197,94,.35);
}

.tab-btn:not(.active):hover {
    background: var(--slate-50);
    color: var(--slate-700);
}

/* ============================================================
   CAMERA PANEL
============================================================ */
.camera-card {
    background: white;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    border: 1.5px solid var(--slate-100);
}

.camera-viewport {
    position: relative;
    background: #0f172a;
    aspect-ratio: 4/3;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

#videoFeed {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: none;
}

/*
 * FIX KAMERA: Canvas harus overlay persis di atas video.
 * position:absolute + inset:0 + pointer-events:none sudah benar,
 * tapi width/height harus diset via JS saat resize agar tidak melar.
 */
#detectionCanvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.camera-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    color: #64748b;
    text-align: center;
    padding: 2rem;
}

.camera-placeholder-icon {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(34,197,94,.1);
    border: 2px solid rgba(34,197,94,.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--green-500);
}

.camera-placeholder h3 { font-family: var(--font-display); font-size: 1rem; color: #94a3b8; }
.camera-placeholder p  { font-size: .825rem; color: #64748b; }

/* scan overlay */
.scan-overlay { position: absolute; inset: 0; pointer-events: none; display: none; }
.scan-overlay.active { display: block; }

.scan-corner {
    position: absolute;
    width: 28px; height: 28px;
    border-color: var(--green-400);
    border-style: solid;
    opacity: .8;
}

.scan-corner.tl { top: 16px; left: 16px;   border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
.scan-corner.tr { top: 16px; right: 16px;  border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
.scan-corner.bl { bottom: 16px; left: 16px;  border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
.scan-corner.br { bottom: 16px; right: 16px; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }

.scan-line-anim {
    position: absolute;
    left: 16px; right: 16px;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--green-400), var(--lime-400), var(--green-400), transparent);
    animation: scanMove 2s ease-in-out infinite;
    top: 16px;
}

@keyframes scanMove {
    0%   { top: 16px; opacity: 1; }
    100% { top: calc(100% - 16px); opacity: .4; }
}

.cam-overlay-info {
    position: absolute;
    top: 12px; left: 12px;
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(8px);
    border-radius: var(--radius-sm);
    padding: .35rem .7rem;
    gap: .75rem;
    display: none;
}

.cam-overlay-info.visible { display: flex; }

.cam-info-item {
    font-size: .72rem;
    color: rgba(255,255,255,.85);
    font-weight: 600;
    font-family: monospace;
}

.cam-info-item span { color: var(--lime-400); }

.cam-controls {
    position: absolute;
    bottom: 12px; right: 12px;
    flex-direction: column;
    gap: .5rem;
    display: none;
}

.cam-controls.visible { display: flex; }

.cam-ctrl-btn {
    width: 38px; height: 38px;
    background: rgba(0,0,0,.5);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 50%;
    color: white;
    font-size: .85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .2s;
}

.cam-ctrl-btn:hover { background: rgba(34,197,94,.5); }

/* ============================================================
   CAMERA CONTROLS BAR
============================================================ */
.cam-actions {
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: .75rem;
    border-top: 1.5px solid var(--slate-100);
}

.btn-detect {
    flex: 1;
    padding: .85rem;
    border-radius: var(--radius-full);
    background: linear-gradient(135deg, var(--green-500), var(--green-600));
    color: white;
    font-weight: 700;
    font-size: .95rem;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    box-shadow: 0 4px 16px rgba(34,197,94,.35);
    transition: all .25s;
    position: relative;
    overflow: hidden;
}

.btn-detect:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(34,197,94,.45);
}

.btn-detect:disabled { opacity: .6; cursor: not-allowed; transform: none; }

.btn-detect.loading::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.2) 50%, transparent 100%);
    background-size: 200%;
    animation: shimmer .8s linear infinite;
}

@keyframes shimmer {
    from { background-position: -200% 0; }
    to   { background-position:  200% 0; }
}

.btn-icon-only {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: var(--slate-100);
    border: 2px solid var(--slate-200);
    color: var(--slate-600);
    font-size: .9rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all .2s;
}

.btn-icon-only:hover {
    background: var(--green-100);
    border-color: var(--green-300);
    color: var(--green-700);
}

/* ============================================================
   UPLOAD PANEL
   FIX: Wrapper pakai position:relative agar canvas overlay tepat
============================================================ */
.upload-zone {
    border: 2.5px dashed var(--slate-200);
    border-radius: var(--radius-xl);
    padding: 3.5rem 2rem;
    text-align: center;
    cursor: pointer;
    transition: all .3s;
    background: var(--slate-50);
    position: relative;
}

.upload-zone:hover,
.upload-zone.dragover {
    border-color: var(--green-400);
    background: var(--green-50);
}

.upload-zone input[type=file] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}

.upload-icon {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, var(--green-100), var(--green-50));
    border-radius: var(--radius-lg);
    margin: 0 auto 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: var(--green-500);
    transition: transform .3s;
}

.upload-zone:hover .upload-icon { transform: scale(1.1) rotate(-5deg); }

.upload-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.05rem;
    color: var(--slate-700);
    margin-bottom: .4rem;
}

.upload-sub { font-size: .85rem; color: var(--slate-400); }

.upload-formats {
    display: flex;
    gap: .5rem;
    justify-content: center;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.format-badge {
    background: white;
    border: 1.5px solid var(--slate-200);
    border-radius: var(--radius-sm);
    padding: .25rem .6rem;
    font-size: .72rem;
    font-weight: 700;
    color: var(--slate-500);
    letter-spacing: .05em;
}

/*
 * FIX UPLOAD PREVIEW:
 * - Wrapper pakai position:relative agar canvas bisa overlay tepat
 * - Gambar ditampilkan normal (object-fit:contain)
 * - Canvas di-overlay dengan position:absolute + inset:0
 * - PENTING: canvas.width/height di-set via JS sesuai rendered size,
 *   BUKAN naturalWidth/naturalHeight, agar koordinat bbox sinkron
 */
#uploadPreviewWrap {
    display: none;
    position: relative;   /* ← KUNCI: agar canvas absolute di dalam ini */
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: #0f172a;
    /* Tidak pakai aspect-ratio fixed — biarkan tinggi mengikuti gambar */
}

#uploadPreviewImg {
    display: block;
    width: 100%;
    height: auto;         /* ← tinggi mengikuti rasio asli gambar */
    object-fit: contain;
    border-radius: var(--radius-lg);
}

#uploadResultCanvas {
    position: absolute;   /* ← overlay persis di atas gambar */
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

/* ============================================================
   RESULTS PANEL
============================================================ */
.results-panel {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.panel-card {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    border: 1.5px solid var(--slate-100);
    overflow: hidden;
}

.panel-card-header {
    padding: 1.1rem 1.4rem;
    border-bottom: 1.5px solid var(--slate-100);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.panel-card-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: .95rem;
    color: var(--slate-800);
    display: flex;
    align-items: center;
    gap: .5rem;
}

.panel-card-body { padding: 1.25rem; }

.result-empty {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--slate-400);
}

.result-empty i { font-size: 2.5rem; opacity: .3; margin-bottom: .75rem; display: block; }
.result-empty p { font-size: .85rem; }

.detection-item {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: .75rem;
    border-radius: var(--radius-md);
    border: 1.5px solid transparent;
    margin-bottom: .5rem;
    transition: all .2s;
    cursor: pointer;
}

.detection-item:last-child { margin-bottom: 0; }
.detection-item:hover { background: var(--slate-50); border-color: var(--slate-200); }
.detection-item.b3    { background: #fff5f5; border-color: rgba(239,68,68,.2); }
.detection-item.nonb3 { background: var(--green-50); border-color: rgba(34,197,94,.2); }

.det-icon {
    width: 40px; height: 40px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    flex-shrink: 0;
}

.detection-item.b3    .det-icon { background: rgba(239,68,68,.15); color: var(--red-600); }
.detection-item.nonb3 .det-icon { background: rgba(34,197,94,.15); color: var(--green-600); }

.det-info { flex: 1; min-width: 0; }

.det-label {
    font-weight: 700;
    font-size: .875rem;
    color: var(--slate-800);
    margin-bottom: .2rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.det-category { font-size: .75rem; font-weight: 600; }
.detection-item.b3    .det-category { color: var(--red-600); }
.detection-item.nonb3 .det-category { color: var(--green-600); }

.det-conf { font-family: var(--font-display); font-weight: 800; font-size: .9rem; }
.detection-item.b3    .det-conf { color: var(--red-500); }
.detection-item.nonb3 .det-conf { color: var(--green-500); }

.summary-bar {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
}

.summary-box { border-radius: var(--radius-md); padding: 1rem; text-align: center; }
.summary-box.b3    { background: #fff1f2; }
.summary-box.nonb3 { background: var(--green-50); }

.summary-num {
    font-family: var(--font-display);
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: .3rem;
}

.summary-box.b3    .summary-num { color: var(--red-600); }
.summary-box.nonb3 .summary-num { color: var(--green-600); }

.summary-lbl { font-size: .75rem; font-weight: 600; }
.summary-box.b3    .summary-lbl { color: var(--red-500); }
.summary-box.nonb3 .summary-lbl { color: var(--green-600); }

.conf-bar-wrap { margin: .35rem 0 0; }

.conf-bar-track {
    height: 6px;
    background: var(--slate-100);
    border-radius: 3px;
    overflow: hidden;
    margin-top: .3rem;
}

.conf-bar-fill { height: 100%; border-radius: 3px; transition: width .6s ease; }

/* ============================================================
   TIPS CARD
============================================================ */
.tip-item {
    display: flex;
    gap: .75rem;
    align-items: flex-start;
    padding: .75rem 0;
    border-bottom: 1.5px solid var(--slate-50);
}

.tip-item:last-child { border-bottom: none; padding-bottom: 0; }

.tip-icon {
    width: 34px; height: 34px;
    border-radius: var(--radius-sm);
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
}

.tip-text { flex: 1; font-size: .8rem; color: var(--slate-600); line-height: 1.5; }
.tip-text strong { display: block; color: var(--slate-800); font-weight: 600; margin-bottom: .15rem; font-size: .82rem; }

/* ============================================================
   PROCESSING OVERLAY
============================================================ */
.processing-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15,23,42,.75);
    backdrop-filter: blur(4px);
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    z-index: 10;
    display: none;
}

.processing-overlay.show { display: flex; }

.processing-spinner {
    width: 56px; height: 56px;
    border: 4px solid rgba(34,197,94,.3);
    border-top-color: var(--green-400);
    border-radius: 50%;
    animation: spin .8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

.processing-text { color: white; font-weight: 700; font-size: .95rem; text-align: center; }
.processing-sub  { color: #94a3b8; font-size: .8rem; }

/* ============================================================
   RESPONSIVE
============================================================ */
@media (max-width: 1024px) {
    .detect-grid { grid-template-columns: 1fr; }
    .results-panel { display: grid; grid-template-columns: 1fr 1fr; }
}

@media (max-width: 640px) {
    .detect-page { padding: 1rem; }
    .results-panel { grid-template-columns: 1fr; }
    .tab-btn span { display: none; }
    .cam-actions { padding: 1rem; }
}
</style>
@endpush

@section('content')

<div class="detect-page">
    <div class="detect-inner">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-top">
                <h1 class="page-title">Deteksi Sampah</h1>
                <div class="status-chip ready" id="statusChip">
                    <span class="dot-indicator"></span>
                    <span id="statusText">Siap Digunakan</span>
                </div>
            </div>
            <p class="page-subtitle">Gunakan kamera atau unggah gambar untuk mengidentifikasi jenis sampah secara otomatis.</p>
        </div>

        <!-- Tab Bar -->
        <div class="tab-bar">
            <button class="tab-btn active" id="tabCamera" onclick="switchTab('camera')">
                <i class="fas fa-camera"></i>
                <span>Kamera Langsung</span>
            </button>
            <button class="tab-btn" id="tabUpload" onclick="switchTab('upload')">
                <i class="fas fa-image"></i>
                <span>Upload Foto</span>
            </button>
        </div>

        <!-- Detection Grid -->
        <div class="detect-grid">

            <!-- LEFT -->
            <div>

                <!-- CAMERA PANEL -->
                <div id="cameraPanel">
                    <div class="camera-card">
                        <div class="camera-viewport">
                            <video id="videoFeed" autoplay playsinline muted></video>
                            <!-- Snapshot canvas: menampilkan frame yang di-capture (di belakang bbox canvas) -->
            <canvas id="snapshotCanvas" style="position:absolute;inset:0;width:100%;height:100%;display:none;object-fit:cover"></canvas>
            <canvas id="detectionCanvas"></canvas>

                            <div class="camera-placeholder" id="cameraPlaceholder">
                                <div class="camera-placeholder-icon"><i class="fas fa-camera"></i></div>
                                <h3>Kamera Belum Aktif</h3>
                                <p>Tekan tombol "Aktifkan Kamera" di bawah untuk memulai deteksi</p>
                            </div>

                            <div class="scan-overlay" id="scanOverlay">
                                <div class="scan-corner tl"></div>
                                <div class="scan-corner tr"></div>
                                <div class="scan-corner bl"></div>
                                <div class="scan-corner br"></div>
                                <div class="scan-line-anim"></div>
                            </div>

                            <div class="cam-overlay-info" id="camInfo">
                                <div class="cam-info-item">FPS: <span id="fpsCounter">0</span></div>
                                <div class="cam-info-item">Obj: <span id="objCounter">0</span></div>
                                <div class="cam-info-item" style="color:#86efac">● LIVE</div>
                            </div>

                            <div class="cam-controls" id="camControls">
                                <button class="cam-ctrl-btn" onclick="flipCamera()" title="Ganti Kamera">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button class="cam-ctrl-btn" onclick="detectOnce()" title="Ambil & Deteksi">
                                    <i class="fas fa-circle"></i>
                                </button>
                            </div>

                            <div class="processing-overlay" id="processingOverlay">
                                <div class="processing-spinner"></div>
                                <div class="processing-text">Memproses Gambar...</div>
                                <div class="processing-sub">Model YOLO sedang menganalisis</div>
                            </div>
                        </div>

                        <div class="cam-actions">
                            <button class="btn-detect" id="btnCamera" onclick="toggleCamera()">
                                <i class="fas fa-camera" id="btnCameraIcon"></i>
                                <span id="btnCameraText">Aktifkan Kamera</span>
                            </button>
                            <button class="btn-icon-only" id="btnDetect" title="Deteksi Sekali" onclick="detectOnce()" disabled>
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn-icon-only" id="btnAutoDetect" title="Lanjut / Auto Deteksi" onclick="handlePlayBtn()" disabled>
                                <i class="fas fa-play"></i>
                            </button>
                            <button class="btn-icon-only" title="Simpan Hasil" onclick="saveResult()">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- UPLOAD PANEL -->
                <div id="uploadPanel" style="display:none">
                    <div class="camera-card">
                        <div style="padding:1.5rem">

                            <div class="upload-zone" id="uploadZone">
                                <input type="file" id="fileInput" accept="image/*" onchange="handleFileUpload(event)">
                                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <div class="upload-title">Seret & Lepas Gambar</div>
                                <p class="upload-sub">atau klik untuk memilih file dari perangkat Anda</p>
                                <div class="upload-formats">
                                    <span class="format-badge">JPG</span>
                                    <span class="format-badge">PNG</span>
                                    <span class="format-badge">WEBP</span>
                                    <span class="format-badge">Max 10MB</span>
                                </div>
                            </div>

                            <!-- Preview wrap: position:relative supaya canvas overlay di dalam sini -->
                            <div id="uploadPreviewWrap">
                                <img id="uploadPreviewImg" src="" alt="Preview">
                                <canvas id="uploadResultCanvas"></canvas>
                                <div class="processing-overlay" id="uploadProcessing">
                                    <div class="processing-spinner"></div>
                                    <div class="processing-text">Menganalisis Gambar...</div>
                                    <div class="processing-sub">Mohon tunggu sebentar</div>
                                </div>
                            </div>
                        </div>

                        <div class="cam-actions" id="uploadActions" style="display:none">
                            <button class="btn-detect" onclick="runUploadDetection()">
                                <i class="fas fa-microscope"></i>
                                Deteksi Sekarang
                            </button>
                            <button class="btn-icon-only" title="Ganti Foto" onclick="resetUpload()">
                                <i class="fas fa-redo"></i>
                            </button>
                            <button class="btn-icon-only" title="Simpan" onclick="saveUploadResult()">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div><!-- /left -->

            <!-- RIGHT: Results Panel -->
            <div class="results-panel">

                <div class="panel-card" id="summaryCard">
                    <div class="panel-card-header">
                        <div class="panel-card-title">
                            <i class="fas fa-chart-pie" style="color:var(--green-500)"></i>
                            Ringkasan Deteksi
                        </div>
                        <button onclick="clearResults()" style="background:none;border:none;cursor:pointer;color:var(--slate-400);font-size:.78rem;font-weight:600;display:flex;align-items:center;gap:.3rem" title="Bersihkan">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <div class="panel-card-body">
                        <div class="summary-bar">
                            <div class="summary-box b3">
                                <div class="summary-num" id="sumB3">0</div>
                                <div class="summary-lbl">⚠️ Sampah B3</div>
                            </div>
                            <div class="summary-box nonb3">
                                <div class="summary-num" id="sumNonB3">0</div>
                                <div class="summary-lbl">✅ Non-B3</div>
                            </div>
                        </div>
                        <div style="margin-top:1rem">
                            <div class="conf-bar-wrap">
                                <div style="display:flex;justify-content:space-between;font-size:.75rem;font-weight:600;color:var(--slate-500)">
                                    <span>Avg Kepercayaan B3</span>
                                    <span id="avgConfB3">—</span>
                                </div>
                                <div class="conf-bar-track">
                                    <div class="conf-bar-fill" id="barB3" style="width:0%;background:linear-gradient(90deg,var(--red-400),var(--red-500))"></div>
                                </div>
                            </div>
                            <div class="conf-bar-wrap" style="margin-top:.6rem">
                                <div style="display:flex;justify-content:space-between;font-size:.75rem;font-weight:600;color:var(--slate-500)">
                                    <span>Avg Kepercayaan Non-B3</span>
                                    <span id="avgConfNonB3">—</span>
                                </div>
                                <div class="conf-bar-track">
                                    <div class="conf-bar-fill" id="barNonB3" style="width:0%;background:linear-gradient(90deg,var(--green-400),var(--lime-400))"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-card">
                    <div class="panel-card-header">
                        <div class="panel-card-title">
                            <i class="fas fa-list-ul" style="color:var(--sky-500)"></i>
                            Objek Terdeteksi
                        </div>
                        <span style="font-size:.75rem;color:var(--slate-400)" id="detCount">0 item</span>
                    </div>
                    <div class="panel-card-body" id="detectionList">
                        <div class="result-empty" id="emptyState">
                            <i class="fas fa-search-location"></i>
                            <p>Belum ada objek terdeteksi.<br>Aktifkan kamera atau upload foto.</p>
                        </div>
                    </div>
                </div>

                <div class="panel-card">
                    <div class="panel-card-header">
                        <div class="panel-card-title">
                            <i class="fas fa-lightbulb" style="color:var(--amber-500)"></i>
                            Tips Penanganan
                        </div>
                    </div>
                    <div class="panel-card-body">
                        <div class="tip-item">
                            <div class="tip-icon"><i class="fas fa-biohazard" style="color:var(--red-500)"></i></div>
                            <div class="tip-text">
                                <strong>Sampah B3 Terdeteksi?</strong>
                                Jangan buang ke tempat sampah biasa. Bawa ke TPS3R atau fasilitas pengolahan B3 terdekat.
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon"><i class="fas fa-recycle"></i></div>
                            <div class="tip-text">
                                <strong>Sampah Daur Ulang</strong>
                                Bersihkan kemasan sebelum membuang. Pisahkan plastik, kertas, dan logam.
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon" style="background:#eff6ff;color:#3b82f6"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="tip-text">
                                <strong>Lokasi TPS Terdekat</strong>
                                Gunakan fitur lokasi untuk menemukan tempat pembuangan resmi di sekitar Anda.
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /results -->
        </div><!-- /grid -->
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ============================================================
   STATE
============================================================ */
const API_DETECT_URL = '{{ route("api.detect") }}';
const CSRF_TOKEN     = document.querySelector('meta[name=csrf-token]')?.content || '';

let stream         = null;
let facingMode     = 'environment';
let autoDetect     = false;
let autoInterval   = null;
let lastDetections = [];
let fpsCount       = 0;
let fpsTimer       = null;

/* ============================================================
   TAB
============================================================ */
function switchTab(tab) {
    const isCamera = tab === 'camera';
    document.getElementById('cameraPanel').style.display = isCamera ? '' : 'none';
    document.getElementById('uploadPanel').style.display  = isCamera ? 'none' : '';
    document.getElementById('tabCamera').classList.toggle('active',  isCamera);
    document.getElementById('tabUpload').classList.toggle('active', !isCamera);
    if (!isCamera && stream) stopCamera();
}

/* ============================================================
   CAMERA
============================================================ */
async function toggleCamera() {
    stream ? stopCamera() : await startCamera();
}

async function startCamera() {
    try {
        setStatus('connecting', 'Menghubungkan...');
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode, width: { ideal: 1280 }, height: { ideal: 720 } },
            audio: false
        });
        const video = document.getElementById('videoFeed');
        video.srcObject = stream;
        video.style.display = 'block';
        document.getElementById('cameraPlaceholder').style.display = 'none';
        document.getElementById('scanOverlay').classList.add('active');
        document.getElementById('camInfo').classList.add('visible');
        document.getElementById('camControls').classList.add('visible');
        document.getElementById('btnCameraIcon').className = 'fas fa-stop';
        document.getElementById('btnCameraText').textContent = 'Matikan Kamera';
        document.getElementById('btnDetect').disabled    = false;
        document.getElementById('btnAutoDetect').disabled = false;
        setStatus('ready', 'Kamera Aktif');
        startFpsCounter();
        showToast('Kamera Aktif', 'Siap untuk deteksi', 'success');
    } catch (err) {
        setStatus('error', 'Kamera Gagal');
        showToast('Kamera Tidak Bisa Diakses', err.message || 'Periksa izin kamera', 'error');
    }
}

function stopCamera() {
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    if (autoDetect) toggleAutoDetect();

    const video = document.getElementById('videoFeed');
    video.srcObject = null;
    video.style.display = 'none';
    document.getElementById('snapshotCanvas').style.display = 'none';
    document.getElementById('cameraPlaceholder').style.display = '';
    document.getElementById('scanOverlay').classList.remove('active');
    document.getElementById('camInfo').classList.remove('visible');
    document.getElementById('camControls').classList.remove('visible');
    document.getElementById('btnCameraIcon').className = 'fas fa-camera';
    document.getElementById('btnCameraText').textContent = 'Aktifkan Kamera';
    document.getElementById('btnDetect').disabled    = true;
    document.getElementById('btnAutoDetect').disabled = true;
    setStatus('ready', 'Siap Digunakan');
    stopFpsCounter();

    // Bersihkan bbox & snapshot saat kamera dimatikan
    const dc = document.getElementById('detectionCanvas');
    dc.getContext('2d').clearRect(0, 0, dc.width, dc.height);
    clearResults();
}

async function flipCamera() {
    facingMode = facingMode === 'environment' ? 'user' : 'environment';
    if (stream) { stopCamera(); await startCamera(); }
}

/* ============================================================
   DETEKSI KAMERA
   FIX: canvas.width/height di-set ke ukuran RENDERED viewport,
        bukan ukuran asli video, agar bbox ter-scale dengan benar.
============================================================ */
async function detectOnce() {
    const video = document.getElementById('videoFeed');
    if (!stream || !video.videoWidth) return;

    showProcessing(true);
    setStatus('detecting', 'Mendeteksi...');

    try {
        /* 1. Capture frame ke tmpCanvas (resolusi asli video) */
        const vw = video.videoWidth;
        const vh = video.videoHeight;
        const tmpCanvas = document.createElement('canvas');
        tmpCanvas.width  = vw;
        tmpCanvas.height = vh;
        tmpCanvas.getContext('2d').drawImage(video, 0, 0);

        /*
         * FIX KAMERA — Tampilkan snapshot frame (freeze view):
         * Salin tmpCanvas → snapshotCanvas yang sudah di-overlay di viewport.
         * snapshotCanvas pakai CSS width:100%/height:100% + object-fit mirip video,
         * tapi kita gambar langsung ke piksel canvas sesuai ukuran container agar presisi.
         */
        const snap     = document.getElementById('snapshotCanvas');
        const viewport = snap.parentElement.getBoundingClientRect();
        snap.width  = viewport.width;
        snap.height = viewport.height;

        // Hitung offset contain (sama seperti renderDetections)
        const containerRatio = viewport.width / viewport.height;
        const sourceRatio    = vw / vh;
        let rW, rH, oX = 0, oY = 0;
        if (sourceRatio > containerRatio) {
            rW = viewport.width; rH = viewport.width / sourceRatio;
            oY = (viewport.height - rH) / 2;
        } else {
            rH = viewport.height; rW = viewport.height * sourceRatio;
            oX = (viewport.width - rW) / 2;
        }
        snap.getContext('2d').drawImage(tmpCanvas, oX, oY, rW, rH);
        snap.style.display = 'block';    // tampilkan snapshot
        video.style.display = 'none';   // sembunyikan live video

        const blob = await new Promise(res => tmpCanvas.toBlob(res, 'image/jpeg', 0.85));

        /* 2. Kirim ke API */
        const detections = await sendToAPI(blob);

        /* 3. Render bbox di atas snapshot */
        const canvas = document.getElementById('detectionCanvas');
        renderDetections(detections, canvas, vw, vh);

        updateResultsPanel(detections);
        fpsCount++;

    } catch (e) {
        showToast('Deteksi Gagal', e.message, 'error');
        setStatus('error', 'Deteksi Gagal');
        resumeVideo();   // kalau error, kembali ke live feed
    } finally {
        showProcessing(false);
        if (stream) setStatus('done', 'Deteksi Selesai — Tekan ▶ untuk lanjut');
    }
}

/* Kembali ke live feed setelah melihat hasil snapshot */
function resumeVideo() {
    const video = document.getElementById('videoFeed');
    const snap  = document.getElementById('snapshotCanvas');
    snap.style.display  = 'none';
    video.style.display = 'block';
    // Bersihkan bbox
    const canvas = document.getElementById('detectionCanvas');
    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
    if (stream) setStatus('ready', 'Kamera Aktif');
}

/* Tombol ▶ berfungsi ganda:
   - Saat snapshot tampil → resume ke live feed
   - Saat live feed aktif → toggle auto deteksi */
function handlePlayBtn() {
    const snap = document.getElementById('snapshotCanvas');
    if (snap.style.display !== 'none') {
        resumeVideo();   // kembali ke live feed
    } else {
        toggleAutoDetect();
    }
}

function toggleAutoDetect() {
    autoDetect = !autoDetect;
    const btn = document.getElementById('btnAutoDetect');
    if (autoDetect) {
        btn.style.cssText = 'background:var(--green-100);border-color:var(--green-300);color:var(--green-700)';
        btn.innerHTML = '<i class="fas fa-pause"></i>';
        autoInterval = setInterval(detectOnce, 2500);
        showToast('Auto Deteksi Aktif', 'Mendeteksi setiap 2.5 detik', 'success');
    } else {
        btn.style.cssText = '';
        btn.innerHTML = '<i class="fas fa-play"></i>';
        clearInterval(autoInterval);
    }
}

/* ============================================================
   API
============================================================ */
async function sendToAPI(blob) {
    const form = new FormData();
    form.append('image', blob, 'frame.jpg');

    const res = await fetch(API_DETECT_URL, {
        method: 'POST',
        body: form,
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        }
    });

    if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        throw new Error(err.message || `Server error: ${res.status}`);
    }

    const data = await res.json();
    if (!data.success) throw new Error(data.message || 'Deteksi gagal');
    return data.detections || [];
}

/* ============================================================
   RENDER BOUNDING BOXES
   FIX UTAMA:
   - canvas.width/height di-set ke ukuran RENDERED elemen (getBoundingClientRect),
     bukan naturalWidth/Height — agar koordinat piksel canvas = piksel layar.
   - Scale bbox dari koordinat asli (vw×vh) ke koordinat canvas rendered.
============================================================ */
function renderDetections(detections, canvas, sourceW, sourceH) {
    /* Ukuran canvas = ukuran elemen di layar (CSS pixels) */
    const rect    = canvas.parentElement.getBoundingClientRect();
    canvas.width  = rect.width;
    canvas.height = rect.height;

    const ctx    = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (!detections.length) return;

    /*
     * Hitung scale:
     * Gambar/video mungkin di-fit (object-fit:contain/cover) di dalam container.
     * Kita perlu tahu berapa piksel rendered dari sourceW×sourceH di dalam rect.
     */
    const containerRatio = rect.width / rect.height;
    const sourceRatio    = sourceW / sourceH;

    let renderedW, renderedH, offsetX = 0, offsetY = 0;

    if (sourceRatio > containerRatio) {
        /* Gambar lebih lebar → fit by width, ada letterbox atas-bawah */
        renderedW = rect.width;
        renderedH = rect.width / sourceRatio;
        offsetY   = (rect.height - renderedH) / 2;
    } else {
        /* Gambar lebih tinggi → fit by height, ada pillarbox kiri-kanan */
        renderedH = rect.height;
        renderedW = rect.height * sourceRatio;
        offsetX   = (rect.width - renderedW) / 2;
    }

    const scaleX = renderedW / sourceW;
    const scaleY = renderedH / sourceH;

    detections.forEach(d => {
        const isB3   = d.category === 'B3';
        const color  = isB3 ? '#ef4444' : '#22c55e';
        const shadow = isB3 ? 'rgba(239,68,68,.4)' : 'rgba(34,197,94,.4)';

        const x = offsetX + d.bbox[0] * scaleX;
        const y = offsetY + d.bbox[1] * scaleY;
        const w = (d.bbox[2] - d.bbox[0]) * scaleX;
        const h = (d.bbox[3] - d.bbox[1]) * scaleY;

        /* Box */
        ctx.strokeStyle = color;
        ctx.lineWidth   = 2.5;
        ctx.shadowColor = shadow;
        ctx.shadowBlur  = 8;
        ctx.strokeRect(x, y, w, h);
        ctx.shadowBlur  = 0;

        /* Corner accents */
        const cs = 14;
        ctx.lineWidth = 3.5;
        [[x,y],[x+w,y],[x,y+h],[x+w,y+h]].forEach(([px,py], i) => {
            ctx.beginPath();
            const dx = i % 2 === 0 ? cs : -cs;
            const dy = i < 2 ? cs : -cs;
            ctx.moveTo(px, py + dy);
            ctx.lineTo(px, py);
            ctx.lineTo(px + dx, py);
            ctx.stroke();
        });

        /* Label */
        ctx.font = 'bold 12px "Space Grotesk", sans-serif';
        const label = `${d.label} ${Math.round(d.confidence * 100)}%`;
        const tw    = ctx.measureText(label).width;
        const lh    = 20;
        const lx    = x;
        const ly    = y > lh + 4 ? y - lh - 4 : y + 4;

        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.roundRect(lx, ly, tw + 16, lh, 4);
        ctx.fill();

        ctx.fillStyle = 'white';
        ctx.fillText(label, lx + 8, ly + 14);
    });
}

/* ============================================================
   UPLOAD
============================================================ */
function handleFileUpload(e) {
    const file = e.target.files[0];
    if (!file) return;

    const wrap   = document.getElementById('uploadPreviewWrap');
    const img    = document.getElementById('uploadPreviewImg');
    const canvas = document.getElementById('uploadResultCanvas');

    // ① Tampilkan wrapper DULU sebelum set src
    //    Tanpa ini, img.offsetWidth = 0 karena elemen masih hidden
    document.getElementById('uploadZone').style.display  = 'none';
    wrap.style.display = 'block';
    document.getElementById('uploadActions').style.display = '';

    // ② Bersihkan canvas & result lama
    canvas.width  = 10; canvas.height = 10;
    canvas.getContext('2d').clearRect(0, 0, 10, 10);
    clearResults();

    // ③ Set src via object URL (lebih cepat dari FileReader + tidak ada isu cache)
    const objectURL = URL.createObjectURL(file);

    img.onload = () => {
        // Sekarang wrapper sudah visible → offsetWidth pasti > 0
        canvas.width  = img.offsetWidth;
        canvas.height = img.offsetHeight;
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        URL.revokeObjectURL(objectURL);   // bebaskan memori
    };

    img.src = objectURL;
}

async function runUploadDetection() {
    const file = document.getElementById('fileInput').files[0];
    if (!file) return;

    document.getElementById('uploadProcessing').classList.add('show');
    setStatus('detecting', 'Menganalisis...');

    try {
        const detections = await sendToAPI(file);

        const img    = document.getElementById('uploadPreviewImg');
        const canvas = document.getElementById('uploadResultCanvas');

        /*
         * FIX UPLOAD:
         * Gunakan img.naturalWidth/naturalHeight sebagai koordinat sumber bbox,
         * tapi canvas.width/height = ukuran rendered (offsetWidth/offsetHeight)
         * agar bbox muncul tepat di atas gambar yang ditampilkan.
         */
        renderDetections(detections, canvas, img.naturalWidth, img.naturalHeight);

        updateResultsPanel(detections);
        setStatus('done', 'Selesai');
        showToast('Deteksi Selesai', `${detections.length} objek ditemukan`, 'success');

    } catch (err) {
        showToast('Gagal', err.message, 'error');
        setStatus('error', 'Gagal');
    } finally {
        document.getElementById('uploadProcessing').classList.remove('show');
    }
}

function resetUpload() {
    document.getElementById('uploadZone').style.display         = '';
    document.getElementById('uploadPreviewWrap').style.display  = 'none';
    document.getElementById('uploadActions').style.display      = 'none';
    document.getElementById('fileInput').value = '';
    clearResults();
    setStatus('ready', 'Siap Digunakan');
}

/* Drag & drop */
const zone = document.getElementById('uploadZone');
zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('dragover'); });
zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('fileInput').files = dt.files;
        handleFileUpload({ target: { files: dt.files } });
    }
});

/* ============================================================
   RESULTS PANEL
============================================================ */
function updateResultsPanel(detections) {
    lastDetections = detections;
    const list  = document.getElementById('detectionList');
    const empty = document.getElementById('emptyState');

    document.getElementById('detCount').textContent = `${detections.length} item`;
    document.getElementById('objCounter').textContent = detections.length;

    if (detections.length === 0) {
        empty.style.display = '';
        ['sumB3','sumNonB3'].forEach(id => document.getElementById(id).textContent = '0');
        ['barB3','barNonB3'].forEach(id => document.getElementById(id).style.width = '0%');
        ['avgConfB3','avgConfNonB3'].forEach(id => document.getElementById(id).textContent = '—');
        return;
    }

    empty.style.display = 'none';
    list.querySelectorAll('.detection-item').forEach(el => el.remove());

    const b3Items = [], nonB3Items = [];

    detections.forEach(d => {
        const isB3 = d.category === 'B3';
        (isB3 ? b3Items : nonB3Items).push(d);

        const icon = isB3 ? 'biohazard' : 'recycle';
        const el   = document.createElement('div');
        el.className = `detection-item ${isB3 ? 'b3' : 'nonb3'}`;
        el.innerHTML = `
            <div class="det-icon"><i class="fas fa-${icon}"></i></div>
            <div class="det-info">
                <div class="det-label">${d.label}</div>
                <div class="det-category">${isB3 ? '⚠️ B3 — Berbahaya' : '✅ Non-B3 — Aman'}</div>
            </div>
            <div class="det-conf">${Math.round(d.confidence * 100)}%</div>`;
        list.appendChild(el);
    });

    document.getElementById('sumB3').textContent    = b3Items.length;
    document.getElementById('sumNonB3').textContent = nonB3Items.length;

    const avgB3    = b3Items.length    ? b3Items.reduce((s,d) => s + d.confidence, 0)    / b3Items.length    : 0;
    const avgNonB3 = nonB3Items.length ? nonB3Items.reduce((s,d) => s + d.confidence, 0) / nonB3Items.length : 0;

    document.getElementById('barB3').style.width        = `${avgB3 * 100}%`;
    document.getElementById('barNonB3').style.width     = `${avgNonB3 * 100}%`;
    document.getElementById('avgConfB3').textContent    = avgB3    ? `${Math.round(avgB3 * 100)}%`    : '—';
    document.getElementById('avgConfNonB3').textContent = avgNonB3 ? `${Math.round(avgNonB3 * 100)}%` : '—';

    if (b3Items.length > 0) showToast('B3 Terdeteksi!', `${b3Items.length} item berbahaya ditemukan`, 'warning');
}

function clearResults() {
    lastDetections = [];
    updateResultsPanel([]);
    ['detectionCanvas','uploadResultCanvas'].forEach(id => {
        const c = document.getElementById(id);
        if (c) c.getContext('2d').clearRect(0, 0, c.width, c.height);
    });
}

/* ============================================================
   HELPERS
============================================================ */
function setStatus(type, text) {
    document.getElementById('statusChip').className = `status-chip ${type}`;
    document.getElementById('statusText').textContent = text;
}

function showProcessing(show) {
    document.getElementById('processingOverlay').classList.toggle('show', show);
}

function startFpsCounter() {
    fpsCount = 0;
    fpsTimer = setInterval(() => {
        document.getElementById('fpsCounter').textContent = fpsCount;
        fpsCount = 0;
    }, 1000);
}

function stopFpsCounter() {
    clearInterval(fpsTimer);
    document.getElementById('fpsCounter').textContent = '0';
}

function saveResult() {
    const canvas = document.getElementById('detectionCanvas');
    const a = document.createElement('a');
    a.download = `wasteguard_${Date.now()}.png`;
    a.href = canvas.toDataURL();
    a.click();
    showToast('Tersimpan', 'Gambar hasil deteksi diunduh', 'success');
}

function saveUploadResult() {
    /* Merge gambar + canvas overlay jadi satu image yang bisa didownload */
    const img    = document.getElementById('uploadPreviewImg');
    const canvas = document.getElementById('uploadResultCanvas');
    const merged = document.createElement('canvas');
    merged.width  = img.naturalWidth;
    merged.height = img.naturalHeight;
    const ctx = merged.getContext('2d');
    ctx.drawImage(img, 0, 0, img.naturalWidth, img.naturalHeight);
    ctx.drawImage(canvas, 0, 0, img.naturalWidth, img.naturalHeight);
    const a = document.createElement('a');
    a.download = `wasteguard_upload_${Date.now()}.png`;
    a.href = merged.toDataURL();
    a.click();
    showToast('Tersimpan', 'Gambar hasil deteksi diunduh', 'success');
}

/* ============================================================
   INIT — pastikan semua canvas & state bersih saat page load
============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    ['detectionCanvas', 'uploadResultCanvas', 'snapshotCanvas'].forEach(id => {
        const c = document.getElementById(id);
        if (c) { c.width = 1; c.height = 1; }
    });
    // Reset upload panel ke state awal
    document.getElementById('uploadPreviewWrap').style.display  = 'none';
    document.getElementById('uploadActions').style.display      = 'none';
    document.getElementById('uploadZone').style.display         = '';
});
</script>
@endpush