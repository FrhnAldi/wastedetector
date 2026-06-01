<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use App\Services\PythonDetectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DetectController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('detect');
    }

    // ──────────────────────────────────────────────────────────
    // POST /api/detect
    // ──────────────────────────────────────────────────────────
    public function detect(Request $request): JsonResponse
    {
        $request->validate([
            'image'     => 'required|image|mimes:jpeg,png,webp|max:10240',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // ✅ FIX: Pastikan cookie guest_uuid selalu ada.
        // Jika belum ada, buat sekarang dan sertakan di response.
        $userUuid = $request->cookie('guest_uuid');
        $newCookie = null;
        if (! $userUuid) {
            $userUuid  = (string) Str::uuid();
            $newCookie = cookie('guest_uuid', $userUuid, 60 * 24 * 365); // 1 tahun
        }

        $image = $request->file('image');

        // 1. Simpan file sementara agar bisa dikirim ke Python
        $tmpPath = $image->store('tmp_frames', 'local');
        $absPath = storage_path("app/{$tmpPath}");

        // 2. Panggil Python YOLO service
        $usedMock = false;
        try {
            $detections = app(PythonDetectionService::class)->detect($absPath);
        } catch (\Throwable $e) {
            // Hapus tmp — file sudah tidak dibutuhkan (gagal kirim ke Python)
            Storage::disk('local')->delete($tmpPath);
            $tmpPath = null;

            Log::error('[YOLO] ' . $e->getMessage());

            // MOCK MODE — aktif saat YOLO_MOCK_MODE=true di .env
            if (config('app.yolo_mock_mode', false)) {
                $detections = $this->mockDetections();
                $usedMock   = true;
                Log::info('[YOLO] Menggunakan mock detections');
            } else {
                $json = response()->json([
                    'success' => false,
                    'message' => 'Python YOLO service tidak dapat diakses. '
                               . 'Pastikan server Python sudah berjalan: python python_service/app.py',
                    'hint'    => 'Atau set YOLO_MOCK_MODE=true di .env untuk mode test.',
                    'debug'   => app()->hasDebugModeEnabled() ? $e->getMessage() : null,
                ], 503);

                return $newCookie ? $json->withCookie($newCookie) : $json;
            }
        }

        // 3. Simpan gambar permanen (jika ada deteksi dan bukan mock)
        $savedPath = null;
        if (! $usedMock && $tmpPath && count($detections) > 0) {
            $destPath = "detections/{$image->hashName()}";
            Storage::disk('public')->put(
                $destPath,
                Storage::disk('local')->get($tmpPath)
            );
            $savedPath = $destPath;
        }

        // 4. Bersihkan tmp
        if ($tmpPath) {
            Storage::disk('local')->delete($tmpPath);
        }

        // 5. Koordinat GPS
        $lat = $request->filled('latitude')  ? (float) $request->input('latitude')  : null;
        $lng = $request->filled('longitude') ? (float) $request->input('longitude') : null;

        // 6. ✅ FIX: Simpan ke database untuk SEMUA deteksi (tidak bergantung pada kondisi lain)
        //    Sebelumnya ada bug: jika $userUuid null, data tidak tersimpan karena
        //    cookie belum pernah di-set di sisi client.
        foreach ($detections as $det) {
            if (! is_array($det) || empty($det['label'])) continue;

            try {
                Detection::create([
                    'user_uuid'  => $userUuid,
                    'label'      => $det['label'],
                    'category'   => $det['category']   ?? 'Non-B3',
                    'confidence' => $det['confidence']  ?? 0.0,
                    'bbox'       => json_encode($det['bbox'] ?? []),
                    'image_path' => $savedPath,
                    'latitude'   => $lat,
                    'longitude'  => $lng,
                ]);
            } catch (\Throwable $e) {
                Log::error('[DB] Gagal simpan deteksi: ' . $e->getMessage());
            }
        }

        $json = response()->json([
            'success'    => true,
            'detections' => array_values($detections),
            'total'      => count($detections),
            'location'   => $lat ? ['lat' => $lat, 'lng' => $lng] : null,
        ]);

        // Pasang cookie baru jika baru dibuat
        return $newCookie ? $json->withCookie($newCookie) : $json;
    }

    // ──────────────────────────────────────────────────────────
    // MOCK DETECTIONS — untuk test UI tanpa Python
    // ──────────────────────────────────────────────────────────
    private function mockDetections(): array
    {
        $items = [
            ['label' => 'Baterai Bekas',    'category' => 'B3',     'confidence' => 0.94, 'bbox' => [60,  40,  280, 200]],
            ['label' => 'Botol Plastik',    'category' => 'Non-B3', 'confidence' => 0.88, 'bbox' => [310, 50,  520, 230]],
            ['label' => 'Lampu Neon',       'category' => 'B3',     'confidence' => 0.76, 'bbox' => [80,  250, 260, 400]],
            ['label' => 'Kaleng Aluminium', 'category' => 'Non-B3', 'confidence' => 0.81, 'bbox' => [200, 100, 400, 300]],
        ];
        shuffle($items);
        return array_slice($items, 0, rand(1, 3));
    }

    // ──────────────────────────────────────────────────────────
    // GET /api/detections/map
    // ──────────────────────────────────────────────────────────
    public function mapPoints(Request $request): JsonResponse
    {
        // ✅ FIX: Tampilkan semua titik, tidak hanya milik user ini.
        // Untuk multi-user, uncomment kondisi forUser() di bawah.
        $points = Detection::withLocation()
            ->latest()
            ->limit(200)
            ->get(['id', 'label', 'category', 'confidence',
                   'latitude', 'longitude', 'created_at']);

        return response()->json(['success' => true, 'points' => $points]);
    }

    // ──────────────────────────────────────────────────────────
    // GET /api/detect/health
    // ──────────────────────────────────────────────────────────
    public function health(): JsonResponse
    {
        $pythonUrl = rtrim(config('services.python_yolo.url', 'http://127.0.0.1:8001'), '/');

        try {
            $res = Http::timeout(5)->get("{$pythonUrl}/health");
            return response()->json([
                'laravel'       => 'ok',
                'python'        => $res->ok() ? 'ok' : 'error',
                'python_detail' => $res->json(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'laravel' => 'ok',
                'python'  => 'unreachable',
                'message' => $e->getMessage(),
                'hint'    => 'Jalankan: python python_service/app.py',
            ], 503);
        }
    }
}