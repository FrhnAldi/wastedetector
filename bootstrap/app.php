<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tambahkan ini agar rute deteksi tidak dicegat security CSRF
        $middleware->validateCsrfTokens(except: [
            'api/detect', 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();