<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PythonDetectionService
{
    public function detect(string $imagePath): array
    {
        // URL dikonfigurasi via config/services.php → PYTHON_YOLO_URL di .env
        $baseUrl = rtrim(config('services.python_yolo.url', 'http://127.0.0.1:8001'), '/');
        $url     = "{$baseUrl}/detect";

        // ✅ PENTING: JANGAN tangkap exception di sini.
        //    Biarkan exception propagasi ke DetectController
        //    agar blok try/catch dan YOLO_MOCK_MODE di controller bisa bekerja.
        $response = Http::timeout(120)
            ->attach(
                'image',
                file_get_contents($imagePath),
                basename($imagePath)
            )
            ->post($url);

        if (! $response->successful()) {
            throw new \RuntimeException(
                "Python YOLO service error [{$response->status()}]: "
                . substr($response->body(), 0, 300)
            );
        }

        $data = $response->json();

        // ✅ FIX UTAMA — Root cause error "Cannot read properties of undefined (reading '0')"
        //
        // SEBELUM (salah):
        //   return $response->json();
        //   → mengembalikan SELURUH object: {"success":true,"total":2,"detections":[...]}
        //   → DetectController: array_values((array) $data)
        //     menghasilkan [true, 2, [{...},{...}]]  ← indeks 0 = true, 1 = 2, 2 = array
        //   → Frontend: detections = [true, 2, [...]]
        //     d = true → true.bbox → undefined → undefined[0] → 💥 ERROR
        //
        // SESUDAH (benar):
        //   return $data['detections']
        //   → mengembalikan hanya array flat: [{label,category,confidence,bbox}, ...]
        //   → Frontend: d.bbox[0] = 60 → ✅ tidak crash

        $detections = $data['detections'] ?? [];

        if (! is_array($detections)) {
            Log::warning('[YOLO] Field "detections" bukan array', ['raw' => $data]);
            return [];
        }

        return array_values($detections);
    }
}