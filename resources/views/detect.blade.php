@extends('layouts.app')

@section('title', 'Deteksi Sampah — WasteGuard')

@push('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>
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
.page-header { margin-bottom: 1.75rem; }
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
.page-subtitle { color: var(--slate-500); font-size: .95rem; }

.status-chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .3rem .85rem;
    border-radius: var(--radius-full);
    font-size: .78rem;
    font-weight: 700;
}
.status-chip.ready     { background: var(--green-100); color: var(--green-700); }
.status-chip.detecting { background: #fef9c3; color: #92400e; }
.status-chip.done      { background: #eff6ff; color: #1d4ed8; }
.status-chip.error     { background: #fef2f2; color: var(--red-700); }

.dot-indicator {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: currentColor;
    animation: blinkDot 1.5s infinite;
}
@keyframes blinkDot { 0%,100%{opacity:1} 50%{opacity:.3} }

/* ============================================================
   MAIN GRID
============================================================ */
.detect-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.5rem;
}

/* ============================================================
   TAB BAR
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
.tab-btn:not(.active):hover { background: var(--slate-50); color: var(--slate-700); }

/* ============================================================
   CAMERA CARD
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
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    display: none;
}
/* ✅ FIX: detectionCanvas menjadi full-cover layer di atas video */
#detectionCanvas {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    pointer-events: none;
    z-index: 2;
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
    z-index: 1;
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
.scan-overlay { position: absolute; inset: 0; pointer-events: none; display: none; z-index: 3; }
.scan-overlay.active { display: block; }
.scan-corner {
    position: absolute;
    width: 28px; height: 28px;
    border-color: var(--green-400);
    border-style: solid;
    opacity: .8;
}
.scan-corner.tl { top:16px;left:16px;    border-width:3px 0 0 3px; border-radius:4px 0 0 0; }
.scan-corner.tr { top:16px;right:16px;   border-width:3px 3px 0 0; border-radius:0 4px 0 0; }
.scan-corner.bl { bottom:16px;left:16px; border-width:0 0 3px 3px; border-radius:0 0 0 4px; }
.scan-corner.br { bottom:16px;right:16px;border-width:0 3px 3px 0; border-radius:0 0 4px 0; }
.scan-line-anim {
    position: absolute;
    left:16px; right:16px;
    height: 2px;
    background: linear-gradient(90deg,transparent,var(--green-400),var(--lime-400),var(--green-400),transparent);
    animation: scanMove 2s ease-in-out infinite;
    top: 16px;
}
@keyframes scanMove { 0%{top:16px;opacity:1} 100%{top:calc(100% - 16px);opacity:.4} }

/* overlay info */
.cam-overlay-info {
    position: absolute;
    top: 12px; left: 12px;
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(8px);
    border-radius: var(--radius-sm);
    padding: .35rem .7rem;
    display: none;
    gap: .75rem;
    z-index: 4;
}
.cam-overlay-info.visible { display: flex; }
.cam-info-item { font-size:.72rem; color:rgba(255,255,255,.85); font-weight:600; font-family:monospace; }
.cam-info-item span { color: var(--lime-400); }

/* location overlay badge */
.location-overlay {
    position: absolute;
    bottom: 12px; left: 12px;
    background: rgba(0,0,0,.6);
    backdrop-filter: blur(8px);
    border-radius: var(--radius-sm);
    padding: .4rem .75rem;
    display: none;
    align-items: center;
    gap: .5rem;
    font-size: .72rem;
    color: rgba(255,255,255,.9);
    font-weight: 600;
    max-width: calc(100% - 100px);
    z-index: 4;
}
.location-overlay.visible { display: flex; }
.location-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--green-400);
    flex-shrink: 0;
    animation: blinkDot 1.5s infinite;
}
.location-overlay.searching .location-dot { background: var(--amber-400); }
.location-overlay.error-loc .location-dot { background: var(--red-400); animation: none; }

/* cam controls */
.cam-controls {
    position: absolute;
    bottom: 12px; right: 12px;
    display: none;
    flex-direction: column;
    gap: .5rem;
    z-index: 4;
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

/* ✅ FIX: flash effect saat foto diambil */
.camera-flash {
    position: absolute;
    inset: 0;
    background: white;
    opacity: 0;
    pointer-events: none;
    z-index: 10;
    transition: opacity .08s ease-out;
}
.camera-flash.active {
    opacity: 0.85;
}

/* processing overlay */
.processing-overlay {
    position: absolute; inset: 0;
    background: rgba(15,23,42,.75);
    backdrop-filter: blur(4px);
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    z-index: 10;
}
.processing-overlay.show { display: flex; }
.processing-spinner {
    width: 56px; height: 56px;
    border: 4px solid rgba(34,197,94,.3);
    border-top-color: var(--green-400);
    border-radius: 50%;
    animation: spin .8s linear infinite;
}
@keyframes spin { to{transform:rotate(360deg)} }
.processing-text  { color:white; font-weight:700; font-size:.95rem; text-align:center; }
.processing-sub   { color:#94a3b8; font-size:.8rem; }

/* cam actions bar */
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
.btn-detect:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 8px 24px rgba(34,197,94,.45); }
.btn-detect:disabled { opacity:.6; cursor:not-allowed; transform:none; }
.btn-detect.loading::after {
    content:'';
    position:absolute; inset:0;
    background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,.2) 50%,transparent 100%);
    background-size:200%;
    animation:shimmer .8s linear infinite;
}
@keyframes shimmer { from{background-position:-200% 0} to{background-position:200% 0} }

.btn-icon-only {
    width:44px; height:44px;
    border-radius:50%;
    background:var(--slate-100);
    border:2px solid var(--slate-200);
    color:var(--slate-600);
    font-size:.9rem;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
    transition:all .2s;
}
.btn-icon-only:hover { background:var(--green-100); border-color:var(--green-300); color:var(--green-700); }
.btn-icon-only.active { background:var(--green-500); border-color:var(--green-500); color:white; }

/* ============================================================
   UPLOAD PANEL
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
.upload-zone:hover,.upload-zone.dragover { border-color:var(--green-400); background:var(--green-50); }
.upload-zone input[type=file] { position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%; }
.upload-icon {
    width:72px; height:72px;
    background:linear-gradient(135deg,var(--green-100),var(--green-50));
    border-radius:var(--radius-lg);
    margin:0 auto 1.25rem;
    display:flex; align-items:center; justify-content:center;
    font-size:1.75rem; color:var(--green-500);
    transition:transform .3s;
}
.upload-zone:hover .upload-icon { transform:scale(1.1) rotate(-5deg); }
.upload-title { font-family:var(--font-display);font-weight:700;font-size:1.05rem;color:var(--slate-700);margin-bottom:.4rem; }
.upload-sub   { font-size:.85rem;color:var(--slate-400); }
.upload-formats { display:flex;gap:.5rem;justify-content:center;margin-top:1rem;flex-wrap:wrap; }
.format-badge { background:white;border:1.5px solid var(--slate-200);border-radius:var(--radius-sm);padding:.25rem .6rem;font-size:.72rem;font-weight:700;color:var(--slate-500);letter-spacing:.05em; }

/* ✅ FIX: Upload preview wrapper — pakai min-height, bukan aspect-ratio */
#uploadPreviewWrap {
    display: none;
    position: relative;
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: #0f172a;
    min-height: 300px;
    max-height: 520px;
}
/* ✅ FIX: display block untuk hilangkan gap bawah gambar (inline baseline gap) */
#uploadPreviewImg {
    width: 100%;
    height: auto;
    max-height: 520px;
    object-fit: contain;
    display: block;
}
#uploadResultCanvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

/* ============================================================
   RESULTS PANEL
============================================================ */
.results-panel { display:flex;flex-direction:column;gap:1rem; }
.panel-card {
    background:white;
    border-radius:var(--radius-xl);
    box-shadow:var(--shadow-sm);
    border:1.5px solid var(--slate-100);
    overflow:hidden;
}
.panel-card-header {
    padding:1.1rem 1.4rem;
    border-bottom:1.5px solid var(--slate-100);
    display:flex;align-items:center;justify-content:space-between;
}
.panel-card-title { font-family:var(--font-display);font-weight:700;font-size:.95rem;color:var(--slate-800);display:flex;align-items:center;gap:.5rem; }
.panel-card-body { padding:1.25rem; }

/* detection items */
.result-empty { text-align:center;padding:2rem 1rem;color:var(--slate-400); }
.result-empty i { font-size:2.5rem;opacity:.3;margin-bottom:.75rem;display:block; }
.result-empty p { font-size:.85rem; }

.detection-item {
    display:flex;align-items:center;gap:.85rem;
    padding:.75rem;border-radius:var(--radius-md);border:1.5px solid transparent;
    margin-bottom:.5rem;transition:all .2s;cursor:pointer;
}
.detection-item:last-child { margin-bottom:0; }
.detection-item:hover { background:var(--slate-50);border-color:var(--slate-200); }
.detection-item.b3    { background:#fff5f5;border-color:rgba(239,68,68,.2); }
.detection-item.nonb3 { background:var(--green-50);border-color:rgba(34,197,94,.2); }
.det-icon { width:40px;height:40px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0; }
.detection-item.b3    .det-icon { background:rgba(239,68,68,.15);color:var(--red-600); }
.detection-item.nonb3 .det-icon { background:rgba(34,197,94,.15);color:var(--green-600); }
.det-info { flex:1;min-width:0; }
.det-label { font-weight:700;font-size:.875rem;color:var(--slate-800);margin-bottom:.2rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.det-category { font-size:.75rem;font-weight:600; }
.detection-item.b3    .det-category { color:var(--red-600); }
.detection-item.nonb3 .det-category { color:var(--green-600); }
.det-conf { font-family:var(--font-display);font-weight:800;font-size:.9rem; }
.detection-item.b3    .det-conf { color:var(--red-500); }
.detection-item.nonb3 .det-conf { color:var(--green-500); }

/* summary */
.summary-bar { display:grid;grid-template-columns:1fr 1fr;gap:.75rem; }
.summary-box { border-radius:var(--radius-md);padding:1rem;text-align:center; }
.summary-box.b3    { background:#fff1f2; }
.summary-box.nonb3 { background:var(--green-50); }
.summary-num { font-family:var(--font-display);font-size:1.8rem;font-weight:800;line-height:1;margin-bottom:.3rem; }
.summary-box.b3    .summary-num { color:var(--red-600); }
.summary-box.nonb3 .summary-num { color:var(--green-600); }
.summary-lbl { font-size:.75rem;font-weight:600; }
.summary-box.b3    .summary-lbl { color:var(--red-500); }
.summary-box.nonb3 .summary-lbl { color:var(--green-600); }

.conf-bar-wrap { margin:.35rem 0 0; }
.conf-bar-track { height:6px;background:var(--slate-100);border-radius:3px;overflow:hidden;margin-top:.3rem; }
.conf-bar-fill  { height:100%;border-radius:3px;transition:width .6s ease; }

/* ============================================================
   MAP SECTION
============================================================ */
.map-section {
    margin-top: 1.5rem;
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    border: 1.5px solid var(--slate-100);
    overflow: hidden;
}
.map-section-header {
    padding: 1.1rem 1.5rem;
    border-bottom: 1.5px solid var(--slate-100);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .75rem;
}
.map-section-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1rem;
    color: var(--slate-800);
    display: flex;
    align-items: center;
    gap: .6rem;
}
.map-section-title i { color: var(--green-500); }
.map-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.map-coord-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: var(--green-50);
    border: 1px solid rgba(34,197,94,.2);
    border-radius: var(--radius-full);
    padding: .3rem .8rem;
    font-size: .75rem;
    font-weight: 700;
    color: var(--green-700);
    font-family: monospace;
    transition: all .3s;
}
.map-coord-badge.empty {
    background: var(--slate-50);
    border-color: var(--slate-200);
    color: var(--slate-400);
}
.map-accuracy-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: #eff6ff;
    border: 1px solid rgba(59,130,246,.2);
    border-radius: var(--radius-full);
    padding: .3rem .8rem;
    font-size: .75rem;
    font-weight: 600;
    color: #2563eb;
}
.map-btn-group { display: flex; gap: .4rem; }
.map-action-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .4rem .9rem;
    border-radius: var(--radius-full);
    border: 1.5px solid var(--slate-200);
    background: white;
    color: var(--slate-600);
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
}
.map-action-btn:hover { border-color:var(--green-400);color:var(--green-700);background:var(--green-50); }
.map-action-btn.primary {
    background: linear-gradient(135deg,var(--green-500),var(--green-600));
    border-color: transparent;
    color: white;
    box-shadow: 0 3px 10px rgba(34,197,94,.3);
}
.map-action-btn.primary:hover { transform:translateY(-1px);box-shadow:0 5px 16px rgba(34,197,94,.4); }
#detectionMap {
    height: 380px;
    width: 100%;
    background: #e8f4f0;
    position: relative;
    z-index: 0;
}
.map-no-location {
    height: 380px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .85rem;
    background: linear-gradient(135deg,var(--slate-50),white);
    color: var(--slate-400);
    text-align: center;
    padding: 2rem;
}
.map-no-location-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: var(--slate-100);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: var(--slate-300);
}
.map-no-location h4 { font-family:var(--font-display);font-weight:700;color:var(--slate-500);font-size:.95rem; }
.map-no-location p  { font-size:.82rem;max-width:280px;line-height:1.6; }
.map-footer {
    padding: .85rem 1.5rem;
    border-top: 1.5px solid var(--slate-100);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    background: var(--slate-50);
}
.map-legend { display: flex; gap: 1.25rem; flex-wrap: wrap; }
.legend-item {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .75rem;
    font-weight: 600;
    color: var(--slate-600);
}
.legend-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
    flex-shrink: 0;
}
.legend-dot.b3      { background: var(--red-500); }
.legend-dot.nonb3   { background: var(--green-500); }
.legend-dot.current { background: #3b82f6; }
.map-history-count  { font-size: .78rem; color: var(--slate-400); }

/* Leaflet popup */
.leaflet-popup-content-wrapper {
    border-radius: 14px !important;
    box-shadow: var(--shadow-lg) !important;
    border: 1.5px solid var(--slate-100) !important;
    padding: 0 !important;
}
.leaflet-popup-content { margin: 0 !important; min-width: 200px; }
.leaflet-popup-tip-container { display: none; }
.map-popup-inner { padding: 1rem; }
.map-popup-title { font-family:var(--font-display);font-weight:800;font-size:.9rem;margin-bottom:.4rem; }
.map-popup-category {
    font-size: .75rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .2rem .55rem;
    border-radius: 999px;
    margin-bottom: .5rem;
}
.map-popup-category.b3    { background:#fef2f2;color:var(--red-700); }
.map-popup-category.nonb3 { background:var(--green-50);color:var(--green-700); }
.map-popup-time { font-size:.72rem;color:var(--slate-400); }
.map-popup-conf {
    font-size:.72rem;font-weight:700;
    background:var(--slate-50);border-radius:6px;
    padding:.2rem .5rem;margin-top:.4rem;display:inline-block;
    color:var(--slate-600);
}

/* location permission bar */
.loc-permission-bar {
    background: linear-gradient(135deg,#eff6ff,#dbeafe);
    border: 1.5px solid rgba(59,130,246,.2);
    border-radius: var(--radius-lg);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .85rem;
    margin-bottom: 1rem;
}
.loc-permission-icon {
    width: 38px; height: 38px;
    background: #3b82f6;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: .95rem;
    flex-shrink: 0;
}
.loc-permission-text { flex:1;font-size:.82rem;color:#1e40af;line-height:1.5; }
.loc-permission-text strong { display:block;font-weight:700;margin-bottom:.15rem; }
.loc-btn-allow {
    padding: .45rem 1rem;
    border-radius: 999px;
    background: #3b82f6;
    color: white;
    font-weight: 700;
    font-size: .8rem;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all .2s;
}
.loc-btn-allow:hover { background:#2563eb; transform:translateY(-1px); }
.loc-btn-deny { background:none;border:none;color:#60a5fa;font-size:.75rem;cursor:pointer;flex-shrink:0;padding:.3rem; }

/* ============================================================
   RESPONSIVE
============================================================ */
@media (max-width:1024px) {
    .detect-grid { grid-template-columns:1fr; }
    .results-panel { display:grid;grid-template-columns:1fr 1fr; }
}
@media (max-width:640px) {
    .detect-page   { padding:1rem; }
    .results-panel { grid-template-columns:1fr; }
    .tab-btn span  { display:none; }
    .cam-actions   { padding:1rem; }
    #detectionMap  { height:260px; }
    .map-no-location { height:260px; }
    .map-section-header { flex-direction:column;align-items:flex-start; }
}
</style>
@endpush

@section('content')
<div class="detect-page">
<div class="detect-inner">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-top">
            <h1 class="page-title">Deteksi Sampah</h1>
            <div class="status-chip ready" id="statusChip">
                <span class="dot-indicator"></span>
                <span id="statusText">Siap Digunakan</span>
            </div>
        </div>
        <p class="page-subtitle">Gunakan kamera atau upload gambar — lokasi foto otomatis dicatat pada peta di bawah.</p>
    </div>

    {{-- Location Permission Bar --}}
    <div class="loc-permission-bar" id="locPermissionBar">
        <div class="loc-permission-icon"><i class="fas fa-map-marker-alt"></i></div>
        <div class="loc-permission-text">
            <strong>Aktifkan Lokasi untuk Fitur Peta</strong>
            Izinkan akses GPS agar lokasi tempat foto diambil bisa ditampilkan di peta.
        </div>
        <button class="loc-btn-allow" onclick="requestLocation()">
            <i class="fas fa-location-arrow"></i> Izinkan
        </button>
        <button class="loc-btn-deny" onclick="dismissLocationBar()">Nanti</button>
    </div>

    {{-- Tab Bar --}}
    <div class="tab-bar">
        <button class="tab-btn active" id="tabCamera" onclick="switchTab('camera')">
            <i class="fas fa-camera"></i><span>Kamera Langsung</span>
        </button>
        <button class="tab-btn" id="tabUpload" onclick="switchTab('upload')">
            <i class="fas fa-image"></i><span>Upload Foto</span>
        </button>
    </div>

    {{-- Detection Grid --}}
    <div class="detect-grid">

        {{-- LEFT: Camera / Upload --}}
        <div>

            {{-- CAMERA PANEL --}}
            <div id="cameraPanel">
                <div class="camera-card">
                    <div class="camera-viewport" id="cameraViewport">
                        <video id="videoFeed" autoplay playsinline muted></video>
                        <canvas id="detectionCanvas"></canvas>

                        <div class="camera-placeholder" id="cameraPlaceholder">
                            <div class="camera-placeholder-icon"><i class="fas fa-camera"></i></div>
                            <h3>Kamera Belum Aktif</h3>
                            <p>Tekan "Aktifkan Kamera" untuk mulai deteksi</p>
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

                        <div class="location-overlay searching" id="locationOverlay">
                            <span class="location-dot"></span>
                            <span id="locationText">Mencari lokasi...</span>
                        </div>

                        <div class="cam-controls" id="camControls">
                            <button class="cam-ctrl-btn" onclick="flipCamera()" title="Ganti Kamera">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button class="cam-ctrl-btn" onclick="detectOnce()" title="Ambil & Deteksi">
                                <i class="fas fa-circle"></i>
                            </button>
                        </div>

                        {{-- ✅ FIX: Flash effect element --}}
                        <div class="camera-flash" id="cameraFlash"></div>

                        <div class="processing-overlay" id="processingOverlay">
                            <div class="processing-spinner"></div>
                            <div class="processing-text">Memproses Gambar...</div>
                            <div class="processing-sub" id="processingSubText">Model YOLO sedang menganalisis</div>
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
                        <button class="btn-icon-only" id="btnAutoDetect" title="Auto Deteksi" onclick="toggleAutoDetect()" disabled>
                            <i class="fas fa-play"></i>
                        </button>
                        <button class="btn-icon-only" title="Simpan Gambar" onclick="saveResult()">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- UPLOAD PANEL --}}
            <div id="uploadPanel" style="display:none">
                <div class="camera-card">
                    <div style="padding:1.5rem">
                        <div class="upload-zone" id="uploadZone">
                            <input type="file" id="fileInput" accept="image/*" onchange="handleFileUpload(event)">
                            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <div class="upload-title">Seret & Lepas Gambar</div>
                            <p class="upload-sub">atau klik untuk memilih file dari perangkat</p>
                            <div class="upload-formats">
                                <span class="format-badge">JPG</span>
                                <span class="format-badge">PNG</span>
                                <span class="format-badge">WEBP</span>
                                <span class="format-badge">Max 10MB</span>
                            </div>
                        </div>
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
                            <i class="fas fa-microscope"></i> Deteksi Sekarang
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

        </div>{{-- /left --}}

        {{-- RIGHT: Results Panel --}}
        <div class="results-panel">

            {{-- Summary --}}
            <div class="panel-card">
                <div class="panel-card-header">
                    <div class="panel-card-title">
                        <i class="fas fa-chart-pie" style="color:var(--green-500)"></i>
                        Ringkasan
                    </div>
                    <button onclick="clearResults()" style="background:none;border:none;cursor:pointer;color:var(--slate-400);font-size:.78rem;font-weight:600;display:flex;align-items:center;gap:.3rem">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <div class="panel-card-body">
                    <div class="summary-bar">
                        <div class="summary-box b3">
                            <div class="summary-num" id="sumB3">0</div>
                            <div class="summary-lbl">⚠️ B3</div>
                        </div>
                        <div class="summary-box nonb3">
                            <div class="summary-num" id="sumNonB3">0</div>
                            <div class="summary-lbl">✅ Non-B3</div>
                        </div>
                    </div>
                    <div style="margin-top:1rem">
                        <div class="conf-bar-wrap">
                            <div style="display:flex;justify-content:space-between;font-size:.75rem;font-weight:600;color:var(--slate-500)">
                                <span>Avg Conf. B3</span><span id="avgConfB3">—</span>
                            </div>
                            <div class="conf-bar-track">
                                <div class="conf-bar-fill" id="barB3" style="width:0%;background:linear-gradient(90deg,var(--red-400),var(--red-500))"></div>
                            </div>
                        </div>
                        <div class="conf-bar-wrap" style="margin-top:.6rem">
                            <div style="display:flex;justify-content:space-between;font-size:.75rem;font-weight:600;color:var(--slate-500)">
                                <span>Avg Conf. Non-B3</span><span id="avgConfNonB3">—</span>
                            </div>
                            <div class="conf-bar-track">
                                <div class="conf-bar-fill" id="barNonB3" style="width:0%;background:linear-gradient(90deg,var(--green-400),var(--lime-400))"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detected Objects --}}
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

            {{-- Tips --}}
            <div class="panel-card">
                <div class="panel-card-header">
                    <div class="panel-card-title">
                        <i class="fas fa-lightbulb" style="color:var(--amber-500)"></i>
                        Tips Penanganan
                    </div>
                </div>
                <div class="panel-card-body">
                    <div style="display:flex;gap:.75rem;align-items:flex-start;padding:.75rem 0;border-bottom:1.5px solid var(--slate-50)">
                        <div style="width:34px;height:34px;border-radius:8px;background:#fef2f2;color:var(--red-600);display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0">
                            <i class="fas fa-biohazard"></i>
                        </div>
                        <div style="font-size:.8rem;color:var(--slate-600);line-height:1.5">
                            <strong style="display:block;color:var(--slate-800);font-weight:600;margin-bottom:.15rem">B3 Terdeteksi?</strong>
                            Jangan buang ke tempat sampah biasa. Bawa ke TPS3R atau fasilitas pengolahan B3.
                        </div>
                    </div>
                    <div style="display:flex;gap:.75rem;align-items:flex-start;padding:.75rem 0">
                        <div style="width:34px;height:34px;border-radius:8px;background:var(--green-50);color:var(--green-600);display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0">
                            <i class="fas fa-map-pin"></i>
                        </div>
                        <div style="font-size:.8rem;color:var(--slate-600);line-height:1.5">
                            <strong style="display:block;color:var(--slate-800);font-weight:600;margin-bottom:.15rem">Lokasi Tercatat</strong>
                            Titik foto tersimpan di peta di bawah untuk referensi lokasi sampah.
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /results --}}
    </div>{{-- /grid --}}

    {{-- MAP SECTION --}}
    <div class="map-section" id="mapSection">
        <div class="map-section-header">
            <div class="map-section-title">
                <i class="fas fa-map-marked-alt"></i>
                Peta Lokasi Deteksi
            </div>

            <div class="map-meta">
                <div class="map-coord-badge empty" id="coordBadge">
                    <i class="fas fa-crosshairs"></i>
                    <span id="coordText">Belum ada lokasi</span>
                </div>

                <div class="map-accuracy-badge" id="accuracyBadge" style="display:none">
                    <i class="fas fa-bullseye"></i>
                    <span id="accuracyText">±— m</span>
                </div>

                <div class="map-btn-group">
                    <button class="map-action-btn" onclick="centerMapOnUser()" id="btnCenterMap">
                        <i class="fas fa-crosshairs"></i> Lokasi Saya
                    </button>
                    <button class="map-action-btn" onclick="openInGoogleMaps()" id="btnGoogleMaps" style="display:none">
                        <i class="fas fa-external-link-alt"></i> Google Maps
                    </button>
                    <button class="map-action-btn primary" onclick="requestLocation()">
                        <i class="fas fa-location-arrow"></i> Perbarui GPS
                    </button>
                </div>
            </div>
        </div>

        <div id="mapContainer">
            <div class="map-no-location" id="mapPlaceholder">
                <div class="map-no-location-icon"><i class="fas fa-map-marked-alt"></i></div>
                <h4>Peta Akan Muncul di Sini</h4>
                <p>Izinkan akses GPS dan lakukan deteksi untuk melihat lokasi foto sampah di peta.</p>
                <button class="map-action-btn primary" onclick="requestLocation()" style="margin-top:.5rem">
                    <i class="fas fa-location-arrow"></i> Aktifkan Lokasi
                </button>
            </div>
            <div id="detectionMap" style="display:none"></div>
        </div>

        <div class="map-footer">
            <div class="map-legend">
                <div class="legend-item"><div class="legend-dot current"></div> Lokasi Anda Sekarang</div>
                <div class="legend-item"><div class="legend-dot b3"></div> Sampah B3 Terdeteksi</div>
                <div class="legend-item"><div class="legend-dot nonb3"></div> Sampah Non-B3</div>
            </div>
            <div class="map-history-count" id="mapHistoryCount">0 titik tercatat sesi ini</div>
        </div>
    </div>

</div>{{-- /detect-inner --}}
</div>{{-- /detect-page --}}
@endsection

@push('scripts')
{{-- Leaflet JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
/* ============================================================
   CONSTANTS & STATE
============================================================ */
const API_DETECT_URL = '{{ route("api.detect") }}';
const CSRF_TOKEN     = document.querySelector('meta[name=csrf-token]')?.content || '';

let stream          = null;
let facingMode      = 'environment';
let autoDetect      = false;
let autoInterval    = null;
let fpsCount        = 0;
let fpsTimer        = null;

/* --- Location state --- */
let currentLat      = null;
let currentLng      = null;
let currentAccuracy = null;
let locationWatcher = null;
let locationGranted = false;
let mapInitialized  = false;

/* --- Leaflet map --- */
let leafletMap       = null;
let userMarker       = null;
let accuracyCircle   = null;
let detectionMarkers = [];
let sessionPoints    = 0;

/* ============================================================
   LOCATION
============================================================ */
function requestLocation() {
    if (!navigator.geolocation) {
        showToast('GPS Tidak Didukung', 'Browser ini tidak mendukung geolokasi.', 'error');
        return;
    }
    setLocationOverlay('searching', 'Mencari lokasi GPS...');
    navigator.geolocation.getCurrentPosition(
        onLocationSuccess,
        onLocationError,
        { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
    );
}

function startLocationWatching() {
    if (locationWatcher) navigator.geolocation.clearWatch(locationWatcher);
    locationWatcher = navigator.geolocation.watchPosition(
        onLocationSuccess,
        onLocationError,
        { enableHighAccuracy: true, maximumAge: 5000, timeout: 15000 }
    );
}

function onLocationSuccess(pos) {
    currentLat      = pos.coords.latitude;
    currentLng      = pos.coords.longitude;
    currentAccuracy = pos.coords.accuracy;
    locationGranted = true;

    document.getElementById('locPermissionBar').style.display = 'none';

    const coordBadge = document.getElementById('coordBadge');
    coordBadge.classList.remove('empty');
    document.getElementById('coordText').textContent =
        `${currentLat.toFixed(5)}, ${currentLng.toFixed(5)}`;

    const accBadge = document.getElementById('accuracyBadge');
    accBadge.style.display = 'flex';
    document.getElementById('accuracyText').textContent =
        `±${Math.round(currentAccuracy)} m`;

    document.getElementById('btnGoogleMaps').style.display = '';

    setLocationOverlay('ready',
        `${currentLat.toFixed(4)}, ${currentLng.toFixed(4)} ±${Math.round(currentAccuracy)}m`);

    initOrUpdateMap();
}

function onLocationError(err) {
    const msgs = {
        1: 'Izin lokasi ditolak. Aktifkan di pengaturan browser.',
        2: 'Posisi tidak tersedia. Pastikan GPS aktif.',
        3: 'Waktu permintaan lokasi habis. Coba lagi.',
    };
    setLocationOverlay('error', msgs[err.code] || 'Gagal mendapatkan lokasi.');
    showToast('Lokasi Gagal', msgs[err.code] || 'Coba aktifkan GPS.', 'error');
}

function dismissLocationBar() {
    document.getElementById('locPermissionBar').style.display = 'none';
}

function setLocationOverlay(state, text) {
    const el = document.getElementById('locationOverlay');
    el.className = 'location-overlay visible';
    if (state === 'searching') el.classList.add('searching');
    if (state === 'error')     el.classList.add('error-loc');
    document.getElementById('locationText').textContent = text;
}

/* ============================================================
   LEAFLET MAP
============================================================ */
function initOrUpdateMap() {
    document.getElementById('mapPlaceholder').style.display = 'none';
    document.getElementById('detectionMap').style.display   = '';

    if (!mapInitialized) {
        leafletMap = L.map('detectionMap', {
            center: [currentLat, currentLng],
            zoom: 16,
            zoomControl: true,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);

        mapInitialized = true;
    }

    updateUserMarker();
}

function updateUserMarker() {
    if (!leafletMap) return;

    if (accuracyCircle) accuracyCircle.remove();
    accuracyCircle = L.circle([currentLat, currentLng], {
        radius: currentAccuracy,
        color: '#3b82f6',
        fillColor: '#3b82f6',
        fillOpacity: 0.08,
        weight: 1.5,
        dashArray: '4 4',
    }).addTo(leafletMap);

    const userIcon = L.divIcon({
        html: `<div style="
            width:18px;height:18px;
            background:#3b82f6;
            border:3px solid white;
            border-radius:50%;
            box-shadow:0 2px 8px rgba(59,130,246,.6);
        "></div>`,
        iconSize: [18, 18],
        iconAnchor: [9, 9],
        className: '',
    });

    if (userMarker) userMarker.remove();
    userMarker = L.marker([currentLat, currentLng], { icon: userIcon })
        .addTo(leafletMap)
        .bindPopup(`<div class="map-popup-inner">
            <div class="map-popup-title">📍 Lokasi Anda</div>
            <div class="map-popup-time">Akurasi: ±${Math.round(currentAccuracy)} m</div>
            <div class="map-popup-conf">${currentLat.toFixed(6)}, ${currentLng.toFixed(6)}</div>
        </div>`, { offset: [0, -5] });

    leafletMap.setView([currentLat, currentLng], leafletMap.getZoom());
}

function addDetectionMarker(detections, lat, lng, timestamp) {
    if (!Array.isArray(detections) || detections.length === 0) return;
    if (!leafletMap || !lat || !lng) return;

    const hasB3     = detections.some(d => d && d.category === 'B3');
    const color     = hasB3 ? '#ef4444' : '#22c55e';
    const icon_char = hasB3 ? '⚠️' : '✅';
    const label     = detections.map(d => d && d.label ? d.label : '').filter(Boolean).join(', ');
    const category  = hasB3 ? 'B3' : 'Non-B3';

    const jLat = lat + (Math.random() - 0.5) * 0.00003;
    const jLng = lng + (Math.random() - 0.5) * 0.00003;

    const icon = L.divIcon({
        html: `<div style="
            width:28px;height:28px;
            background:${color};
            border:3px solid white;
            border-radius:50%;
            box-shadow:0 3px 10px rgba(0,0,0,.25);
            display:flex;align-items:center;justify-content:center;
            font-size:12px;
        ">${icon_char}</div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 14],
        className: '',
    });

    const avgConf  = detections.reduce((s, d) => s + (d && d.confidence ? d.confidence : 0), 0) / detections.length;
    const catClass = hasB3 ? 'b3' : 'nonb3';
    const catLabel = hasB3 ? '⚠️ B3 — Berbahaya' : '✅ Non-B3 — Aman';

    const marker = L.marker([jLat, jLng], { icon })
        .addTo(leafletMap)
        .bindPopup(`<div class="map-popup-inner">
            <div class="map-popup-title">${label}</div>
            <div class="map-popup-category ${catClass}">${catLabel}</div>
            <div class="map-popup-time">${timestamp}</div>
            <div class="map-popup-conf">Kepercayaan: ${Math.round(avgConf * 100)}% · ${detections.length} objek</div>
            <div class="map-popup-conf" style="margin-top:.25rem">📍 ${lat.toFixed(5)}, ${lng.toFixed(5)}</div>
        </div>`, { offset: [0, -5] });

    setTimeout(() => marker.openPopup(), 300);

    detectionMarkers.push({ marker, lat, lng, label, category, timestamp });
    sessionPoints++;
    document.getElementById('mapHistoryCount').textContent =
        `${sessionPoints} titik tercatat sesi ini`;

    const allPoints = detectionMarkers.map(m => [m.lat, m.lng]);
    if (userMarker) allPoints.push([currentLat, currentLng]);
    if (allPoints.length > 1) {
        leafletMap.fitBounds(L.latLngBounds(allPoints), { padding: [48, 48] });
    }
}

function centerMapOnUser() {
    if (!leafletMap || !currentLat) { requestLocation(); return; }
    leafletMap.setView([currentLat, currentLng], 17, { animate: true });
    if (userMarker) userMarker.openPopup();
}

function openInGoogleMaps() {
    if (!currentLat) return;
    window.open(`https://www.google.com/maps?q=${currentLat},${currentLng}`, '_blank');
}

/* ============================================================
   TAB SWITCHING
============================================================ */
function switchTab(tab) {
    const isCamera = tab === 'camera';
    document.getElementById('cameraPanel').style.display = isCamera ? '' : 'none';
    document.getElementById('uploadPanel').style.display = isCamera ? 'none' : '';
    document.getElementById('tabCamera').classList.toggle('active', isCamera);
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
            audio: false,
        });
        const video = document.getElementById('videoFeed');
        video.srcObject = stream;
        video.style.display = 'block';
        document.getElementById('cameraPlaceholder').style.display   = 'none';
        document.getElementById('scanOverlay').classList.add('active');
        document.getElementById('camInfo').classList.add('visible');
        document.getElementById('camControls').classList.add('visible');
        document.getElementById('locationOverlay').classList.add('visible');
        document.getElementById('btnCameraIcon').className  = 'fas fa-stop';
        document.getElementById('btnCameraText').textContent = 'Matikan Kamera';
        document.getElementById('btnDetect').disabled     = false;
        document.getElementById('btnAutoDetect').disabled = false;
        setStatus('ready', 'Kamera Aktif');
        startFpsCounter();

        if (navigator.geolocation) startLocationWatching();
        else setLocationOverlay('error', 'GPS tidak tersedia');

        showToast('Kamera Aktif', 'Siap untuk deteksi', 'success');
    } catch (err) {
        setStatus('error', 'Kamera Gagal');
        showToast('Kamera Tidak Bisa Diakses', err.message || 'Periksa izin kamera', 'error');
    }
}

function stopCamera() {
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    if (autoDetect) toggleAutoDetect();
    if (locationWatcher) { navigator.geolocation.clearWatch(locationWatcher); locationWatcher = null; }

    const video = document.getElementById('videoFeed');
    video.srcObject = null;
    video.style.display = 'none';

    // ✅ FIX: bersihkan canvas saat stop agar tidak tertinggal gambar lama
    const detCanvas = document.getElementById('detectionCanvas');
    detCanvas.getContext('2d').clearRect(0, 0, detCanvas.width, detCanvas.height);
    detCanvas.style.zIndex = '';

    document.getElementById('cameraPlaceholder').style.display    = '';
    document.getElementById('scanOverlay').classList.remove('active');
    document.getElementById('camInfo').classList.remove('visible');
    document.getElementById('camControls').classList.remove('visible');
    document.getElementById('locationOverlay').classList.remove('visible');
    document.getElementById('btnCameraIcon').className  = 'fas fa-camera';
    document.getElementById('btnCameraText').textContent = 'Aktifkan Kamera';
    document.getElementById('btnDetect').disabled     = true;
    document.getElementById('btnAutoDetect').disabled = true;
    setStatus('ready', 'Siap Digunakan');
    stopFpsCounter();
}

async function flipCamera() {
    facingMode = facingMode === 'environment' ? 'user' : 'environment';
    if (stream) { stopCamera(); await startCamera(); }
}

/* ============================================================
   DETECTION — KAMERA
   ✅ FIX UTAMA:
   1. Snapshot video → tampilkan di detectionCanvas (video "freeze")
   2. Sembunyikan <video>, tampilkan canvas snapshot
   3. Kirim resolusi asli ke API untuk akurasi YOLO
   4. Hitung ulang bbox dengan memperhitungkan crop object-fit: cover
   5. Tampilkan video kembali setelah 3 detik
============================================================ */
async function detectOnce() {
    const video = document.getElementById('videoFeed');
    if (!video.srcObject) return;

    // Simpan koordinat & waktu saat tombol ditekan (bukan saat response balik)
    const snapLat  = currentLat;
    const snapLng  = currentLng;
    const snapTime = new Date().toLocaleTimeString('id-ID');

    // --- Flash effect ---
    triggerFlash();

    // --- Ambil ukuran display viewport ---
    const detCanvas = document.getElementById('detectionCanvas');
    const viewport  = document.getElementById('cameraViewport');
    const dispW     = viewport.clientWidth;
    const dispH     = viewport.clientHeight;

    // --- Ukuran video asli ---
    const vw = video.videoWidth  || 640;
    const vh = video.videoHeight || 480;

    // --- Hitung crop region (simulasi object-fit: cover) ---
    // Kita perlu tahu area mana dari video asli yang terlihat di viewport
    const vRatio = vw / vh;
    const dRatio = dispW / dispH;
    let sx = 0, sy = 0, sw = vw, sh = vh;
    if (vRatio > dRatio) {
        // Video lebih lebar → crop kiri-kanan
        sw = vh * dRatio;
        sx = (vw - sw) / 2;
    } else {
        // Video lebih tinggi → crop atas-bawah
        sh = vw / dRatio;
        sy = (vh - sh) / 2;
    }

    // --- Gambar snapshot ke detectionCanvas (ukuran display) ---
    detCanvas.width  = dispW;
    detCanvas.height = dispH;
    const snapCtx = detCanvas.getContext('2d');
    snapCtx.drawImage(video, sx, sy, sw, sh, 0, 0, dispW, dispH);

    // --- Sembunyikan video → canvas snapshot terlihat (efek "foto diambil") ---
    video.style.visibility = 'hidden';
    detCanvas.style.zIndex = '5';

    // Sembunyikan scan overlay saat foto sudah diambil
    document.getElementById('scanOverlay').classList.remove('active');

    showProcessing(true, 'Model YOLO sedang menganalisis...');
    setStatus('detecting', 'Mendeteksi...');

    try {
        // --- Kirim resolusi penuh ke API (lebih akurat untuk YOLO) ---
        const apiCanvas = document.createElement('canvas');
        apiCanvas.width  = vw;
        apiCanvas.height = vh;
        apiCanvas.getContext('2d').drawImage(video, 0, 0, vw, vh);
        const blob       = await new Promise(res => apiCanvas.toBlob(res, 'image/jpeg', .85));
        const detections = await sendToAPI(blob, snapLat, snapLng);

        // --- Gambar bounding box di atas snapshot dengan koreksi crop ---
        renderDetectionsCamera(detections, detCanvas, vw, vh, dispW, dispH, sx, sy, sw, sh);
        updateResultsPanel(detections);

        if (detections.length > 0) {
            addDetectionMarker(detections, snapLat, snapLng, snapTime);
        }

        fpsCount++;
        setStatus('done', 'Selesai');

    } catch (e) {
        showToast('Deteksi Gagal', e.message, 'error');
        setStatus('error', 'Deteksi Gagal');
        // Kalau gagal, langsung kembalikan video
        restoreVideo();
    } finally {
        showProcessing(false);

        // Kembalikan video setelah 3 detik agar user bisa lihat hasil
        if (stream) {
            setTimeout(() => {
                restoreVideo();
                setStatus('ready', 'Kamera Aktif');
            }, 3000);
        }
    }
}

/* Kembalikan tampilan video & reset state canvas */
function restoreVideo() {
    const video     = document.getElementById('videoFeed');
    const detCanvas = document.getElementById('detectionCanvas');

    video.style.visibility = 'visible';
    detCanvas.style.zIndex = '2';

    // Bersihkan snapshot dari canvas agar video kelihatan kembali
    detCanvas.getContext('2d').clearRect(0, 0, detCanvas.width, detCanvas.height);

    // Aktifkan kembali scan overlay jika kamera masih hidup
    if (stream) {
        document.getElementById('scanOverlay').classList.add('active');
    }
}

/* Flash putih sebentar untuk efek shutter kamera */
function triggerFlash() {
    const flash = document.getElementById('cameraFlash');
    flash.classList.add('active');
    setTimeout(() => flash.classList.remove('active'), 150);
}

function toggleAutoDetect() {
    autoDetect = !autoDetect;
    const btn = document.getElementById('btnAutoDetect');
    if (autoDetect) {
        btn.classList.add('active');
        btn.innerHTML = '<i class="fas fa-pause"></i>';
        autoInterval  = setInterval(detectOnce, 2500);
        showToast('Auto Deteksi Aktif', 'Mendeteksi setiap 2.5 detik', 'success');
    } else {
        btn.classList.remove('active');
        btn.innerHTML = '<i class="fas fa-play"></i>';
        clearInterval(autoInterval);
    }
}

/* ============================================================
   API CALL
============================================================ */
async function sendToAPI(blob, lat, lng) {
    const form = new FormData();
    form.append('image', blob, 'frame.jpg');
    form.append('_token', CSRF_TOKEN);
    if (lat) form.append('latitude',  lat);
    if (lng) form.append('longitude', lng);

    let res;
    try {
        res = await fetch(API_DETECT_URL, {
            method:  'POST',
            body:    form,
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        });
    } catch (networkErr) {
        throw new Error('Tidak bisa terhubung ke server. Pastikan Laravel berjalan.');
    }

    const raw = await res.text();

    let data;
    try {
        data = JSON.parse(raw);
    } catch {
        console.error('Non-JSON response:', raw.substring(0, 300));
        throw new Error(`Server error ${res.status}: Response bukan JSON. Cek Laravel log.`);
    }

    if (!data.success) {
        const msg  = data.message || 'Deteksi gagal';
        const hint = data.hint    ? ` — ${data.hint}` : '';
        throw new Error(msg + hint);
    }

    const detections = data.detections;
    if (!Array.isArray(detections)) {
        console.warn('API returned non-array detections:', detections);
        return [];
    }

    return detections;
}

/* ============================================================
   DRAW BOUNDING BOXES — KAMERA
   ✅ FIX: Memperhitungkan crop region dari object-fit: cover
   Parameter:
     detCanvas     — element canvas yang akan digambar
     vw, vh        — resolusi video asli (yang dikirim ke API)
     dispW, dispH  — ukuran viewport yang ditampilkan
     sx, sy, sw, sh — crop region dari video asli (hasil kalkulasi cover)
============================================================ */
function renderDetectionsCamera(detections, detCanvas, vw, vh, dispW, dispH, sx, sy, sw, sh) {
    if (!Array.isArray(detections)) detections = [];

    // Snapshot sudah tergambar — kita gambar bbox di atasnya
    const ctx = detCanvas.getContext('2d');

    // Skala: dari koordinat video asli (yang sudah di-crop sx,sy,sw,sh)
    // ke koordinat display (0,0,dispW,dispH)
    const scaleX = dispW / sw;
    const scaleY = dispH / sh;

    detections.forEach(d => {
        if (!d || typeof d !== 'object' || !Array.isArray(d.bbox) || d.bbox.length < 4) return;

        const isB3  = d.category === 'B3';
        const color = isB3 ? '#ef4444' : '#22c55e';

        // Geser bbox relatif terhadap crop region (sx, sy), lalu skala ke display
        const x = (d.bbox[0] - sx) * scaleX;
        const y = (d.bbox[1] - sy) * scaleY;
        const w = (d.bbox[2] - d.bbox[0]) * scaleX;
        const h = (d.bbox[3] - d.bbox[1]) * scaleY;

        // Bounding box utama
        ctx.strokeStyle = color;
        ctx.lineWidth   = 2.5;
        ctx.shadowColor = isB3 ? 'rgba(239,68,68,.4)' : 'rgba(34,197,94,.4)';
        ctx.shadowBlur  = 8;
        ctx.strokeRect(x, y, w, h);
        ctx.shadowBlur  = 0;

        // Corner brackets
        const cs = 14; ctx.lineWidth = 3.5;
        [[x, y], [x+w, y], [x, y+h], [x+w, y+h]].forEach(([px, py], i) => {
            ctx.beginPath();
            ctx.moveTo(px, py + (i < 2 ? cs : -cs));
            ctx.lineTo(px, py);
            ctx.lineTo(px + (i % 2 === 0 ? cs : -cs), py);
            ctx.stroke();
        });

        // Label badge
        ctx.font = 'bold 12px "Space Grotesk",sans-serif';
        const label = `${d.label} ${Math.round((d.confidence || 0) * 100)}%`;
        const tw    = ctx.measureText(label).width;
        const ly    = y > 24 ? y - 24 : y + 4;
        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.roundRect(x, ly, tw + 16, 20, 4);
        ctx.fill();
        ctx.fillStyle = 'white';
        ctx.fillText(label, x + 8, ly + 14);
    });
}

/* ============================================================
   DRAW BOUNDING BOXES — UPLOAD
   ✅ FIX: Ukuran canvas = ukuran tampilan gambar (getBoundingClientRect),
   bukan naturalWidth. Tidak ada crop untuk upload (object-fit: contain).
============================================================ */
function renderDetections(detections, canvas, vw, vh) {
    if (!Array.isArray(detections)) detections = [];

    const ctx  = canvas.getContext('2d');

    // ✅ Gunakan ukuran canvas yang sudah di-set dari luar (dispW x dispH)
    const dispW = canvas.width;
    const dispH = canvas.height;

    // Untuk upload: tidak ada crop, hanya skala lurus dari resolusi asli ke display
    // Perlu memperhitungkan letterbox (object-fit: contain) agar bbox tidak meleset
    const imgRatio  = vw / vh;
    const dispRatio = dispW / dispH;

    let renderW, renderH, offsetX = 0, offsetY = 0;
    if (imgRatio > dispRatio) {
        // Gambar lebih lebar → letterbox atas-bawah
        renderW = dispW;
        renderH = dispW / imgRatio;
        offsetY = (dispH - renderH) / 2;
    } else {
        // Gambar lebih tinggi → letterbox kiri-kanan
        renderH = dispH;
        renderW = dispH * imgRatio;
        offsetX = (dispW - renderW) / 2;
    }

    const scaleX = renderW / vw;
    const scaleY = renderH / vh;

    ctx.clearRect(0, 0, dispW, dispH);

    detections.forEach(d => {
        if (!d || typeof d !== 'object' || !Array.isArray(d.bbox) || d.bbox.length < 4) return;

        const isB3  = d.category === 'B3';
        const color = isB3 ? '#ef4444' : '#22c55e';

        const x = offsetX + d.bbox[0] * scaleX;
        const y = offsetY + d.bbox[1] * scaleY;
        const w = (d.bbox[2] - d.bbox[0]) * scaleX;
        const h = (d.bbox[3] - d.bbox[1]) * scaleY;

        ctx.strokeStyle = color;
        ctx.lineWidth   = 2.5;
        ctx.shadowColor = isB3 ? 'rgba(239,68,68,.4)' : 'rgba(34,197,94,.4)';
        ctx.shadowBlur  = 8;
        ctx.strokeRect(x, y, w, h);
        ctx.shadowBlur  = 0;

        const cs = 14; ctx.lineWidth = 3.5;
        [[x, y], [x+w, y], [x, y+h], [x+w, y+h]].forEach(([px, py], i) => {
            ctx.beginPath();
            ctx.moveTo(px, py + (i < 2 ? cs : -cs));
            ctx.lineTo(px, py);
            ctx.lineTo(px + (i % 2 === 0 ? cs : -cs), py);
            ctx.stroke();
        });

        ctx.font = 'bold 12px "Space Grotesk",sans-serif';
        const label = `${d.label} ${Math.round((d.confidence || 0) * 100)}%`;
        const tw    = ctx.measureText(label).width;
        const ly    = y > 24 ? y - 24 : y + 4;
        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.roundRect(x, ly, tw + 16, 20, 4);
        ctx.fill();
        ctx.fillStyle = 'white';
        ctx.fillText(label, x + 8, ly + 14);
    });
}

/* ============================================================
   RESULTS PANEL
============================================================ */
function updateResultsPanel(detections) {
    if (!Array.isArray(detections)) detections = [];

    const list  = document.getElementById('detectionList');
    const empty = document.getElementById('emptyState');
    document.getElementById('detCount').textContent = `${detections.length} item`;

    if (detections.length === 0) {
        empty.style.display = '';
        ['sumB3','sumNonB3'].forEach(id => document.getElementById(id).textContent = '0');
        ['barB3','barNonB3'].forEach(id => document.getElementById(id).style.width = '0%');
        ['avgConfB3','avgConfNonB3'].forEach(id => document.getElementById(id).textContent = '—');
        return;
    }

    empty.style.display = 'none';
    list.querySelectorAll('.detection-item').forEach(el => el.remove());

    let b3Items = [], nonB3Items = [];

    detections.forEach(d => {
        if (!d || typeof d !== 'object' || !d.label || !d.category) return;

        const isB3 = d.category === 'B3';
        if (isB3) b3Items.push(d); else nonB3Items.push(d);

        const el = document.createElement('div');
        el.className = `detection-item ${isB3 ? 'b3' : 'nonb3'}`;
        el.innerHTML = `
            <div class="det-icon">
                <i class="fas fa-${isB3 ? 'biohazard' : 'recycle'}"></i>
            </div>
            <div class="det-info">
                <div class="det-label">${d.label}</div>
                <div class="det-category">${isB3 ? '⚠️ B3 — Berbahaya' : '✅ Non-B3 — Aman'}</div>
            </div>
            <div class="det-conf">${Math.round((d.confidence || 0) * 100)}%</div>`;
        list.appendChild(el);
    });

    document.getElementById('sumB3').textContent    = b3Items.length;
    document.getElementById('sumNonB3').textContent = nonB3Items.length;

    const avgB3    = b3Items.length    ? b3Items.reduce((s, d)    => s + d.confidence, 0) / b3Items.length    : 0;
    const avgNonB3 = nonB3Items.length ? nonB3Items.reduce((s, d) => s + d.confidence, 0) / nonB3Items.length : 0;

    document.getElementById('barB3').style.width        = `${avgB3 * 100}%`;
    document.getElementById('barNonB3').style.width     = `${avgNonB3 * 100}%`;
    document.getElementById('avgConfB3').textContent    = avgB3    ? `${Math.round(avgB3 * 100)}%`    : '—';
    document.getElementById('avgConfNonB3').textContent = avgNonB3 ? `${Math.round(avgNonB3 * 100)}%` : '—';

    document.getElementById('objCounter').textContent = detections.length;

    if (b3Items.length > 0)
        showToast('⚠️ B3 Terdeteksi!', `${b3Items.length} item berbahaya ditemukan`, 'warning');
}

function clearResults() {
    updateResultsPanel([]);
    const detCanvas = document.getElementById('detectionCanvas');
    if (detCanvas) detCanvas.getContext('2d').clearRect(0, 0, detCanvas.width, detCanvas.height);
    const upCanvas = document.getElementById('uploadResultCanvas');
    if (upCanvas) upCanvas.getContext('2d').clearRect(0, 0, upCanvas.width, upCanvas.height);
}

/* ============================================================
   UPLOAD
   ✅ FIX: Tunggu gambar benar-benar termuat sebelum tampilkan wrapper
           Ukuran canvas diambil dari getBoundingClientRect (ukuran tampilan nyata)
============================================================ */
function handleFileUpload(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = ev => {
        const img = document.getElementById('uploadPreviewImg');

        // Reset canvas lama
        const canvas = document.getElementById('uploadResultCanvas');
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

        img.onload = () => {
            // ✅ Tampilkan wrapper dulu, baru ambil ukuran sebenarnya
            document.getElementById('uploadZone').style.display        = 'none';
            document.getElementById('uploadPreviewWrap').style.display = 'block';
            document.getElementById('uploadActions').style.display     = 'flex';
        };

        img.src = ev.target.result;
    };
    reader.readAsDataURL(file);
}

async function runUploadDetection() {
    const file = document.getElementById('fileInput').files[0];
    if (!file) return;

    document.getElementById('uploadProcessing').classList.add('show');
    setStatus('detecting', 'Menganalisis...');
    const snapTime = new Date().toLocaleTimeString('id-ID');

    try {
        const detections = await sendToAPI(file, currentLat, currentLng);
        const img        = document.getElementById('uploadPreviewImg');
        const canvas     = document.getElementById('uploadResultCanvas');

        // ✅ FIX: Ukuran canvas = ukuran tampilan gambar yang sebenarnya
        // Gunakan getBoundingClientRect untuk ukuran yang akurat setelah layout
        const rect    = img.getBoundingClientRect();
        canvas.width  = rect.width  > 0 ? rect.width  : img.naturalWidth;
        canvas.height = rect.height > 0 ? rect.height : img.naturalHeight;

        // ✅ Render dengan memperhitungkan letterbox (object-fit: contain)
        renderDetections(detections, canvas, img.naturalWidth, img.naturalHeight);
        updateResultsPanel(detections);

        if (detections.length > 0)
            addDetectionMarker(detections, currentLat, currentLng, snapTime);

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
    document.getElementById('uploadZone').style.display        = '';
    document.getElementById('uploadPreviewWrap').style.display = 'none';
    document.getElementById('uploadActions').style.display     = 'none';
    document.getElementById('fileInput').value = '';
    clearResults();
    setStatus('ready', 'Siap Digunakan');
}

/* Simpan hasil deteksi upload */
function saveUploadResult() {
    const img    = document.getElementById('uploadPreviewImg');
    const canvas = document.getElementById('uploadResultCanvas');
    if (!img.src || img.src === window.location.href) {
        showToast('Belum ada gambar', 'Upload gambar terlebih dahulu', 'error');
        return;
    }
    // Merge gambar + canvas overlay ke satu canvas baru
    const merged = document.createElement('canvas');
    merged.width  = img.naturalWidth;
    merged.height = img.naturalHeight;
    const mCtx = merged.getContext('2d');
    mCtx.drawImage(img, 0, 0, img.naturalWidth, img.naturalHeight);
    mCtx.drawImage(canvas, 0, 0, img.naturalWidth, img.naturalHeight);

    const a = document.createElement('a');
    a.download = `wasteguard_upload_${Date.now()}.png`;
    a.href     = merged.toDataURL();
    a.click();
    showToast('Tersimpan', 'Gambar hasil deteksi diunduh', 'success');
}

/* drag & drop */
const zone = document.getElementById('uploadZone');
zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('dragover'); });
zone.addEventListener('dragleave', ()  => zone.classList.remove('dragover'));
zone.addEventListener('drop', e => {
    e.preventDefault(); zone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file);
        document.getElementById('fileInput').files = dt.files;
        handleFileUpload({ target: { files: dt.files } });
    }
});

/* ============================================================
   HELPERS
============================================================ */
function setStatus(type, text) {
    document.getElementById('statusChip').className  = `status-chip ${type}`;
    document.getElementById('statusText').textContent = text;
}

function showProcessing(show, sub) {
    document.getElementById('processingOverlay').classList.toggle('show', show);
    if (sub) document.getElementById('processingSubText').textContent = sub;
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
    const video     = document.getElementById('videoFeed');
    const detCanvas = document.getElementById('detectionCanvas');

    // ✅ FIX: Merge snapshot + bbox overlay ke canvas baru sebelum download
    const merged = document.createElement('canvas');
    merged.width  = detCanvas.width  || 640;
    merged.height = detCanvas.height || 480;
    const mCtx = merged.getContext('2d');

    // Gambar video (jika masih live) atau snapshot yang ada di detCanvas
    if (stream && video.readyState >= 2) {
        mCtx.drawImage(video, 0, 0, merged.width, merged.height);
    }
    mCtx.drawImage(detCanvas, 0, 0);

    const a = document.createElement('a');
    a.download = `wasteguard_${Date.now()}.png`;
    a.href     = merged.toDataURL();
    a.click();
    showToast('Tersimpan', 'Gambar hasil deteksi diunduh', 'success');
}

/* ============================================================
   AUTO-REQUEST LOCATION ON PAGE LOAD
============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            pos => {
                onLocationSuccess(pos);
                document.getElementById('locPermissionBar').style.display = 'none';
            },
            () => { /* silent fail — permission bar tetap tampil */ },
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 30000 }
        );
    }
});
</script>
@endpush