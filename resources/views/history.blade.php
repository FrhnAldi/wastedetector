@extends('layouts.app')

@section('title', 'Riwayat Deteksi — WasteGuard')

@push('styles')
<style>
.history-page {
    background: var(--slate-50);
    min-height: calc(100vh - 68px);
    padding: 2rem;
}

.history-inner { max-width: 1200px; margin: 0 auto; }

.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 1.75rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--slate-900);
}

.page-subtitle { color: var(--slate-500); font-size: .9rem; margin-top: .2rem; }

/* Filter bar */
.filter-bar {
    display: flex;
    gap: .75rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    align-items: center;
}

.filter-btn {
    padding: .45rem 1rem;
    border-radius: var(--radius-full);
    border: 1.5px solid var(--slate-200);
    background: white;
    color: var(--slate-600);
    font-weight: 600;
    font-size: .8rem;
    cursor: pointer;
    transition: all .2s;
}

.filter-btn:hover, .filter-btn.active {
    background: var(--green-500);
    border-color: var(--green-500);
    color: white;
}

.filter-btn.danger.active {
    background: var(--red-500);
    border-color: var(--red-500);
}

.search-input {
    flex: 1;
    min-width: 200px;
    max-width: 320px;
    padding: .5rem 1rem;
    border-radius: var(--radius-full);
    border: 1.5px solid var(--slate-200);
    font-size: .875rem;
    outline: none;
    transition: all .2s;
    background: white;
    font-family: var(--font-body);
}

.search-input:focus {
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(34,197,94,.12);
}

/* Stats row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.75rem;
}

.stat-mini {
    background: white;
    border-radius: var(--radius-lg);
    padding: 1.1rem 1.3rem;
    box-shadow: var(--shadow-sm);
    border: 1.5px solid var(--slate-100);
    display: flex;
    align-items: center;
    gap: .9rem;
}

.stat-mini-icon {
    width: 42px; height: 42px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.stat-mini-num {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 800;
    line-height: 1;
    color: var(--slate-900);
}

.stat-mini-lbl {
    font-size: .75rem;
    color: var(--slate-500);
    font-weight: 500;
}

/* Table */
.history-table-wrap {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    border: 1.5px solid var(--slate-100);
    overflow: hidden;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
}

.history-table thead {
    background: var(--slate-50);
    border-bottom: 1.5px solid var(--slate-100);
}

.history-table th {
    padding: .85rem 1.25rem;
    text-align: left;
    font-size: .75rem;
    font-weight: 700;
    color: var(--slate-500);
    letter-spacing: .06em;
    text-transform: uppercase;
    white-space: nowrap;
}

.history-table td {
    padding: .95rem 1.25rem;
    font-size: .875rem;
    color: var(--slate-700);
    border-bottom: 1.5px solid var(--slate-50);
    vertical-align: middle;
}

.history-table tbody tr:last-child td { border-bottom: none; }

.history-table tbody tr:hover td { background: var(--slate-50); }

.thumb {
    width: 52px; height: 40px;
    border-radius: var(--radius-sm);
    object-fit: cover;
    background: var(--slate-100);
    display: block;
}

.badge-b3 {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: #fef2f2;
    color: var(--red-600);
    border: 1px solid rgba(239,68,68,.2);
    border-radius: var(--radius-full);
    padding: .25rem .7rem;
    font-size: .75rem;
    font-weight: 700;
}

.badge-nonb3 {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: var(--green-50);
    color: var(--green-700);
    border: 1px solid rgba(34,197,94,.2);
    border-radius: var(--radius-full);
    padding: .25rem .7rem;
    font-size: .75rem;
    font-weight: 700;
}

.action-btn {
    width: 32px; height: 32px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--slate-200);
    background: white;
    color: var(--slate-500);
    font-size: .75rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all .2s;
}

.action-btn:hover {
    border-color: var(--green-400);
    color: var(--green-700);
    background: var(--green-50);
}

.action-btn.del:hover { border-color: var(--red-400); color: var(--red-600); background: #fef2f2; }

/* pagination */
.pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-top: 1.5px solid var(--slate-100);
}

.page-info { font-size: .8rem; color: var(--slate-500); }

.page-btns { display: flex; gap: .4rem; }

.page-btn {
    width: 34px; height: 34px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--slate-200);
    background: white;
    color: var(--slate-600);
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .2s;
}

.page-btn.active, .page-btn:hover {
    background: var(--green-500);
    border-color: var(--green-500);
    color: white;
}

/* modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(4px);
    z-index: 5000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    opacity: 0;
    pointer-events: none;
    transition: opacity .25s;
}

.modal-overlay.open {
    opacity: 1;
    pointer-events: all;
}

.modal-box {
    background: white;
    border-radius: var(--radius-xl);
    width: 100%;
    max-width: 600px;
    box-shadow: var(--shadow-xl);
    overflow: hidden;
    transform: scale(.95);
    transition: transform .25s;
}

.modal-overlay.open .modal-box { transform: scale(1); }

.modal-header {
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1.5px solid var(--slate-100);
}

.modal-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1rem;
}

.modal-close {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: var(--slate-100);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--slate-500);
    transition: all .2s;
}

.modal-close:hover { background: var(--slate-200); }

.modal-body { padding: 1.5rem; }

.modal-img {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
    border-radius: var(--radius-md);
    background: var(--slate-50);
    margin-bottom: 1.25rem;
}

@media (max-width: 768px) {
    .stats-row { grid-template-columns: 1fr 1fr; }
    .history-table th:nth-child(3),
    .history-table td:nth-child(3) { display: none; }
    .page-header { flex-direction: column; align-items: flex-start; }
}

@media (max-width: 480px) {
    .history-page { padding: 1rem; }
    .stats-row { grid-template-columns: 1fr 1fr; }
    .history-table th:nth-child(4),
    .history-table td:nth-child(4) { display: none; }
}
</style>
@endpush

@section('content')

<div class="history-page">
    <div class="history-inner">

        <div class="page-header">
            <div>
                <h1 class="page-title">Riwayat Deteksi</h1>
                <p class="page-subtitle">Total {{ $totalDetections ?? 0 }} deteksi telah dilakukan</p>
            </div>
            <div style="display:flex;gap:.75rem">
                <button onclick="exportCSV()" style="display:inline-flex;align-items:center;gap:.5rem;padding:.55rem 1.1rem;border-radius:var(--radius-full);border:1.5px solid var(--slate-200);background:white;color:var(--slate-600);font-weight:600;font-size:.85rem;cursor:pointer">
                    <i class="fas fa-file-export"></i> Export CSV
                </button>
                <form method="POST" action="{{ route('history.clear') }}" onsubmit="return confirm('Hapus semua riwayat?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="display:inline-flex;align-items:center;gap:.5rem;padding:.55rem 1.1rem;border-radius:var(--radius-full);border:1.5px solid rgba(239,68,68,.3);background:#fef2f2;color:var(--red-600);font-weight:600;font-size:.85rem;cursor:pointer">
                        <i class="fas fa-trash"></i> Hapus Semua
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats row -->
        <div class="stats-row">
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:#eff6ff;color:#3b82f6"><i class="fas fa-history"></i></div>
                <div>
                    <div class="stat-mini-num">{{ $totalDetections ?? 0 }}</div>
                    <div class="stat-mini-lbl">Total Deteksi</div>
                </div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:#fef2f2;color:var(--red-600)"><i class="fas fa-biohazard"></i></div>
                <div>
                    <div class="stat-mini-num">{{ $totalB3 ?? 0 }}</div>
                    <div class="stat-mini-lbl">Objek B3</div>
                </div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:var(--green-50);color:var(--green-600)"><i class="fas fa-leaf"></i></div>
                <div>
                    <div class="stat-mini-num">{{ $totalNonB3 ?? 0 }}</div>
                    <div class="stat-mini-lbl">Objek Non-B3</div>
                </div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:#fdf4ff;color:#a855f7"><i class="fas fa-percentage"></i></div>
                <div>
                    <div class="stat-mini-num">{{ $avgAccuracy ?? '—' }}%</div>
                    <div class="stat-mini-lbl">Avg Akurasi</div>
                </div>
            </div>
        </div>

        <!-- Filter bar -->
        <div class="filter-bar">
            <button class="filter-btn active" onclick="filterTable('all', this)">Semua</button>
            <button class="filter-btn danger" onclick="filterTable('b3', this)">⚠️ B3</button>
            <button class="filter-btn" onclick="filterTable('nonb3', this)">✅ Non-B3</button>
            <button class="filter-btn" onclick="filterTable('today', this)">Hari Ini</button>
            <input type="text" class="search-input" placeholder="🔍  Cari nama objek..." oninput="searchTable(this.value)">
        </div>

        <!-- Table -->
        <div class="history-table-wrap">
            <table class="history-table" id="historyTable">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Nama Objek</th>
                        <th>Kategori</th>
                        <th>Kepercayaan</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                @php
                    /** @var \App\Models\Detection $det */
                @endphp
                <tbody id="tableBody">
                    @forelse($detections ?? [] as $det)
                    <tr data-category="{{ strtolower($det->category) }}" data-label="{{ strtolower($det->label) }}" data-date="{{ $det->created_at->toDateString() }}">
                        <td>
                            @if($det->image_path)
                                <img src="{{ Storage::url($det->image_path) }}" class="thumb" alt="{{ $det->label }}">
                            @else
                                <div class="thumb" style="display:flex;align-items:center;justify-content:center;color:var(--slate-400)">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:600;color:var(--slate-800)">{{ $det->label }}</div>
                            <div style="font-size:.75rem;color:var(--slate-400)">ID #{{ $det->id }}</div>
                        </td>
                        <td>
                            @if($det->category === 'B3')
                                <span class="badge-b3"><i class="fas fa-exclamation-triangle"></i> B3</span>
                            @else
                                <span class="badge-nonb3"><i class="fas fa-check-circle"></i> Non-B3</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.5rem">
                                <div style="flex:1;height:5px;background:var(--slate-100);border-radius:3px;min-width:60px">
                                    <div @style([
                                        'height: 100%',
                                        'border-radius: 3px',
                                        'width: ' . ($det->confidence * 100) . '%',
                                        'background: var(--red-400)' => $det->category === 'B3',
                                        'background: var(--green-400)' => $det->category !== 'B3',
                                    ])></div>
                                </div>
                                <span style="font-size:.8rem;font-weight:700">{{ round($det->confidence * 100) }}%</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:.82rem">{{ $det->created_at->format('d M Y') }}</div>
                            <div style="font-size:.75rem;color:var(--slate-400)">{{ $det->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            <div style="display:flex;gap:.4rem">
                                <button class="action-btn" onclick="viewDetail('{{ $det->id }}')" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form method="POST" action="{{ route('history.destroy', $det->id) }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn del" title="Hapus" onclick="return confirm('Hapus item ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:3rem;color:var(--slate-400)">
                            <i class="fas fa-inbox" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:.75rem"></i>
                            Belum ada riwayat deteksi.<br>
                            <a href="{{ route('detect') }}" style="color:var(--green-500);font-weight:600">Mulai deteksi sekarang →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                <div class="page-info">
                    Menampilkan {{ $detections->firstItem() ?? 0 }}–{{ $detections->lastItem() ?? 0 }}
                    dari {{ $detections->total() ?? 0 }} hasil
                </div>
                <div class="page-btns">
                    {{ $detections->links('pagination::simple-bootstrap-4') ?? '' }}
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Detail Modal -->
<div class="modal-overlay" id="detailModal" onclick="closeModal(event)">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Detail Deteksi</div>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="modalBody">
            <div style="text-align:center;padding:2rem;color:var(--slate-400)">Memuat...</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function filterTable(type, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const today = new Date().toISOString().slice(0, 10);
    document.querySelectorAll('#tableBody tr[data-category]').forEach(row => {
        const cat  = row.dataset.category;
        const date = row.dataset.date;
        let show = true;
        if (type === 'b3')     show = cat === 'b3';
        if (type === 'nonb3')  show = cat !== 'b3';
        if (type === 'today')  show = date === today;
        row.style.display = show ? '' : 'none';
    });
}

function searchTable(q) {
    const lq = q.toLowerCase();
    document.querySelectorAll('#tableBody tr[data-label]').forEach(row => {
        row.style.display = row.dataset.label.includes(lq) ? '' : 'none';
    });
}

async function viewDetail(id) {
    const modal = document.getElementById('detailModal');
    const body  = document.getElementById('modalBody');
    modal.classList.add('open');
    body.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--slate-400)"><i class="fas fa-spinner fa-spin" style="font-size:2rem"></i><p style="margin-top:1rem">Memuat...</p></div>';

    try {
        const res  = await fetch(`/riwayat/${id}`, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        body.innerHTML = `
            ${data.image_url ? `<img src="${data.image_url}" class="modal-img" alt="${data.label}">` : ''}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                <div style="padding:.85rem;background:var(--slate-50);border-radius:var(--radius-md)">
                    <div style="font-size:.72rem;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem">Objek</div>
                    <div style="font-weight:700;color:var(--slate-900)">${data.label}</div>
                </div>
                <div style="padding:.85rem;background:${data.category === 'B3' ? '#fef2f2' : 'var(--green-50)'};border-radius:var(--radius-md)">
                    <div style="font-size:.72rem;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem">Kategori</div>
                    <div style="font-weight:700;color:${data.category === 'B3' ? 'var(--red-600)' : 'var(--green-700)'}">${data.category === 'B3' ? '⚠️ B3 — Berbahaya' : '✅ Non-B3 — Aman'}</div>
                </div>
                <div style="padding:.85rem;background:var(--slate-50);border-radius:var(--radius-md)">
                    <div style="font-size:.72rem;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem">Kepercayaan</div>
                    <div style="font-weight:800;font-size:1.3rem;color:var(--slate-900)">${Math.round(data.confidence * 100)}%</div>
                </div>
                <div style="padding:.85rem;background:var(--slate-50);border-radius:var(--radius-md)">
                    <div style="font-size:.72rem;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem">Waktu</div>
                    <div style="font-weight:600;color:var(--slate-700)">${data.created_at}</div>
                </div>
            </div>
            ${data.category === 'B3' ? `
            <div style="margin-top:1rem;background:#fef2f2;border-radius:var(--radius-md);padding:1rem;display:flex;gap:.75rem;align-items:flex-start">
                <i class="fas fa-exclamation-triangle" style="color:var(--red-500);margin-top:.1rem"></i>
                <div>
                    <div style="font-weight:700;color:var(--red-700);font-size:.875rem;margin-bottom:.25rem">Peringatan — Sampah B3</div>
                    <p style="font-size:.8rem;color:var(--red-600);line-height:1.6">Jangan buang ke tempat sampah biasa. Bawa ke fasilitas pengolahan B3 terdekat atau hubungi dinas lingkungan hidup setempat.</p>
                </div>
            </div>` : ''}`;
    } catch {
        body.innerHTML = '<p style="color:var(--red-500);text-align:center;padding:2rem">Gagal memuat data.</p>';
    }
}

function closeModal(e) {
    if (e && e.target !== document.getElementById('detailModal')) return;
    document.getElementById('detailModal').classList.remove('open');
}

function exportCSV() {
    window.location.href = '{{ route("history.export") }}';
}
</script>
@endpush