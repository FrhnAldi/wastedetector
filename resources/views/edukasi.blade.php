@extends('layouts.app')

@section('title', 'Edukasi Sampah B3 — WasteGuard')

@push('styles')
<style>
/* ============================================================
   PAGE SHELL
============================================================ */
.edu-page {
    background: var(--slate-50);
    min-height: calc(100vh - 68px);
}

/* ============================================================
   HERO STRIP
============================================================ */
.edu-hero {
    background: linear-gradient(135deg, #052e16 0%, #14532d 50%, #166534 100%);
    padding: 4rem 2rem 5.5rem;
    position: relative;
    overflow: hidden;
}

.edu-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 15% 60%, rgba(74,222,128,.15) 0%, transparent 45%),
        radial-gradient(circle at 85% 20%, rgba(163,230,53,.10) 0%, transparent 40%),
        radial-gradient(circle at 50% 100%, rgba(34,197,94,.08) 0%, transparent 50%);
    pointer-events: none;
}

/* subtle dot grid */
.edu-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px);
    background-size: 28px 28px;
    pointer-events: none;
}

.edu-hero-inner {
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 3rem;
}

.edu-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    background: rgba(74,222,128,.18);
    border: 1px solid rgba(74,222,128,.35);
    color: #86efac;
    font-size: .78rem;
    font-weight: 700;
    padding: .35rem .9rem;
    border-radius: 999px;
    margin-bottom: 1.1rem;
    letter-spacing: .06em;
    text-transform: uppercase;
}

.edu-hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    margin-bottom: .9rem;
}

.edu-hero-title span {
    background: linear-gradient(90deg, #4ade80, #a3e635);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.edu-hero-sub {
    color: #a1a1aa;
    font-size: .975rem;
    line-height: 1.7;
    max-width: 540px;
}

/* quick-nav pills */
.edu-quicknav {
    display: flex;
    gap: .5rem;
    margin-top: 1.75rem;
    flex-wrap: wrap;
}

.qnav-pill {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .4rem 1rem;
    border-radius: 999px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    color: #d1fae5;
    font-size: .8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
    cursor: pointer;
}

.qnav-pill:hover {
    background: rgba(74,222,128,.2);
    border-color: rgba(74,222,128,.4);
    color: #fff;
}

/* floating stat cards */
.edu-hero-stats {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}

.edu-stat-card {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 1rem 1.4rem;
    min-width: 180px;
    transition: all .3s;
}

.edu-stat-card:hover {
    background: rgba(74,222,128,.12);
    border-color: rgba(74,222,128,.3);
    transform: translateX(-4px);
}

.edu-stat-num {
    font-family: var(--font-display);
    font-size: 1.7rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    margin-bottom: .2rem;
}

.edu-stat-num em {
    font-style: normal;
    color: #4ade80;
}

.edu-stat-lbl {
    font-size: .75rem;
    color: #94a3b8;
    font-weight: 500;
    line-height: 1.4;
}

/* wave divider */
.edu-wave {
    margin-top: -2px;
    line-height: 0;
}

.edu-wave svg { display: block; }

/* ============================================================
   STICKY TOC SIDEBAR LAYOUT
============================================================ */
.edu-body {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2.5rem 2rem 4rem;
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 2.5rem;
    align-items: start;
}

/* ---- TOC ---- */
.edu-toc {
    position: sticky;
    top: 84px;
    background: white;
    border-radius: 18px;
    border: 1.5px solid var(--slate-100);
    padding: 1.25rem;
    box-shadow: var(--shadow-sm);
}

.toc-title {
    font-size: .72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--slate-400);
    margin-bottom: .85rem;
    padding-bottom: .7rem;
    border-bottom: 1.5px solid var(--slate-100);
    display: flex;
    align-items: center;
    gap: .4rem;
}

.toc-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: .15rem;
}

.toc-item a {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .5rem .7rem;
    border-radius: 10px;
    text-decoration: none;
    font-size: .82rem;
    font-weight: 500;
    color: var(--slate-500);
    transition: all .2s;
    border-left: 2px solid transparent;
}

.toc-item a:hover,
.toc-item a.active {
    background: var(--green-50);
    color: var(--green-700);
    border-left-color: var(--green-500);
    font-weight: 600;
}

.toc-item a .toc-icon {
    width: 22px; height: 22px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
    flex-shrink: 0;
    background: var(--slate-100);
    color: var(--slate-500);
    transition: all .2s;
}

.toc-item a:hover .toc-icon,
.toc-item a.active .toc-icon {
    background: var(--green-100);
    color: var(--green-600);
}

.toc-progress {
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1.5px solid var(--slate-100);
}

.toc-progress-label {
    display: flex;
    justify-content: space-between;
    font-size: .72rem;
    font-weight: 600;
    color: var(--slate-400);
    margin-bottom: .4rem;
}

.toc-progress-track {
    height: 5px;
    background: var(--slate-100);
    border-radius: 3px;
    overflow: hidden;
}

.toc-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--green-400), var(--lime-400));
    border-radius: 3px;
    transition: width .4s ease;
    width: 0%;
}

/* ============================================================
   CONTENT SECTIONS
============================================================ */
.edu-content { display: flex; flex-direction: column; gap: 2.5rem; }

/* section anchor */
.edu-section { scroll-margin-top: 90px; }

.section-card {
    background: white;
    border-radius: 22px;
    box-shadow: var(--shadow-sm);
    border: 1.5px solid var(--slate-100);
    overflow: hidden;
    transition: box-shadow .3s;
}

.section-card:hover { box-shadow: var(--shadow-md); }

.section-card-head {
    padding: 1.75rem 2rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border-bottom: 1.5px solid var(--slate-50);
}

.section-head-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.section-head-label {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    margin-bottom: .2rem;
}

.section-head-title {
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--slate-900);
}

.section-card-body { padding: 1.75rem 2rem; }

/* ============================================================
   SECTION 1 — APA ITU B3?
============================================================ */
.what-is-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.what-box {
    border-radius: 14px;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: .6rem;
}

.what-box.danger {
    background: linear-gradient(135deg, #fef2f2, #fff1f2);
    border: 1.5px solid rgba(239,68,68,.15);
}

.what-box.safe {
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    border: 1.5px solid rgba(34,197,94,.15);
}

.what-box-title {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

.what-box.danger .what-box-title { color: var(--red-600); }
.what-box.safe  .what-box-title  { color: var(--green-700); }

.what-box p {
    font-size: .85rem;
    line-height: 1.65;
    color: var(--slate-600);
}

.definition-block {
    background: linear-gradient(135deg, var(--slate-50), white);
    border-radius: 14px;
    border-left: 4px solid var(--green-500);
    padding: 1.1rem 1.4rem;
    margin-bottom: 1.25rem;
}

.definition-block p {
    font-size: .9rem;
    line-height: 1.7;
    color: var(--slate-700);
}

.definition-block strong { color: var(--green-700); }

.characteristic-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .75rem;
}

.char-chip {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1rem .75rem;
    border-radius: 14px;
    gap: .5rem;
    transition: transform .2s;
    cursor: default;
}

.char-chip:hover { transform: translateY(-3px); }

.char-chip-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.char-chip span {
    font-size: .78rem;
    font-weight: 700;
    color: var(--slate-700);
    line-height: 1.3;
}

/* ============================================================
   SECTION 2 — JENIS SAMPAH B3
============================================================ */
.waste-filter-tabs {
    display: flex;
    gap: .4rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}

.wf-tab {
    padding: .4rem .9rem;
    border-radius: 999px;
    border: 1.5px solid var(--slate-200);
    background: white;
    font-size: .78rem;
    font-weight: 700;
    color: var(--slate-500);
    cursor: pointer;
    transition: all .2s;
}

.wf-tab:hover { border-color: var(--green-400); color: var(--green-600); }

.wf-tab.active {
    background: var(--green-500);
    border-color: var(--green-500);
    color: white;
    box-shadow: 0 3px 10px rgba(34,197,94,.3);
}

.waste-type-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.waste-type-card {
    border-radius: 16px;
    padding: 1.25rem;
    border: 1.5px solid transparent;
    cursor: pointer;
    transition: all .25s;
    position: relative;
    overflow: hidden;
}

.waste-type-card::before {
    content: '';
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity .25s;
}

.waste-type-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.waste-type-card:hover::before { opacity: 1; }

/* categories */
.waste-type-card.elektronik { background: #eff6ff; border-color: rgba(59,130,246,.15); }
.waste-type-card.elektronik::before { background: linear-gradient(135deg,rgba(59,130,246,.06),transparent); }
.waste-type-card.elektronik .wt-icon { background: #dbeafe; color: #2563eb; }

.waste-type-card.kimia { background: #fdf4ff; border-color: rgba(168,85,247,.15); }
.waste-type-card.kimia::before { background: linear-gradient(135deg,rgba(168,85,247,.06),transparent); }
.waste-type-card.kimia .wt-icon { background: #f3e8ff; color: #9333ea; }

.waste-type-card.medis { background: #fff7ed; border-color: rgba(249,115,22,.15); }
.waste-type-card.medis::before { background: linear-gradient(135deg,rgba(249,115,22,.06),transparent); }
.waste-type-card.medis .wt-icon { background: #ffedd5; color: #ea580c; }

.waste-type-card.baterai { background: #fef9c3; border-color: rgba(234,179,8,.2); }
.waste-type-card.baterai::before { background: linear-gradient(135deg,rgba(234,179,8,.06),transparent); }
.waste-type-card.baterai .wt-icon { background: #fef08a; color: #ca8a04; }

.waste-type-card.cat { background: #fef2f2; border-color: rgba(239,68,68,.15); }
.waste-type-card.cat::before { background: linear-gradient(135deg,rgba(239,68,68,.06),transparent); }
.waste-type-card.cat .wt-icon { background: #fee2e2; color: var(--red-600); }

.waste-type-card.pertanian { background: #f0fdf4; border-color: rgba(34,197,94,.15); }
.waste-type-card.pertanian::before { background: linear-gradient(135deg,rgba(34,197,94,.06),transparent); }
.waste-type-card.pertanian .wt-icon { background: var(--green-100); color: var(--green-700); }

.wt-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    margin-bottom: .85rem;
}

.wt-name {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: .9rem;
    color: var(--slate-800);
    margin-bottom: .35rem;
}

.wt-examples {
    font-size: .78rem;
    color: var(--slate-500);
    line-height: 1.5;
}

.wt-danger-tag {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-size: .7rem;
    font-weight: 700;
    color: var(--red-600);
    background: rgba(239,68,68,.1);
    border-radius: 999px;
    padding: .2rem .55rem;
    margin-top: .6rem;
}

/* ============================================================
   SECTION 3 — BAHAYA B3
============================================================ */
.danger-timeline {
    position: relative;
    padding-left: 2rem;
}

.danger-timeline::before {
    content: '';
    position: absolute;
    left: .55rem;
    top: 0; bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, var(--red-400), var(--amber-400), var(--red-400));
}

.timeline-item {
    position: relative;
    padding-bottom: 1.75rem;
}

.timeline-item:last-child { padding-bottom: 0; }

.timeline-dot {
    position: absolute;
    left: -1.55rem;
    top: .3rem;
    width: 22px; height: 22px;
    border-radius: 50%;
    border: 2.5px solid white;
    box-shadow: 0 0 0 2px currentColor;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .6rem;
    font-weight: 800;
    color: white;
}

.timeline-item:nth-child(1) .timeline-dot { background: var(--red-500); color: var(--red-500); box-shadow: 0 0 0 2px var(--red-400); }
.timeline-item:nth-child(2) .timeline-dot { background: #ea580c; box-shadow: 0 0 0 2px #fb923c; }
.timeline-item:nth-child(3) .timeline-dot { background: var(--amber-500); box-shadow: 0 0 0 2px var(--amber-400); }
.timeline-item:nth-child(4) .timeline-dot { background: #16a34a; box-shadow: 0 0 0 2px #4ade80; }
.timeline-item:nth-child(5) .timeline-dot { background: #7c3aed; box-shadow: 0 0 0 2px #a78bfa; }

.timeline-head {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: .95rem;
    color: var(--slate-800);
    margin-bottom: .4rem;
    display: flex;
    align-items: center;
    gap: .6rem;
}

.timeline-body {
    font-size: .85rem;
    color: var(--slate-600);
    line-height: 1.7;
}

.danger-alert-box {
    background: linear-gradient(135deg, #fff1f2, #fef2f2);
    border: 1.5px solid rgba(239,68,68,.2);
    border-radius: 14px;
    padding: 1.1rem 1.3rem;
    margin-top: 1.5rem;
    display: flex;
    gap: .85rem;
    align-items: flex-start;
}

.danger-alert-icon {
    width: 38px; height: 38px;
    background: var(--red-500);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
    margin-top: .1rem;
}

.danger-alert-text {
    font-size: .85rem;
    color: var(--red-800);
    line-height: 1.65;
}

.danger-alert-text strong {
    display: block;
    font-weight: 700;
    margin-bottom: .25rem;
    font-size: .9rem;
}

/* ============================================================
   SECTION 4 — CARA PEMBUANGAN
============================================================ */
.disposal-steps {
    display: flex;
    flex-direction: column;
    gap: .85rem;
}

.disposal-step {
    display: flex;
    gap: 1.1rem;
    align-items: flex-start;
    padding: 1.1rem 1.25rem;
    border-radius: 16px;
    border: 1.5px solid var(--slate-100);
    background: white;
    transition: all .25s;
    cursor: default;
}

.disposal-step:hover {
    border-color: var(--green-300);
    background: var(--green-50);
    transform: translateX(4px);
    box-shadow: var(--shadow-sm);
}

.step-num {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--green-500), var(--lime-500));
    color: white;
    font-weight: 800;
    font-size: .85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(34,197,94,.3);
}

.step-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    flex-shrink: 0;
    background: var(--slate-100);
    color: var(--slate-600);
    transition: all .25s;
}

.disposal-step:hover .step-icon {
    background: var(--green-100);
    color: var(--green-700);
}

.step-content { flex: 1; }

.step-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: .92rem;
    color: var(--slate-800);
    margin-bottom: .3rem;
}

.step-desc {
    font-size: .82rem;
    color: var(--slate-500);
    line-height: 1.6;
}

.step-tag {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-size: .7rem;
    font-weight: 700;
    padding: .2rem .55rem;
    border-radius: 999px;
    margin-top: .45rem;
}

.step-tag.penting { background: #fef9c3; color: #92400e; }
.step-tag.wajib   { background: #fef2f2; color: var(--red-700); }
.step-tag.tips    { background: var(--green-50); color: var(--green-700); }

/* disposal do/dont */
.do-dont-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-top: 1.5rem;
}

.do-card, .dont-card {
    border-radius: 16px;
    padding: 1.25rem;
}

.do-card {
    background: var(--green-50);
    border: 1.5px solid rgba(34,197,94,.2);
}

.dont-card {
    background: #fef2f2;
    border: 1.5px solid rgba(239,68,68,.15);
}

.do-card h4, .dont-card h4 {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: .9rem;
    margin-bottom: .85rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

.do-card   h4 { color: var(--green-700); }
.dont-card h4 { color: var(--red-700); }

.do-list, .dont-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: .5rem;
}

.do-list li, .dont-list li {
    display: flex;
    align-items: flex-start;
    gap: .5rem;
    font-size: .8rem;
    line-height: 1.5;
    color: var(--slate-700);
}

.do-list   li i { color: var(--green-500); margin-top: .15rem; font-size: .75rem; flex-shrink: 0; }
.dont-list li i { color: var(--red-500);   margin-top: .15rem; font-size: .75rem; flex-shrink: 0; }

/* ============================================================
   SECTION 5 — LOKASI FASILITAS
============================================================ */
.facility-search {
    display: flex;
    gap: .75rem;
    margin-bottom: 1.25rem;
}

.facility-search input {
    flex: 1;
    padding: .65rem 1.1rem;
    border-radius: 999px;
    border: 1.5px solid var(--slate-200);
    font-size: .875rem;
    outline: none;
    transition: all .2s;
    font-family: var(--font-body);
    background: var(--slate-50);
}

.facility-search input:focus {
    border-color: var(--green-400);
    background: white;
    box-shadow: 0 0 0 3px rgba(34,197,94,.1);
}

.btn-search {
    padding: .65rem 1.35rem;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--green-500), var(--green-600));
    color: white;
    font-weight: 700;
    font-size: .875rem;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: .45rem;
    transition: all .2s;
}

.btn-search:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(34,197,94,.35);
}

.facility-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: .85rem;
}

.facility-card {
    border: 1.5px solid var(--slate-100);
    border-radius: 14px;
    padding: 1rem 1.1rem;
    background: var(--slate-50);
    transition: all .25s;
    cursor: pointer;
}

.facility-card:hover {
    border-color: var(--green-300);
    background: var(--green-50);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.facility-header {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    margin-bottom: .6rem;
}

.facility-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    flex-shrink: 0;
    background: var(--green-100);
    color: var(--green-700);
}

.facility-name {
    font-weight: 700;
    font-size: .875rem;
    color: var(--slate-800);
    line-height: 1.3;
}

.facility-type {
    font-size: .72rem;
    color: var(--slate-400);
    margin-top: .15rem;
}

.facility-info {
    font-size: .78rem;
    color: var(--slate-500);
    display: flex;
    align-items: center;
    gap: .4rem;
}

.facility-distance {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    font-size: .72rem;
    font-weight: 700;
    color: var(--green-700);
    background: var(--green-100);
    border-radius: 999px;
    padding: .15rem .5rem;
}

.map-placeholder {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-radius: 16px;
    border: 2px dashed rgba(34,197,94,.25);
    height: 180px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .6rem;
    color: var(--green-600);
    margin-top: 1.25rem;
    cursor: pointer;
    transition: all .2s;
}

.map-placeholder:hover {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    border-color: rgba(34,197,94,.4);
}

.map-placeholder i { font-size: 2rem; opacity: .6; }
.map-placeholder p { font-size: .85rem; font-weight: 600; }

/* ============================================================
   SECTION 6 — QUIZ
============================================================ */
.quiz-progress {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.quiz-progress-track {
    flex: 1;
    height: 8px;
    background: var(--slate-100);
    border-radius: 4px;
    overflow: hidden;
}

.quiz-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--green-400), var(--lime-400));
    border-radius: 4px;
    transition: width .4s ease;
    width: 0%;
}

.quiz-progress-label {
    font-size: .78rem;
    font-weight: 700;
    color: var(--slate-500);
    white-space: nowrap;
}

.quiz-question-box {
    background: linear-gradient(135deg, var(--slate-50), white);
    border-radius: 16px;
    border: 1.5px solid var(--slate-100);
    padding: 1.5rem;
    margin-bottom: 1.1rem;
}

.quiz-q-num {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--green-600);
    margin-bottom: .5rem;
}

.quiz-q-text {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1rem;
    color: var(--slate-800);
    line-height: 1.5;
}

.quiz-options {
    display: flex;
    flex-direction: column;
    gap: .6rem;
}

.quiz-opt {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: .85rem 1.1rem;
    border-radius: 12px;
    border: 1.5px solid var(--slate-200);
    background: white;
    cursor: pointer;
    transition: all .2s;
    font-size: .875rem;
    font-weight: 500;
    color: var(--slate-700);
    text-align: left;
    width: 100%;
    font-family: var(--font-body);
}

.quiz-opt:hover:not(:disabled) {
    border-color: var(--green-400);
    background: var(--green-50);
    color: var(--green-800);
}

.quiz-opt.correct {
    border-color: var(--green-400);
    background: var(--green-50);
    color: var(--green-800);
}

.quiz-opt.wrong {
    border-color: var(--red-400);
    background: #fef2f2;
    color: var(--red-700);
}

.quiz-opt-letter {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: var(--slate-100);
    font-weight: 800;
    font-size: .8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--slate-500);
    transition: all .2s;
}

.quiz-opt.correct .quiz-opt-letter { background: var(--green-500); color: white; }
.quiz-opt.wrong   .quiz-opt-letter { background: var(--red-500);   color: white; }

.quiz-feedback {
    border-radius: 12px;
    padding: .9rem 1.1rem;
    font-size: .85rem;
    font-weight: 500;
    line-height: 1.6;
    margin-top: .75rem;
    display: none;
    align-items: flex-start;
    gap: .65rem;
}

.quiz-feedback.show { display: flex; }
.quiz-feedback.correct-fb { background: var(--green-50); color: var(--green-800); border: 1px solid rgba(34,197,94,.2); }
.quiz-feedback.wrong-fb   { background: #fef2f2;         color: var(--red-800);   border: 1px solid rgba(239,68,68,.2); }

.quiz-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.25rem;
    gap: .75rem;
}

.quiz-score-badge {
    background: linear-gradient(135deg, var(--green-500), var(--lime-500));
    color: white;
    border-radius: 999px;
    padding: .45rem 1.1rem;
    font-weight: 700;
    font-size: .85rem;
    box-shadow: 0 4px 12px rgba(34,197,94,.3);
}

.btn-quiz-nav {
    padding: .65rem 1.4rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: .875rem;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: .5rem;
    transition: all .2s;
}

.btn-quiz-next {
    background: linear-gradient(135deg, var(--green-500), var(--green-600));
    color: white;
    box-shadow: 0 4px 14px rgba(34,197,94,.3);
}

.btn-quiz-next:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(34,197,94,.4);
}

.btn-quiz-next:disabled {
    opacity: .5;
    cursor: not-allowed;
    transform: none;
}

.btn-quiz-reset {
    background: var(--slate-100);
    color: var(--slate-600);
}

.btn-quiz-reset:hover {
    background: var(--slate-200);
}

/* Quiz results */
.quiz-result-screen {
    text-align: center;
    padding: 1.5rem;
    display: none;
}

.quiz-result-screen.show { display: block; }

.result-trophy { font-size: 4rem; margin-bottom: 1rem; display: block; animation: trophyBounce 1s ease both; }

@keyframes trophyBounce {
    0%   { transform: scale(0) rotate(-15deg); opacity: 0; }
    60%  { transform: scale(1.2) rotate(5deg); opacity: 1; }
    100% { transform: scale(1) rotate(0deg); }
}

.result-score-circle {
    width: 100px; height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--green-500), var(--lime-500));
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
    box-shadow: 0 10px 30px rgba(34,197,94,.4);
}

.result-score-num {
    font-family: var(--font-display);
    font-size: 1.8rem;
    font-weight: 800;
    color: white;
    line-height: 1;
}

.result-score-lbl {
    font-size: .65rem;
    color: rgba(255,255,255,.8);
    font-weight: 700;
}

.result-title {
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--slate-900);
    margin-bottom: .5rem;
}

.result-sub { font-size: .875rem; color: var(--slate-500); margin-bottom: 1.25rem; }

/* ============================================================
   SECTION 7 — REGULASI
============================================================ */
.regulation-list {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}

.regulation-item {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    padding: 1rem 1.2rem;
    border-radius: 14px;
    border: 1.5px solid var(--slate-100);
    background: var(--slate-50);
    transition: all .2s;
}

.regulation-item:hover {
    border-color: var(--green-200);
    background: var(--green-50);
}

.reg-icon {
    width: 40px; height: 40px;
    border-radius: 11px;
    background: #eff6ff;
    color: #3b82f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.reg-code {
    font-size: .72rem;
    font-weight: 700;
    color: #3b82f6;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: .2rem;
}

.reg-title {
    font-weight: 700;
    font-size: .875rem;
    color: var(--slate-800);
    margin-bottom: .25rem;
}

.reg-desc { font-size: .8rem; color: var(--slate-500); line-height: 1.55; }

/* ============================================================
   CTA STRIP
============================================================ */
.edu-cta-strip {
    background: linear-gradient(135deg, var(--green-600), #14532d);
    border-radius: 22px;
    padding: 2.5rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    position: relative;
    overflow: hidden;
}

.edu-cta-strip::before {
    content: '';
    position: absolute;
    top: -50px; right: -50px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,.05);
    border-radius: 50%;
}

.edu-cta-strip::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 30%;
    width: 160px; height: 160px;
    background: rgba(163,230,53,.08);
    border-radius: 50%;
}

.edu-cta-text { position: relative; z-index: 1; }

.edu-cta-title {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 800;
    color: white;
    margin-bottom: .4rem;
}

.edu-cta-sub { color: #86efac; font-size: .9rem; }

.edu-cta-actions {
    position: relative;
    z-index: 1;
    display: flex;
    gap: .75rem;
    flex-shrink: 0;
    flex-wrap: wrap;
}

.btn-white {
    padding: .75rem 1.5rem;
    border-radius: 999px;
    background: white;
    color: var(--green-700);
    font-weight: 700;
    font-size: .9rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    transition: all .2s;
    box-shadow: 0 4px 16px rgba(0,0,0,.2);
}

.btn-white:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.25);
}

.btn-ghost-white {
    padding: .75rem 1.5rem;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
    border: 1.5px solid rgba(255,255,255,.25);
    color: white;
    font-weight: 700;
    font-size: .9rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    transition: all .2s;
}

.btn-ghost-white:hover {
    background: rgba(255,255,255,.2);
    border-color: rgba(255,255,255,.4);
}

/* ============================================================
   RESPONSIVE
============================================================ */
@media (max-width: 1024px) {
    .edu-body { grid-template-columns: 200px 1fr; gap: 1.75rem; }
    .waste-type-grid { grid-template-columns: repeat(2, 1fr); }
    .characteristic-grid { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 860px) {
    .edu-body { grid-template-columns: 1fr; }
    .edu-toc { display: none; }
    .edu-hero-inner { grid-template-columns: 1fr; }
    .edu-hero-stats { flex-direction: row; flex-wrap: wrap; }
    .edu-stat-card { flex: 1; min-width: 130px; }
}

@media (max-width: 640px) {
    .waste-type-grid { grid-template-columns: 1fr; }
    .characteristic-grid { grid-template-columns: repeat(2, 1fr); }
    .do-dont-grid { grid-template-columns: 1fr; }
    .facility-grid { grid-template-columns: 1fr; }
    .what-is-grid { grid-template-columns: 1fr; }
    .edu-cta-strip { flex-direction: column; text-align: center; }
    .edu-cta-actions { justify-content: center; }
    .section-card-body { padding: 1.25rem; }
    .section-card-head { padding: 1.25rem; }
}

@media (max-width: 480px) {
    .characteristic-grid { grid-template-columns: 1fr 1fr; }
}
</style>
@endpush

@section('content')
<div class="edu-page">

    <!-- ===== HERO ===== -->
    <div class="edu-hero">
        <div class="edu-hero-inner">
            <div>
                <div class="edu-hero-badge">
                    <i class="fas fa-graduation-cap"></i>
                    Pusat Edukasi
                </div>
                <h1 class="edu-hero-title">
                    Kenali Sampah <span>B3</span>,<br>
                    Jaga Lingkungan Kita
                </h1>
                <p class="edu-hero-sub">
                    Pelajari apa itu sampah Bahan Berbahaya dan Beracun (B3), mengapa berbahaya, dan bagaimana cara menanganinya dengan benar untuk melindungi kesehatan dan alam sekitar kita.
                </p>
                <div class="edu-quicknav">
                    <a class="qnav-pill" href="#apa-itu"><i class="fas fa-question-circle"></i> Apa itu B3?</a>
                    <a class="qnav-pill" href="#jenis"><i class="fas fa-tags"></i> Jenis B3</a>
                    <a class="qnav-pill" href="#bahaya"><i class="fas fa-skull-crossbones"></i> Bahayanya</a>
                    <a class="qnav-pill" href="#pembuangan"><i class="fas fa-recycle"></i> Cara Buang</a>
                    <a class="qnav-pill" href="#quiz"><i class="fas fa-brain"></i> Quiz</a>
                </div>
            </div>

            <div class="edu-hero-stats">
                <div class="edu-stat-card">
                    <div class="edu-stat-num">60<em>jt</em></div>
                    <div class="edu-stat-lbl">Ton sampah B3<br>dihasilkan Indonesia/tahun</div>
                </div>
                <div class="edu-stat-card">
                    <div class="edu-stat-num">70<em>%</em></div>
                    <div class="edu-stat-lbl">Tidak dikelola<br>dengan benar</div>
                </div>
                <div class="edu-stat-card">
                    <div class="edu-stat-num">30<em>+</em></div>
                    <div class="edu-stat-lbl">Penyakit serius<br>akibat paparan B3</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave -->
    <div class="edu-wave">
        <svg viewBox="0 0 1440 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:50px">
            <path d="M0,32 C240,56 480,8 720,32 C960,56 1200,8 1440,32 L1440,50 L0,50 Z" fill="#f8fafc"/>
        </svg>
    </div>

    <!-- ===== BODY ===== -->
    <div class="edu-body">

        <!-- TOC Sidebar -->
        <aside class="edu-toc" id="tocSidebar">
            <div class="toc-title"><i class="fas fa-list"></i> Isi Halaman</div>
            <ul class="toc-list">
                <li class="toc-item">
                    <a href="#apa-itu" data-section="apa-itu">
                        <span class="toc-icon"><i class="fas fa-question"></i></span>
                        Apa itu B3?
                    </a>
                </li>
                <li class="toc-item">
                    <a href="#jenis" data-section="jenis">
                        <span class="toc-icon"><i class="fas fa-tags"></i></span>
                        Jenis Sampah B3
                    </a>
                </li>
                <li class="toc-item">
                    <a href="#bahaya" data-section="bahaya">
                        <span class="toc-icon"><i class="fas fa-skull-crossbones"></i></span>
                        Bahaya B3
                    </a>
                </li>
                <li class="toc-item">
                    <a href="#pembuangan" data-section="pembuangan">
                        <span class="toc-icon"><i class="fas fa-recycle"></i></span>
                        Cara Pembuangan
                    </a>
                </li>
                <li class="toc-item">
                    <a href="#fasilitas" data-section="fasilitas">
                        <span class="toc-icon"><i class="fas fa-map-marker-alt"></i></span>
                        Lokasi Fasilitas
                    </a>
                </li>
                <li class="toc-item">
                    <a href="#quiz" data-section="quiz">
                        <span class="toc-icon"><i class="fas fa-brain"></i></span>
                        Quiz Edukasi
                    </a>
                </li>
                <li class="toc-item">
                    <a href="#regulasi" data-section="regulasi">
                        <span class="toc-icon"><i class="fas fa-gavel"></i></span>
                        Regulasi
                    </a>
                </li>
            </ul>

            <div class="toc-progress">
                <div class="toc-progress-label">
                    <span>Progress Baca</span>
                    <span id="readPct">0%</span>
                </div>
                <div class="toc-progress-track">
                    <div class="toc-progress-fill" id="readBar"></div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="edu-content">

            <!-- ===== 1. APA ITU B3? ===== -->
            <div class="edu-section section-card" id="apa-itu">
                <div class="section-card-head">
                    <div class="section-head-icon" style="background:#fef9c3;color:#92400e">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div>
                        <div class="section-head-label" style="color:#92400e">Pengenalan</div>
                        <div class="section-head-title">Apa itu Sampah B3?</div>
                    </div>
                </div>
                <div class="section-card-body">

                    <div class="definition-block">
                        <p><strong>B3 (Bahan Berbahaya dan Beracun)</strong> adalah zat, energi, dan/atau komponen lain yang karena sifat, konsentrasi, dan/atau jumlahnya, baik secara langsung maupun tidak langsung, dapat mencemarkan dan/atau merusak lingkungan hidup, membahayakan kesehatan manusia, kelangsungan hidup manusia serta makhluk hidup lain.</p>
                    </div>

                    <div class="what-is-grid">
                        <div class="what-box danger">
                            <div class="what-box-title"><i class="fas fa-biohazard"></i> Sampah B3</div>
                            <p>Mengandung zat kimia berbahaya, bersifat beracun, mudah terbakar, reaktif, korosif, atau infeksius yang dapat merusak lingkungan dan mengancam kesehatan manusia secara serius.</p>
                        </div>
                        <div class="what-box safe">
                            <div class="what-box-title"><i class="fas fa-leaf"></i> Sampah Non-B3</div>
                            <p>Sampah rumah tangga dan industri biasa yang tidak mengandung zat berbahaya dalam konsentrasi tinggi. Dapat didaur ulang, dikompos, atau dibuang melalui jalur pengelolaan sampah normal.</p>
                        </div>
                    </div>

                    <p style="font-size:.875rem;color:var(--slate-600);margin-bottom:1.1rem;line-height:1.7">Sampah B3 memiliki satu atau lebih karakteristik berikut ini:</p>

                    <div class="characteristic-grid">
                        <div class="char-chip" style="background:#fff7ed">
                            <div class="char-chip-icon" style="background:#ffedd5;color:#ea580c"><i class="fas fa-fire"></i></div>
                            <span>Mudah<br>Terbakar</span>
                        </div>
                        <div class="char-chip" style="background:#fef2f2">
                            <div class="char-chip-icon" style="background:#fee2e2;color:var(--red-600)"><i class="fas fa-skull-crossbones"></i></div>
                            <span>Beracun /<br>Toksik</span>
                        </div>
                        <div class="char-chip" style="background:#fdf4ff">
                            <div class="char-chip-icon" style="background:#f3e8ff;color:#9333ea"><i class="fas fa-atom"></i></div>
                            <span>Reaktif /<br>Eksplosif</span>
                        </div>
                        <div class="char-chip" style="background:#eff6ff">
                            <div class="char-chip-icon" style="background:#dbeafe;color:#2563eb"><i class="fas fa-biohazard"></i></div>
                            <span>Infeksius /<br>Biologis</span>
                        </div>
                        <div class="char-chip" style="background:#f0fdf4">
                            <div class="char-chip-icon" style="background:var(--green-100);color:var(--green-700)"><i class="fas fa-flask"></i></div>
                            <span>Korosif /<br>Asam/Basa</span>
                        </div>
                        <div class="char-chip" style="background:#fef9c3">
                            <div class="char-chip-icon" style="background:#fef08a;color:#ca8a04"><i class="fas fa-radiation"></i></div>
                            <span>Radioaktif /<br>Karsinogenik</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===== 2. JENIS B3 ===== -->
            <div class="edu-section section-card" id="jenis">
                <div class="section-card-head">
                    <div class="section-head-icon" style="background:#fef2f2;color:var(--red-600)">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div>
                        <div class="section-head-label" style="color:var(--red-600)">Klasifikasi</div>
                        <div class="section-head-title">Jenis-jenis Sampah B3</div>
                    </div>
                </div>
                <div class="section-card-body">

                    <p style="font-size:.875rem;color:var(--slate-500);margin-bottom:1.1rem;line-height:1.7">Sampah B3 terbagi menjadi beberapa kategori berdasarkan sumber dan jenis bahayanya. Klik kartu untuk informasi lebih lanjut.</p>

                    <div class="waste-filter-tabs">
                        <button class="wf-tab active" onclick="filterWaste('all', this)">Semua</button>
                        <button class="wf-tab" onclick="filterWaste('elektronik', this)">🖥️ Elektronik</button>
                        <button class="wf-tab" onclick="filterWaste('kimia', this)">🧪 Kimia</button>
                        <button class="wf-tab" onclick="filterWaste('medis', this)">💊 Medis</button>
                        <button class="wf-tab" onclick="filterWaste('baterai', this)">🔋 Baterai</button>
                    </div>

                    <div class="waste-type-grid" id="wasteTypeGrid">

                        <div class="waste-type-card elektronik" data-cat="elektronik" onclick="showWasteDetail('elektronik')">
                            <div class="wt-icon"><i class="fas fa-laptop"></i></div>
                            <div class="wt-name">E-Waste (Limbah Elektronik)</div>
                            <div class="wt-examples">Ponsel, laptop, TV, printer, baterai lithium, keyboard, mouse</div>
                            <span class="wt-danger-tag"><i class="fas fa-exclamation-triangle"></i> Timbal, Merkuri, Kadmium</span>
                        </div>

                        <div class="waste-type-card kimia" data-cat="kimia" onclick="showWasteDetail('kimia')">
                            <div class="wt-icon"><i class="fas fa-flask"></i></div>
                            <div class="wt-name">Limbah Kimia Rumah Tangga</div>
                            <div class="wt-examples">Pembersih toilet, pemutih, cat dinding, solvent, tiner, cairan aki</div>
                            <span class="wt-danger-tag"><i class="fas fa-exclamation-triangle"></i> Korosif, Beracun</span>
                        </div>

                        <div class="waste-type-card medis" data-cat="medis" onclick="showWasteDetail('medis')">
                            <div class="wt-icon"><i class="fas fa-pills"></i></div>
                            <div class="wt-name">Limbah Medis & Farmasi</div>
                            <div class="wt-examples">Obat kadaluarsa, jarum suntik, termometer merkuri, krim antibiotik</div>
                            <span class="wt-danger-tag"><i class="fas fa-exclamation-triangle"></i> Infeksius, Toksik</span>
                        </div>

                        <div class="waste-type-card baterai" data-cat="baterai" onclick="showWasteDetail('baterai')">
                            <div class="wt-icon"><i class="fas fa-battery-full"></i></div>
                            <div class="wt-name">Baterai & Aki Bekas</div>
                            <div class="wt-examples">Baterai AA/AAA, baterai kancing, aki mobil, baterai lithium-ion, powerbank</div>
                            <span class="wt-danger-tag"><i class="fas fa-exclamation-triangle"></i> Kadmium, Asam Sulfat</span>
                        </div>

                        <div class="waste-type-card cat" data-cat="kimia" onclick="showWasteDetail('cat')">
                            <div class="wt-icon"><i class="fas fa-paint-roller"></i></div>
                            <div class="wt-name">Cat, Pernis & Pestisida</div>
                            <div class="wt-examples">Cat minyak, pernis, thinner, insektisida, herbisida, fungisida</div>
                            <span class="wt-danger-tag"><i class="fas fa-exclamation-triangle"></i> Mudah Terbakar, Toksik</span>
                        </div>

                        <div class="waste-type-card pertanian" data-cat="kimia" onclick="showWasteDetail('pertanian')">
                            <div class="wt-icon"><i class="fas fa-seedling"></i></div>
                            <div class="wt-name">Limbah Pertanian</div>
                            <div class="wt-examples">Kemasan pestisida, pupuk kimia bekas, alat penyemprot, wadah herbisida</div>
                            <span class="wt-danger-tag"><i class="fas fa-exclamation-triangle"></i> Kontaminasi Tanah</span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ===== 3. BAHAYA B3 ===== -->
            <div class="edu-section section-card" id="bahaya">
                <div class="section-card-head">
                    <div class="section-head-icon" style="background:#fef2f2;color:var(--red-600)">
                        <i class="fas fa-skull-crossbones"></i>
                    </div>
                    <div>
                        <div class="section-head-label" style="color:var(--red-600)">Dampak Negatif</div>
                        <div class="section-head-title">Bahaya Sampah B3 bagi Manusia & Lingkungan</div>
                    </div>
                </div>
                <div class="section-card-body">

                    <div class="danger-timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"><i class="fas fa-circle"></i></div>
                            <div class="timeline-head">
                                <i class="fas fa-brain" style="color:var(--red-500)"></i>
                                Gangguan Sistem Saraf
                            </div>
                            <div class="timeline-body">Logam berat seperti timbal (Pb) dan merkuri (Hg) dari baterai dan lampu neon rusak dapat merusak sistem saraf pusat. Paparan kronis menyebabkan penurunan IQ pada anak-anak, tremor, hingga kelumpuhan. Di Indonesia, kasus keracunan timbal sering ditemukan di sekitar TPA liar.</div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-head">
                                <i class="fas fa-lungs" style="color:#ea580c"></i>
                                Penyakit Saluran Napas & Kanker
                            </div>
                            <div class="timeline-body">Pembakaran sampah B3 secara sembarangan menghasilkan dioksin dan furan — senyawa karsinogenik kelas satu. Menghirup asapnya meningkatkan risiko kanker paru-paru, kanker hati, dan gangguan hormon reproduksi secara signifikan.</div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-head">
                                <i class="fas fa-tint" style="color:var(--amber-500)"></i>
                                Pencemaran Air & Tanah
                            </div>
                            <div class="timeline-body">Bahan kimia dari limbah B3 yang terbuang ke tanah meresap ke air tanah dan sungai. Kadmium dari baterai, misalnya, hanya butuh 1 baterai AA untuk mencemari 600.000 liter air. Racun ini masuk ke rantai makanan manusia melalui ikan dan sayuran.</div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-head">
                                <i class="fas fa-fish" style="color:#16a34a"></i>
                                Kerusakan Ekosistem & Kepunahan
                            </div>
                            <div class="timeline-body">Pencemaran logam berat dan bahan kimia berbahaya mematikan organisme mikro, fitoplankton, dan rantai makanan air. Ini berdampak langsung pada kepunahan spesies lokal, kerusakan terumbu karang, dan menurunnya populasi ikan di perairan Indonesia.</div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-head">
                                <i class="fas fa-child" style="color:#7c3aed"></i>
                                Dampak Khusus pada Anak-anak & Ibu Hamil
                            </div>
                            <div class="timeline-body">Anak-anak 10× lebih rentan terhadap paparan B3 karena sistem imun dan saraf mereka masih berkembang. Pada ibu hamil, paparan B3 meningkatkan risiko keguguran, cacat lahir, dan gangguan perkembangan kognitif bayi secara permanen.</div>
                        </div>
                    </div>

                    <div class="danger-alert-box">
                        <div class="danger-alert-icon"><i class="fas fa-exclamation"></i></div>
                        <div class="danger-alert-text">
                            <strong>⚠️ Peringatan Penting!</strong>
                            Jangan pernah membakar, mengubur, atau membuang sampah B3 ke selokan/sungai. Tindakan ini bukan hanya merusak lingkungan, tetapi juga merupakan pelanggaran hukum yang dapat dikenai sanksi pidana berdasarkan UU No. 32 Tahun 2009 tentang Perlindungan dan Pengelolaan Lingkungan Hidup.
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===== 4. CARA PEMBUANGAN ===== -->
            <div class="edu-section section-card" id="pembuangan">
                <div class="section-card-head">
                    <div class="section-head-icon" style="background:var(--green-50);color:var(--green-700)">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <div>
                        <div class="section-head-label" style="color:var(--green-700)">Panduan Praktis</div>
                        <div class="section-head-title">Cara Pembuangan Sampah B3 yang Benar</div>
                    </div>
                </div>
                <div class="section-card-body">

                    <p style="font-size:.875rem;color:var(--slate-500);margin-bottom:1.25rem;line-height:1.7">Ikuti langkah-langkah berikut untuk mengelola sampah B3 dengan aman dan bertanggung jawab:</p>

                    <div class="disposal-steps">

                        <div class="disposal-step">
                            <div class="step-num">1</div>
                            <div class="step-icon"><i class="fas fa-search"></i></div>
                            <div class="step-content">
                                <div class="step-title">Identifikasi Jenis Sampah</div>
                                <div class="step-desc">Periksa label kemasan untuk simbol bahaya (tengkorak, api, korosi). Gunakan aplikasi WasteGuard untuk deteksi otomatis. Pisahkan sampah B3 dari sampah biasa sejak awal.</div>
                                <span class="step-tag tips"><i class="fas fa-lightbulb"></i> Gunakan WasteGuard untuk identifikasi!</span>
                            </div>
                        </div>

                        <div class="disposal-step">
                            <div class="step-num">2</div>
                            <div class="step-icon"><i class="fas fa-box"></i></div>
                            <div class="step-content">
                                <div class="step-title">Simpan dalam Wadah Tertutup & Berlabel</div>
                                <div class="step-desc">Gunakan wadah asli atau kontainer kedap udara. Beri label "SAMPAH B3 — JANGAN DIBUANG SEMBARANGAN". Simpan di tempat kering, sejuk, jauh dari jangkauan anak-anak dan sumber api.</div>
                                <span class="step-tag penting"><i class="fas fa-exclamation"></i> Penting: Jangan campur jenis berbeda</span>
                            </div>
                        </div>

                        <div class="disposal-step">
                            <div class="step-num">3</div>
                            <div class="step-icon"><i class="fas fa-hand-paper"></i></div>
                            <div class="step-content">
                                <div class="step-title">Gunakan APD saat Menangani</div>
                                <div class="step-desc">Pakai sarung tangan karet tebal, masker, dan kacamata pelindung saat mengangkut atau menangani sampah B3. Cuci tangan hingga bersih sesudahnya. Jangan makan/minum saat menangani B3.</div>
                                <span class="step-tag wajib"><i class="fas fa-hard-hat"></i> Wajib: APD selalu digunakan</span>
                            </div>
                        </div>

                        <div class="disposal-step">
                            <div class="step-num">4</div>
                            <div class="step-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="step-content">
                                <div class="step-title">Bawa ke Fasilitas B3 Resmi</div>
                                <div class="step-desc">Antar ke TPS3R (Tempat Pengolahan Sampah 3R), drop box bank sampah, atau event pengumpulan e-waste. Beberapa merek elektronik (Samsung, Apple, Xiaomi) memiliki program take-back gratis di toko resmi.</div>
                                <span class="step-tag tips"><i class="fas fa-map"></i> Lihat lokasi di tab Fasilitas</span>
                            </div>
                        </div>

                        <div class="disposal-step">
                            <div class="step-num">5</div>
                            <div class="step-icon"><i class="fas fa-phone"></i></div>
                            <div class="step-content">
                                <div class="step-title">Hubungi Instansi Terkait</div>
                                <div class="step-desc">Untuk jumlah besar atau tidak tahu cara menanganinya, hubungi Dinas Lingkungan Hidup setempat atau KLHK. Layanan pengangkutan B3 tersedia di banyak kota besar secara gratis untuk masyarakat.</div>
                                <span class="step-tag penting"><i class="fas fa-phone"></i> KLHK: 021-5720214</span>
                            </div>
                        </div>

                    </div>

                    <!-- Do & Don't -->
                    <div class="do-dont-grid">
                        <div class="do-card">
                            <h4><i class="fas fa-check-circle"></i> Lakukan (DO ✅)</h4>
                            <ul class="do-list">
                                <li><i class="fas fa-check"></i> Pisahkan B3 dari sampah rumah tangga biasa</li>
                                <li><i class="fas fa-check"></i> Simpan dalam wadah tertutup dan berlabel</li>
                                <li><i class="fas fa-check"></i> Manfaatkan program daur ulang resmi</li>
                                <li><i class="fas fa-check"></i> Pakai APD saat menangani B3</li>
                                <li><i class="fas fa-check"></i> Lapor ke RT/RW jika ada tumpahan B3</li>
                                <li><i class="fas fa-check"></i> Ajarkan anak mengenal simbol bahaya B3</li>
                            </ul>
                        </div>
                        <div class="dont-card">
                            <h4><i class="fas fa-times-circle"></i> Jangan Lakukan (DON'T ❌)</h4>
                            <ul class="dont-list">
                                <li><i class="fas fa-times"></i> Membuang ke tempat sampah biasa</li>
                                <li><i class="fas fa-times"></i> Membakar sampah B3 di halaman rumah</li>
                                <li><i class="fas fa-times"></i> Menuangkan cairan B3 ke saluran air</li>
                                <li><i class="fas fa-times"></i> Mengubur dalam tanah secara sembarangan</li>
                                <li><i class="fas fa-times"></i> Mencampur berbagai jenis B3 bersama</li>
                                <li><i class="fas fa-times"></i> Menyimpan di dekat makanan atau anak-anak</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===== 5. FASILITAS ===== -->
            <div class="edu-section section-card" id="fasilitas">
                <div class="section-card-head">
                    <div class="section-head-icon" style="background:#eff6ff;color:#3b82f6">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div class="section-head-label" style="color:#3b82f6">Lokasi Terdekat</div>
                        <div class="section-head-title">Fasilitas Pengolahan Sampah B3</div>
                    </div>
                </div>
                <div class="section-card-body">

                    <div class="facility-search">
                        <input type="text" id="facilitySearch" placeholder="🔍  Cari kota atau kecamatan..." oninput="searchFacility(this.value)">
                        <button class="btn-search" onclick="detectLocation()">
                            <i class="fas fa-location-arrow"></i> Lokasi Saya
                        </button>
                    </div>

                    <div class="facility-grid" id="facilityGrid">

                        <div class="facility-card">
                            <div class="facility-header">
                                <div class="facility-icon"><i class="fas fa-recycle"></i></div>
                                <div>
                                    <div class="facility-name">TPS3R Tebet Barat</div>
                                    <div class="facility-type">Tempat Pengolahan Sampah 3R</div>
                                </div>
                            </div>
                            <div class="facility-info"><i class="fas fa-map-pin"></i> Tebet, Jakarta Selatan</div>
                            <div style="display:flex;align-items:center;gap:.5rem;margin-top:.4rem">
                                <span class="facility-distance"><i class="fas fa-walking"></i> 1.2 km</span>
                                <span style="font-size:.72rem;color:var(--green-600);font-weight:600">● Buka hari ini</span>
                            </div>
                        </div>

                        <div class="facility-card">
                            <div class="facility-header">
                                <div class="facility-icon" style="background:#eff6ff;color:#3b82f6"><i class="fas fa-university"></i></div>
                                <div>
                                    <div class="facility-name">Bank Sampah Induk DKI</div>
                                    <div class="facility-type">Bank Sampah Elektronik</div>
                                </div>
                            </div>
                            <div class="facility-info"><i class="fas fa-map-pin"></i> Cakung, Jakarta Timur</div>
                            <div style="display:flex;align-items:center;gap:.5rem;margin-top:.4rem">
                                <span class="facility-distance"><i class="fas fa-car"></i> 8.5 km</span>
                                <span style="font-size:.72rem;color:var(--green-600);font-weight:600">● Buka hari ini</span>
                            </div>
                        </div>

                        <div class="facility-card">
                            <div class="facility-header">
                                <div class="facility-icon" style="background:#fff7ed;color:#ea580c"><i class="fas fa-industry"></i></div>
                                <div>
                                    <div class="facility-name">PT Prasadha Pamunah Limbah</div>
                                    <div class="facility-type">Pengolah B3 Bersertifikat</div>
                                </div>
                            </div>
                            <div class="facility-info"><i class="fas fa-map-pin"></i> Bekasi, Jawa Barat</div>
                            <div style="display:flex;align-items:center;gap:.5rem;margin-top:.4rem">
                                <span class="facility-distance"><i class="fas fa-car"></i> 22 km</span>
                                <span style="font-size:.72rem;color:#f59e0b;font-weight:600">● Perlu janji</span>
                            </div>
                        </div>

                        <div class="facility-card">
                            <div class="facility-header">
                                <div class="facility-icon" style="background:#fdf4ff;color:#9333ea"><i class="fas fa-store"></i></div>
                                <div>
                                    <div class="facility-name">Drop Box E-Waste iBox</div>
                                    <div class="facility-type">Program Take-Back Elektronik</div>
                                </div>
                            </div>
                            <div class="facility-info"><i class="fas fa-map-pin"></i> Mall Pondok Indah, Jakarta</div>
                            <div style="display:flex;align-items:center;gap:.5rem;margin-top:.4rem">
                                <span class="facility-distance"><i class="fas fa-car"></i> 5.3 km</span>
                                <span style="font-size:.72rem;color:var(--green-600);font-weight:600">● Buka 10.00–22.00</span>
                            </div>
                        </div>

                    </div>

                    <div class="map-placeholder" onclick="openMap()">
                        <i class="fas fa-map-marked-alt"></i>
                        <p>Buka Peta Interaktif — Temukan fasilitas B3 terdekat</p>
                        <span style="font-size:.78rem;color:var(--green-500);font-weight:500">Klik untuk buka Google Maps →</span>
                    </div>

                </div>
            </div>

            <!-- ===== 6. QUIZ ===== -->
            <div class="edu-section section-card" id="quiz">
                <div class="section-card-head">
                    <div class="section-head-icon" style="background:linear-gradient(135deg,#ede9fe,#ddd6fe);color:#7c3aed">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div>
                        <div class="section-head-label" style="color:#7c3aed">Uji Pengetahuan</div>
                        <div class="section-head-title">Quiz Edukasi Sampah B3</div>
                    </div>
                </div>
                <div class="section-card-body">

                    <!-- Quiz App -->
                    <div id="quizApp">
                        <div class="quiz-progress">
                            <div class="quiz-progress-track">
                                <div class="quiz-progress-fill" id="quizBar"></div>
                            </div>
                            <div class="quiz-progress-label" id="quizProgressLabel">Soal 1 / 7</div>
                        </div>

                        <div class="quiz-question-box">
                            <div class="quiz-q-num" id="quizQNum">Soal 1</div>
                            <div class="quiz-q-text" id="quizQText"></div>
                        </div>

                        <div class="quiz-options" id="quizOptions"></div>

                        <div class="quiz-feedback" id="quizFeedback">
                            <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:.15rem"></i>
                            <span id="quizFeedbackText"></span>
                        </div>

                        <div class="quiz-nav">
                            <div class="quiz-score-badge" id="quizScoreBadge">Skor: 0</div>
                            <div style="display:flex;gap:.6rem">
                                <button class="btn-quiz-nav btn-quiz-reset" onclick="resetQuiz()">
                                    <i class="fas fa-redo"></i> Ulang
                                </button>
                                <button class="btn-quiz-nav btn-quiz-next" id="quizNextBtn" onclick="nextQuestion()" disabled>
                                    Lanjut <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quiz Result -->
                    <div class="quiz-result-screen" id="quizResult">
                        <span class="result-trophy" id="resultEmoji">🏆</span>
                        <div class="result-score-circle">
                            <div class="result-score-num" id="resultScore">0</div>
                            <div class="result-score-lbl">/ 7 Benar</div>
                        </div>
                        <div class="result-title" id="resultTitle">Luar Biasa!</div>
                        <div class="result-sub" id="resultSub">Kamu sudah paham tentang sampah B3.</div>
                        <div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap">
                            <button class="btn-quiz-nav btn-quiz-next" onclick="resetQuiz()">
                                <i class="fas fa-redo"></i> Coba Lagi
                            </button>
                            <a href="{{ url('/deteksi') }}" class="btn-quiz-nav" style="background:var(--green-50);color:var(--green-700);border:1.5px solid rgba(34,197,94,.25)">
                                <i class="fas fa-camera"></i> Coba Deteksi
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ===== 7. REGULASI ===== -->
            <div class="edu-section section-card" id="regulasi">
                <div class="section-card-head">
                    <div class="section-head-icon" style="background:#eff6ff;color:#2563eb">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <div>
                        <div class="section-head-label" style="color:#2563eb">Dasar Hukum</div>
                        <div class="section-head-title">Regulasi Pengelolaan B3 di Indonesia</div>
                    </div>
                </div>
                <div class="section-card-body">

                    <p style="font-size:.875rem;color:var(--slate-500);margin-bottom:1.25rem;line-height:1.7">
                        Pengelolaan sampah B3 di Indonesia diatur oleh sejumlah peraturan perundang-undangan yang mengikat bagi masyarakat dan pelaku industri:
                    </p>

                    <div class="regulation-list">

                        <div class="regulation-item">
                            <div class="reg-icon"><i class="fas fa-scroll"></i></div>
                            <div>
                                <div class="reg-code">UU No. 32 / 2009</div>
                                <div class="reg-title">Perlindungan dan Pengelolaan Lingkungan Hidup</div>
                                <div class="reg-desc">Undang-undang payung yang mengatur perlindungan lingkungan hidup secara menyeluruh, termasuk larangan pembuangan limbah B3 secara sembarangan dengan ancaman pidana penjara hingga 10 tahun.</div>
                            </div>
                        </div>

                        <div class="regulation-item">
                            <div class="reg-icon"><i class="fas fa-file-alt"></i></div>
                            <div>
                                <div class="reg-code">PP No. 22 / 2021</div>
                                <div class="reg-title">Penyelenggaraan Perlindungan dan Pengelolaan Lingkungan</div>
                                <div class="reg-desc">Peraturan turunan yang mengatur teknis pengelolaan B3, termasuk tata cara penyimpanan, pengangkutan, pengolahan, dan penimbunan limbah B3 yang wajib dilengkapi dokumen dan izin khusus.</div>
                            </div>
                        </div>

                        <div class="regulation-item">
                            <div class="reg-icon"><i class="fas fa-file-alt"></i></div>
                            <div>
                                <div class="reg-code">PerMen LHK No. P.12 / 2020</div>
                                <div class="reg-title">Penyimpanan Limbah B3</div>
                                <div class="reg-desc">Mengatur secara khusus persyaratan teknis tempat penyimpanan sementara limbah B3, meliputi desain bangunan, sistem ventilasi, penanganan tumpahan, dan persyaratan perizinan yang wajib dipenuhi.</div>
                            </div>
                        </div>

                        <div class="regulation-item">
                            <div class="reg-icon"><i class="fas fa-recycle"></i></div>
                            <div>
                                <div class="reg-code">UU No. 18 / 2008</div>
                                <div class="reg-title">Pengelolaan Sampah</div>
                                <div class="reg-desc">Mewajibkan masyarakat untuk memilah sampah dari sumber, termasuk memisahkan sampah B3 dari rumah tangga. Pemerintah daerah wajib menyediakan fasilitas pengelolaan sampah B3 rumah tangga yang memadai.</div>
                            </div>
                        </div>

                        <div class="regulation-item">
                            <div class="reg-icon" style="background:#fef9c3;color:#ca8a04"><i class="fas fa-exclamation-triangle"></i></div>
                            <div>
                                <div class="reg-code" style="color:#ca8a04">Sanksi Hukum</div>
                                <div class="reg-title">Ancaman Pidana Pembuangan Limbah B3</div>
                                <div class="reg-desc">Pelanggaran pembuangan limbah B3 dapat dikenai sanksi pidana penjara <strong>1–10 tahun</strong> dan denda <strong>Rp 500 juta – Rp 10 miliar</strong> sesuai Pasal 103 UU No. 32/2009. Bagi industri, izin usaha dapat dicabut secara permanen.</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- CTA Strip -->
            <div class="edu-cta-strip">
                <div class="edu-cta-text">
                    <div class="edu-cta-title">♻️ Mulai Deteksi Sampah Sekarang</div>
                    <div class="edu-cta-sub">Sekarang kamu sudah tahu — saatnya bertindak. Gunakan WasteGuard untuk identifikasi sampah di sekitarmu.</div>
                </div>
                <div class="edu-cta-actions">
                    <a href="{{ url('/deteksi') }}" class="btn-white">
                        <i class="fas fa-camera"></i> Buka Deteksi
                    </a>
                    <a href="{{ url('/riwayat') }}" class="btn-ghost-white">
                        <i class="fas fa-history"></i> Lihat Riwayat
                    </a>
                </div>
            </div>

        </div><!-- /edu-content -->
    </div><!-- /edu-body -->
</div><!-- /edu-page -->

<!-- ===== WASTE DETAIL MODAL ===== -->
<div class="modal-overlay" id="wasteModal" onclick="closeWasteModal(event)">
    <div class="modal-box" style="max-width:480px">
        <div class="modal-header">
            <div class="modal-title" id="wModalTitle">Detail Jenis B3</div>
            <button class="modal-close" onclick="closeWasteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="wModalBody"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ============================================================
   SCROLL SPY — TOC
============================================================ */
const sections  = document.querySelectorAll('.edu-section');
const tocLinks  = document.querySelectorAll('.toc-item a');

const scrollSpy = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const id = entry.target.id;
            tocLinks.forEach(a => {
                a.classList.toggle('active', a.dataset.section === id);
            });
        }
    });
}, { rootMargin: '-10% 0px -80% 0px' });

sections.forEach(s => scrollSpy.observe(s));

/* Read progress bar */
window.addEventListener('scroll', () => {
    const doc = document.documentElement;
    const pct = (doc.scrollTop / (doc.scrollHeight - doc.clientHeight)) * 100;
    const val = Math.round(Math.min(pct, 100));
    document.getElementById('readBar').style.width = val + '%';
    document.getElementById('readPct').textContent = val + '%';
});

/* ============================================================
   WASTE FILTER TABS
============================================================ */
function filterWaste(cat, btn) {
    document.querySelectorAll('.wf-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.waste-type-card').forEach(card => {
        if (cat === 'all' || card.dataset.cat === cat) {
            card.style.display = '';
            card.style.animation = 'fadeSlideIn .3s ease both';
        } else {
            card.style.display = 'none';
        }
    });
}

/* ============================================================
   WASTE DETAIL MODAL
============================================================ */
const wasteDetails = {
    elektronik: {
        icon: '🖥️', title: 'E-Waste (Limbah Elektronik)',
        desc: 'Limbah elektronik atau e-waste adalah kategori sampah B3 yang paling cepat berkembang di dunia. Indonesia menghasilkan lebih dari 2 juta ton e-waste setiap tahunnya.',
        dangers: ['Timbal (Pb) — kerusakan otak dan ginjal', 'Merkuri (Hg) — merusak sistem saraf', 'Kadmium (Cd) — kanker ginjal dan tulang', 'Brom — gangguan hormon tiroid'],
        actions: ['Kembalikan ke toko resmi (program take-back)', 'Bank sampah elektronik', 'Donasikan jika masih berfungsi', 'Jangan dibongkar sendiri tanpa APD'],
        color: '#3b82f6'
    },
    kimia: {
        icon: '🧪', title: 'Limbah Kimia Rumah Tangga',
        desc: 'Produk-produk pembersih rumah tangga, cat, dan pelarut mengandung bahan kimia berbahaya yang sering tidak disadari oleh masyarakat awam.',
        dangers: ['VOC (Volatile Organic Compounds) — iritasi paru-paru', 'Klorin — kerusakan saluran napas', 'Solvent — kerusakan hati dan saraf', 'Fosfor — racun akut bila tertelan'],
        actions: ['Habiskan atau berikan kepada yang membutuhkan', 'Bawa ke event pengumpulan limbah B3', 'Simpan tertutup rapat di tempat berventilasi', 'Jangan tuang ke saluran air'],
        color: '#9333ea'
    },
    medis: {
        icon: '💊', title: 'Limbah Medis & Farmasi',
        desc: 'Obat-obatan kadaluarsa dan peralatan medis bekas mengandung senyawa aktif yang berbahaya jika masuk ke lingkungan atau dikonsumsi sembarangan.',
        dangers: ['Antibiotik terbuang — resistansi bakteri', 'Hormon sintetis — gangguan reproduksi', 'Jarum suntik — penularan penyakit infeksi', 'Bahan radioaktif — kerusakan DNA'],
        actions: ['Kembalikan obat sisa ke apotek/puskesmas', 'Program "Obat Kembali" di Kimia Farma', 'Jangan siram ke toilet atau wastafel', 'Jarum suntik: wadah khusus sharps container'],
        color: '#ea580c'
    },
    baterai: {
        icon: '🔋', title: 'Baterai & Aki Bekas',
        desc: 'Satu baterai AA mengandung cukup kadmium untuk mencemari 600.000 liter air tanah. Indonesia membuang miliaran baterai per tahun tanpa penanganan khusus.',
        dangers: ['Kadmium — kanker dan kerusakan ginjal', 'Timbal — kerusakan sistem saraf anak', 'Asam sulfat (aki) — korosif/membakar kulit', 'Nikel — alergi dan karsinogenik'],
        actions: ['Drop box baterai di supermarket/toko elektronik', 'Program daur ulang brand (Panasonic, Energizer)', 'Aki bekas → bengkel resmi atau pengepul bersertifikat', 'Jangan dibongkar atau dibakar'],
        color: '#ca8a04'
    },
    cat: {
        icon: '🎨', title: 'Cat, Pernis & Pestisida',
        desc: 'Cat berbasis solvent dan pestisida mengandung bahan aktif toksik tinggi yang dapat meresap ke tanah dan mencemari air tanah dalam waktu singkat.',
        dangers: ['Toluena & xilena — merusak hati & ginjal', 'Arsenik (pestisida) — karsinogenik kelas 1', 'Timbal (cat lama) — kerusakan saraf', 'Kloropirifos — berbahaya bagi otak bayi'],
        actions: ['Gunakan habis atau bagikan ke tetangga/komunitas', 'Keringkan cat sebelum dibuang ke tempat B3', 'Kembalikan kemasan pestisida ke distributor', 'Cari solusi ramah lingkungan (cat berbasis air)'],
        color: '#dc2626'
    },
    pertanian: {
        icon: '🌱', title: 'Limbah Pertanian',
        desc: 'Kemasan pestisida, herbisida, dan pupuk kimia yang dibuang sembarangan menjadi sumber pencemaran terbesar di lahan pertanian dan daerah aliran sungai.',
        dangers: ['Residu pestisida — kontaminasi rantai makanan', 'Nitrat berlebih (pupuk) — eutrofikasi air', 'Logam berat dalam pupuk — akumulasi di tanah', 'Endosulfan — gangguan hormon & reproduksi'],
        actions: ['Cuci triple-rinse kemasan sebelum dibuang', 'Kumpulkan di program PUAP (kios pertanian)', 'Ikuti pelatihan pertanian organik/berkelanjutan', 'Laporkan ke Dinas Pertanian setempat'],
        color: '#16a34a'
    }
};

function showWasteDetail(type) {
    const d = wasteDetails[type];
    if (!d) return;

    document.getElementById('wModalTitle').innerHTML = `${d.icon} ${d.title}`;
    document.getElementById('wModalBody').innerHTML = `
        <p style="font-size:.875rem;color:var(--slate-600);line-height:1.7;margin-bottom:1.25rem">${d.desc}</p>

        <div style="margin-bottom:1.25rem">
            <div style="font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:var(--red-600);margin-bottom:.65rem;display:flex;align-items:center;gap:.4rem">
                <i class="fas fa-skull-crossbones"></i> Kandungan Berbahaya
            </div>
            <div style="display:flex;flex-direction:column;gap:.4rem">
                ${d.dangers.map(x => `<div style="display:flex;align-items:center;gap:.6rem;font-size:.82rem;color:var(--slate-700)"><i class="fas fa-dot-circle" style="color:var(--red-400);font-size:.6rem;flex-shrink:0"></i>${x}</div>`).join('')}
            </div>
        </div>

        <div>
            <div style="font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:var(--green-700);margin-bottom:.65rem;display:flex;align-items:center;gap:.4rem">
                <i class="fas fa-check-circle"></i> Cara Penanganan
            </div>
            <div style="display:flex;flex-direction:column;gap:.4rem">
                ${d.actions.map(x => `<div style="display:flex;align-items:flex-start;gap:.6rem;font-size:.82rem;color:var(--slate-700)"><i class="fas fa-arrow-right" style="color:var(--green-500);font-size:.65rem;margin-top:.25rem;flex-shrink:0"></i>${x}</div>`).join('')}
            </div>
        </div>

        <div style="margin-top:1.25rem;text-align:center">
            <a href="{{ url('/deteksi') }}" style="display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.4rem;border-radius:999px;background:linear-gradient(135deg,var(--green-500),var(--green-600));color:white;font-weight:700;font-size:.85rem;text-decoration:none;box-shadow:0 4px 14px rgba(34,197,94,.35)">
                <i class="fas fa-camera"></i> Deteksi ${d.title.split(' ')[0]} Sekarang
            </a>
        </div>`;
    document.getElementById('wasteModal').classList.add('open');
}

function closeWasteModal(e) {
    if (e && e.target !== document.getElementById('wasteModal')) return;
    document.getElementById('wasteModal').classList.remove('open');
}

/* ============================================================
   QUIZ ENGINE
============================================================ */
const QUIZ_QUESTIONS = [
    {
        q: "Manakah dari berikut ini yang TERMASUK sampah B3?",
        opts: ["Botol plastik bekas", "Baterai AA bekas", "Kardus pizza", "Kulit buah pisang"],
        ans: 1,
        explain: "Baterai bekas mengandung kadmium, timbal, dan merkuri — bahan kimia berbahaya yang dapat mencemari tanah dan air tanah. Wajib dibuang di tempat khusus B3."
    },
    {
        q: "Berapa liter air tanah yang dapat tercemar oleh 1 buah baterai AA bekas?",
        opts: ["1.000 liter", "10.000 liter", "600.000 liter", "50.000 liter"],
        ans: 2,
        explain: "Satu baterai AA mengandung cukup kadmium untuk mencemari hingga 600.000 liter air tanah! Ini adalah alasan utama mengapa baterai harus dibuang di tempat khusus B3."
    },
    {
        q: "Apa kepanjangan dari B3 dalam konteks pengelolaan sampah di Indonesia?",
        opts: ["Barang Beracun Berbahaya", "Bahan Berbahaya dan Beracun", "Benda Berbahaya dan Berisiko", "Bahan Buangan Berat"],
        ans: 1,
        explain: "B3 adalah singkatan dari Bahan Berbahaya dan Beracun, sesuai dengan PP No. 101 Tahun 2014 dan berbagai regulasi lingkungan hidup di Indonesia."
    },
    {
        q: "Apa yang HARUS dilakukan jika menemukan obat-obatan kadaluarsa di rumah?",
        opts: ["Siram ke toilet", "Buang ke tempat sampah biasa", "Kembalikan ke apotek atau puskesmas", "Kubur di halaman rumah"],
        ans: 2,
        explain: "Obat kadaluarsa harus dikembalikan ke apotek, puskesmas, atau fasilitas kesehatan. Banyak apotek memiliki program pengumpulan obat sisa. Jangan disiram ke toilet karena mencemari air."
    },
    {
        q: "Simbol ini ☠️ pada kemasan produk menandakan bahwa produk tersebut bersifat...",
        opts: ["Mudah terbakar (flammable)", "Beracun (toxic)", "Radioaktif", "Korosif"],
        ans: 1,
        explain: "Simbol tengkorak dan tulang bersilang (☠️) adalah simbol universal untuk zat BERACUN (toxic). Artinya produk dapat menyebabkan kematian atau cedera serius jika ditelan, dihirup, atau menyentuh kulit."
    },
    {
        q: "Undang-undang mana yang mengatur sanksi pidana bagi pembuang limbah B3 secara sembarangan?",
        opts: ["UU No. 18 Tahun 2008", "UU No. 32 Tahun 2009", "PP No. 22 Tahun 2021", "UU No. 11 Tahun 2020"],
        ans: 1,
        explain: "UU No. 32 Tahun 2009 tentang Perlindungan dan Pengelolaan Lingkungan Hidup mengatur sanksi pidana 1–10 tahun penjara dan denda Rp 500 juta – Rp 10 miliar bagi pelanggar pembuangan limbah B3."
    },
    {
        q: "Tempat yang TEPAT untuk membuang ponsel rusak atau laptop lama adalah...",
        opts: ["Tempat sampah di jalan", "Selokan atau got", "Drop box e-waste atau bank sampah elektronik", "Dibakar di halaman rumah"],
        ans: 2,
        explain: "Ponsel dan laptop termasuk e-waste (limbah elektronik B3). Cara terbaik: bawa ke drop box e-waste di pusat perbelanjaan, toko elektronik resmi, atau bank sampah yang melayani e-waste."
    }
];

let currentQ   = 0;
let score      = 0;
let answered   = false;

function initQuiz() {
    currentQ = 0; score = 0; answered = false;
    document.getElementById('quizApp').style.display = '';
    document.getElementById('quizResult').classList.remove('show');
    renderQuestion();
}

function renderQuestion() {
    const q = QUIZ_QUESTIONS[currentQ];
    const total = QUIZ_QUESTIONS.length;

    document.getElementById('quizBar').style.width = `${((currentQ) / total) * 100}%`;
    document.getElementById('quizProgressLabel').textContent = `Soal ${currentQ + 1} / ${total}`;
    document.getElementById('quizQNum').textContent = `Soal ${currentQ + 1}`;
    document.getElementById('quizQText').textContent = q.q;
    document.getElementById('quizScoreBadge').textContent = `Skor: ${score}`;
    document.getElementById('quizNextBtn').disabled = true;

    const fb = document.getElementById('quizFeedback');
    fb.classList.remove('show', 'correct-fb', 'wrong-fb');

    const letters = ['A', 'B', 'C', 'D'];
    const optWrap = document.getElementById('quizOptions');
    optWrap.innerHTML = '';
    q.opts.forEach((opt, i) => {
        const btn = document.createElement('button');
        btn.className = 'quiz-opt';
        btn.innerHTML = `<span class="quiz-opt-letter">${letters[i]}</span>${opt}`;
        btn.onclick = () => selectAnswer(i, btn);
        optWrap.appendChild(btn);
    });

    answered = false;
}

function selectAnswer(idx, btn) {
    if (answered) return;
    answered = true;

    const q       = QUIZ_QUESTIONS[currentQ];
    const correct = idx === q.ans;
    const allBtns = document.querySelectorAll('.quiz-opt');

    allBtns.forEach((b, i) => {
        b.disabled = true;
        if (i === q.ans) b.classList.add('correct');
        if (i === idx && !correct) b.classList.add('wrong');
    });

    if (correct) score++;

    // feedback
    const fb = document.getElementById('quizFeedback');
    const ft = document.getElementById('quizFeedbackText');
    fb.classList.add('show', correct ? 'correct-fb' : 'wrong-fb');
    ft.textContent = (correct ? '✅ Benar! ' : '❌ Kurang tepat. ') + q.explain;

    document.getElementById('quizNextBtn').disabled = false;
    document.getElementById('quizScoreBadge').textContent = `Skor: ${score}`;

    // last question
    if (currentQ === QUIZ_QUESTIONS.length - 1) {
        document.getElementById('quizNextBtn').innerHTML = 'Lihat Hasil <i class="fas fa-trophy"></i>';
    }
}

function nextQuestion() {
    currentQ++;
    if (currentQ >= QUIZ_QUESTIONS.length) {
        showResult(); return;
    }
    renderQuestion();
}

function showResult() {
    document.getElementById('quizBar').style.width = '100%';
    document.getElementById('quizApp').style.display = 'none';
    document.getElementById('quizResult').classList.add('show');

    const pct = score / QUIZ_QUESTIONS.length;
    document.getElementById('resultScore').textContent = score;

    let emoji = '😢', title = 'Terus Belajar!', sub = 'Baca ulang materi di atas ya.';
    if (pct >= 0.85) { emoji = '🏆'; title = 'Luar Biasa! Kamu Ahli B3!'; sub = 'Nilai sempurna! Bagikan pengetahuanmu ke keluarga.'; }
    else if (pct >= 0.6) { emoji = '👍'; title = 'Bagus! Hampir Sempurna'; sub = 'Sedikit lagi kamu jadi ahli B3.'; }
    else if (pct >= 0.4) { emoji = '📚'; title = 'Lumayan, Terus Belajar!'; sub = 'Baca lagi bagian yang kamu kurang mengerti.'; }

    document.getElementById('resultEmoji').textContent = emoji;
    document.getElementById('resultTitle').textContent = title;
    document.getElementById('resultSub').textContent   = sub;
}

function resetQuiz() { initQuiz(); }

/* ============================================================
   FACILITY SEARCH & LOCATION
============================================================ */
function searchFacility(q) {
    const lq = q.toLowerCase();
    document.querySelectorAll('.facility-card').forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(lq) ? '' : 'none';
    });
}

function detectLocation() {
    if (!navigator.geolocation) {
        showToast('Tidak didukung', 'Browser tidak mendukung geolokasi', 'error'); return;
    }
    showToast('Mencari Lokasi...', 'Meminta akses GPS', 'success');
    navigator.geolocation.getCurrentPosition(
        pos => {
            const { latitude: lat, longitude: lng } = pos.coords;
            showToast('Lokasi Ditemukan', `${lat.toFixed(4)}, ${lng.toFixed(4)}`, 'success');
        },
        () => showToast('Akses Ditolak', 'Izinkan akses lokasi di pengaturan browser', 'error')
    );
}

function openMap() {
    window.open('https://www.google.com/maps/search/fasilitas+pengolahan+limbah+B3+terdekat', '_blank');
}

/* ============================================================
   INIT
============================================================ */
document.addEventListener('DOMContentLoaded', initQuiz);
</script>
@endpush