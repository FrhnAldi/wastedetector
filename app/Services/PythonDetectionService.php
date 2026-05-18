<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Tambahkan ini

class PythonDetectionService
{
    public function detect($imagePath)
    {
        // Ingat ganti link ini setelah Render kamu LIVE
        $url = "https://waste-ai-api.onrender.com/detect";

        try {
            $response = Http::attach(
                'image', 
                file_get_contents($imagePath), 
                basename($imagePath)
            )->post($url);

            if ($response->successful()) {
                return $response->json();
            }

            // Catat log jika API Render mengembalikan error (misal error 500)
            Log::error("Render AI Error: " . $response->status() . " - " . $response->body());
            return [];

        } catch (\Exception $e) {
            // Catat log jika gagal konek ke server Render (misal timeout)
            Log::error("Gagal koneksi ke Render: " . $e->getMessage());
            return [];
        }
    }
}