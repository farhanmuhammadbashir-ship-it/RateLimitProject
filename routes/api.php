<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:10,1')->post('/login', [\App\Http\Controllers\AuthController::class, 'issueToken']);

Route::middleware(['auth:sanctum', 'throttle:api'])->get('/private', function (Request $request) {
    return response()->json([
        'type' => 'protected_api',
        'rate_limit' => '10 requests per minute',
        'timestamp' => now()
    ]);
});

Route::middleware(['throttle:basic'])->get('/basic-limit', [\App\Http\Controllers\RateLimitController::class, 'basic']);
Route::middleware(['throttle:strict'])->get('/strict-limit', [\App\Http\Controllers\RateLimitController::class, 'strict']);
Route::middleware(['throttle:custom'])->get('/custom-limit', [\App\Http\Controllers\RateLimitController::class, 'custom']);
Route::get('/no-limit', [\App\Http\Controllers\RateLimitController::class, 'noLimit']);
