<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Tambahkan konfigurasi layanan eksternal di sini.
    | File ini sudah ada di Laravel secara default — tambahkan
    | blok 'python_yolo' ke dalam array yang sudah ada.
    |
    */

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // ── WasteGuard: Python YOLO Service ──────────────────────────────────
    // URL dikonfigurasi via .env → PYTHON_YOLO_URL
    // Default: http://127.0.0.1:8001 (lokal)
    'python_yolo' => [
        'url' => env('PYTHON_YOLO_URL', 'http://127.0.0.1:8001'),
    ],

];