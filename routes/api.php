<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// 2. مسارات المنتجات (Products Module)
// GET /products و GET /products/{id} غير محمية
Route::apiResource('products', ProductController::class);

// 3. مسارات محمية (Protected Routes)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('logout', [AuthController::class, 'logout']);

    
});

// مسار اختياري لعرض المستخدم الحالي
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('orders', OrderController::class)->only(['index', 'store']);
});
