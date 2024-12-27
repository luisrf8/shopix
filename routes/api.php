<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoogleDriveController;
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas públicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Endpoint para obtener el token CSRF
Route::get('sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf-token' => csrf_token()]);
});
Route::get('/categories', [ProductController::class, 'categoriesIndex'])->name('categories');

// Rutas protegidas por el middleware auth:sanctum
Route::middleware(['verify.access.token', 'role:admin,vendor'])->group(function () {
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});

Route::middleware(['auth'])->group(function () {
// Route::middleware(['auth:sanctum', 'verify.access.token', 'role:admin,vendor'])->group(function () {
    //Categorías
    Route::get('/categories', [CategoryController::class, 'getCategories']);
    Route::post('/create-category', [CategoryController::class, 'store']);

    //Productos
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::get('/api/products/{id}', [ProductController::class, 'show']);
    Route::put('/api/products/{id}', [ProductController::class, 'update']);

    // Ruta para crear el producto
    Route::post('/create-product', [ProductController::class, 'create']);
    // Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::get('/auth/google', [GoogleDriveController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [GoogleDriveController::class, 'handleGoogleCallback']);

    Route::post('/variants/store', [ProductVariantController::class, 'store'])->name('variants.store');
    // Route::middleware(['auth:sanctum'])->post('/variants/store', [ProductVariantController::class, 'store'])->name('variants.store');
});

// Rutas públicas para Google OAuth
Route::get('/auth/google', [GoogleDriveController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleDriveController::class, 'handleGoogleCallback']);
