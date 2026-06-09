<?php
// app/Http/Controllers/HistoryController.php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    public function index()
    {
        $detections      = Detection::latest()->paginate(15);
        $totalDetections = Detection::count();
        $totalB3         = Detection::where('category', 'B3')->count();
        $totalNonB3      = Detection::where('category', 'Non-B3')->count();
        $avgAccuracy     = $totalDetections > 0
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

    public function show(Detection $detection)
    {
        return response()->json([
            'id'         => $detection->id,
            'label'      => $detection->label,
            'category'   => $detection->category,
            'confidence' => $detection->confidence,
            'bbox'       => $detection->bbox,
            'image_url'  => $detection->image_path
                                ? Storage::url($detection->image_path)
                                : null,
            'latitude'   => $detection->latitude,
            'longitude'  => $detection->longitude,
            'created_at' => $detection->created_at->format('d M Y, H:i'),
        ]);
    }

    public function destroy(Detection $detection)
    {
        if ($detection->image_path && Storage::disk('public')->exists($detection->image_path)) {
            Storage::disk('public')->delete($detection->image_path);
        }
        $detection->delete();

        return redirect()->route('history.index')
                         ->with('success', 'Riwayat berhasil dihapus.');
    }

    public function clear()
    {
        $paths = Detection::whereNotNull('image_path')
                          ->pluck('image_path')
                          ->toArray();

        Detection::truncate();

        foreach ($paths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        return redirect()->route('history.index')
                         ->with('success', 'Semua riwayat berhasil dihapus.');
    }

    public function export()
    {
        $detections = Detection::latest()->get();

        $csv = "ID,Label,Kategori,Kepercayaan (%),Latitude,Longitude,Waktu\n";
        foreach ($detections as $det) {
            $csv .= implode(',', [
                $det->id,
                '"' . str_replace('"', '""', $det->label) . '"',
                $det->category,
                round($det->confidence * 100),
                $det->latitude  ?? '',
                $det->longitude ?? '',
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