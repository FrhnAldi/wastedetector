<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detection;
use App\Services\PythonDetectionService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DetectController extends Controller
{
    public function index()
    {
        return view('detect');
    }
 
    /**
     * Receive an image from the JS frontend, forward to Python YOLO service,
     * persist detections, and return JSON to the browser.
     */
    public function detect(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,webp|max:10240',
    ]);

    try {
        // 1. Simpan gambar ke folder /tmp (Satu-satunya folder yang bisa ditulis di Vercel)
        $image = $request->file('image');
        $fileName = time() . '_' . $image->getClientOriginalName();
        
        // Simpan nama file ke variabel agar tidak 'undefined' di bawah
        $tmpPath = "/tmp/{$fileName}"; 
        $image->move('/tmp', $fileName);

        // 2. Panggil Python YOLO detection service menggunakan path absolut
        $detections = app(PythonDetectionService::class)->detect($tmpPath);

        // 3. Simpan data ke Database (Database Supabase sudah aman)
        foreach ($detections as $det) {
            Detection::create([
                'label'      => $det['label'],
                'category'   => $det['category'],
                'confidence' => $det['confidence'],
                'bbox'       => json_encode($det['bbox']),
                'image_path' => 'url_atau_path_cloud_storage_kamu', // Vercel tidak bisa simpan file permanen
            ]);
        }

        // 4. Bersihkan file sementara di /tmp agar tidak penuh
        if (file_exists($tmpPath)) {
            unlink($tmpPath);
        }

        return response()->json([
            'success'    => true,
            'detections' => $detections,
        ]);

    } catch (\Exception $e) {
        Log::error('Detection error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memproses gambar: ' . $e->getMessage(),
        ], 500);
    }
}
}
 
