<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PythonDetectionService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.python_yolo.url', 'http://127.0.0.1:8001');
    }

    public function detect(string $imagePath): array
    {
        $response = Http::timeout(30)
            ->attach('image', file_get_contents($imagePath), basename($imagePath))
            ->post("{$this->baseUrl}/detect");

        if ($response->failed()) {
            Log::error("Python YOLO service error: " . $response->body());
            throw new \RuntimeException("Python detection service returned error: " . $response->status());
        }

        $data = $response->json();

        if (!isset($data['detections'])) {
            throw new \RuntimeException("Unexpected response format from Python service.");
        }

        return $data['detections'];
    }
}