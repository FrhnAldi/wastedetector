<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Di dalam routes/api.php
use App\Http\Controllers\DetectController;

Route::post('/detect', [DetectController::class, 'detect']);