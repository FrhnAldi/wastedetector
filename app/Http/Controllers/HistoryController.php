<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detection;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    /**
     * Tampilkan halaman riwayat dengan pagination & statistik.
     */
    public function index()
    {
        $detections     = Detection::latest()->paginate(15);
        $totalDetections = Detection::count();
        $totalB3        = Detection::where('category', 'B3')->count();
        $totalNonB3     = Detection::where('category', 'Non-B3')->count();
        $avgAccuracy    = Detection::count()
            ? round(Detection::avg('confidence') * 100)
            : null;

        return view('history', compact(
            'detections',
            'totalDetections',
            'totalB3',
            'totalNonB3',
            'avgAccuracy'
        ));
    }

    /**
     * Kembalikan detail satu deteksi sebagai JSON (untuk modal).
     */
    public function show(Detection $detection)
    {
        return response()->json([
            'id'         => $detection->id,
            'label'      => $detection->label,
            'category'   => $detection->category,
            'confidence' => $detection->confidence,
            'bbox'       => $detection->bbox,
            // image_url: gunakan Storage::url agar path benar (perlu storage:link)
            'image_url'  => $detection->image_path
                                ? Storage::url($detection->image_path)
                                : null,
            'created_at' => $detection->created_at->format('d M Y, H:i'),
        ]);
    }

    /**
     * Hapus satu item riwayat beserta file gambarnya.
     */
    public function destroy(Detection $detection)
    {
        // Hapus file dari storage supaya tidak menumpuk
        if ($detection->image_path && Storage::disk('public')->exists($detection->image_path)) {
            Storage::disk('public')->delete($detection->image_path);
        }

        $detection->delete();

        return redirect()->route('history.index')
                         ->with('success', 'Riwayat berhasil dihapus.');
    }

    /**
     * Hapus SEMUA riwayat + semua file gambar deteksi.
     */
    public function clear()
    {
        // Ambil semua path sebelum dihapus
        $paths = Detection::whereNotNull('image_path')
                          ->pluck('image_path')
                          ->toArray();

        Detection::truncate();

        // Hapus file satu per satu (bukan deleteDirectory agar folder tetap ada)
        foreach ($paths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        return redirect()->route('history.index')
                         ->with('success', 'Semua riwayat berhasil dihapus.');
    }

    /**
     * Export riwayat sebagai CSV.
     */
    public function export()
    {
        $detections = Detection::latest()->get();

        $csv = "ID,Label,Kategori,Kepercayaan (%),Waktu\n";
        foreach ($detections as $det) {
            $csv .= implode(',', [
                $det->id,
                '"' . $det->label . '"',
                $det->category,
                round($det->confidence * 100),
                $det->created_at->format('d/m/Y H:i'),
            ]) . "\n";
        }

        $filename = 'wasteguard_riwayat_' . now()->format('Ymd_His') . '.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}