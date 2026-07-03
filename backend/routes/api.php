<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/testimonials', [TestimonialController::class, 'index']);

Route::get('/settings', [SettingController::class, 'index']);

Route::post('/setup', function (\Illuminate\Http\Request $req) {
    if ($req->header('X-Setup-Key') !== 'japranmceh-setup-2024') {
        return response()->json(['error' => 'Invalid key'], 403);
    }
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $output .= "\n" . \Illuminate\Support\Facades\Artisan::output();
        return response()->json(['success' => true, 'output' => $output]);
    } catch (\Throwable $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::post('/testimonials', [TestimonialController::class, 'store']);
    Route::put('/testimonials/{id}', [TestimonialController::class, 'update']);
    Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy']);

    Route::put('/settings', [SettingController::class, 'update']);
});
