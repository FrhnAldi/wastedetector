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
            // 1. Save the uploaded frame temporarily
            $image   = $request->file('image');
            $tmpPath = $image->store('tmp_frames', 'local');
            $absPath = storage_path("app/{$tmpPath}");
 
            // 2. Call Python YOLO detection service
            $detections = app(PythonDetectionService::class)->detect($absPath);
 
            // 3. Optionally save the frame permanently for history
            $savedPath = $image->store('detections', 'public');
 
            // 4. Persist each detection to the DB
            foreach ($detections as $det) {
                Detection::create([
                    'label'      => $det['label'],
                    'category'   => $det['category'],   // 'B3' or 'Non-B3'
                    'confidence' => $det['confidence'],
                    'bbox'       => json_encode($det['bbox']),
                    'image_path' => $savedPath,
                ]);
            }
 
            // 5. Clean up tmp file
            Storage::disk('local')->delete($tmpPath);
 
            return response()->json([
                'success'    => true,
                'detections' => $detections,
            ]);
 
        } catch (\Exception $e) {
            Log::error('Detection error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses gambar.',
            ], 500);
        }
    }
}
 
