<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleDriveController;


// Rutas públicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Endpoint para obtener el token CSRF
Route::post('/create-user', [UserController::class, 'store']);
Route::post('/user/{id}', [UserController::class, 'update']);
Route::post('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
// Rutas públicas para Google OAuth
Route::get('/auth/google', [GoogleDriveController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleDriveController::class, 'handleGoogleCallback']);

// Rutas protegidas por el middleware 'auth:sanctum'
Route::get('/products', [ProductController::class, 'index']);
Route::post('/get-variants', [PurchaseOrderController::class, 'getVariants']);
Route::get('/categories', [CategoryController::class, 'getCategories']);
Route::get('/get-products', [ProductController::class, 'getProducts']);
Route::get('/products/{id}', [ProductController::class, 'showByCategoryEcomm']);
Route::get('/getProduct/{id}', [ProductController::class, 'show']);


Route::middleware(['auth'])->group(function () {
    // Categorías
    Route::post('/create-category', [CategoryController::class, 'store']);
    // Route::post('/update-category/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories/{category}', [CategoryController::class, 'update']);
    // Route::post('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus']);
    Route::post('categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus']);

    // Productos
    Route::post('/products/{id}', [ProductController::class, 'update']);

    Route::post('/create-order', [PurchaseOrderController::class, 'store']);

    // Ruta para crear el producto
    Route::post('/create-product', [ProductController::class, 'create']);
    Route::post('/addImage/{productId}', [ProductController::class, 'addImage'])->name('product.addImage');
    Route::delete('/product/remove-image/{imageId}', [ProductController::class, 'removeImage'])->name('product.removeImage');
    
    // Variantes de productos
    Route::post('/variants/store', [ProductVariantController::class, 'store'])->name('variants.store');
    Route::put('/variants/{productVariant}', [ProductVariantController::class, 'update'])->name('variants.update');

    Route::prefix('payment-methods')->group(function () {
        Route::post('/create', [PaymentMethodController::class, 'create']);
        Route::put('/{id}/edit', [PaymentMethodController::class, 'edit']);
        Route::patch('/{id}/toggleStatus', [PaymentMethodController::class, 'deactivate']);
    });
    
    Route::post('currencies/create', [PaymentMethodController::class, 'currencyCreate']);
    Route::prefix('')->group(function () {
        Route::post('/update', [PaymentMethodController::class, 'updateCurrency']);
        Route::patch('/{id}/currencyToggleStatus', [PaymentMethodController::class, 'deactivate']);
    });
    
    Route::prefix('dollar-rate')->group(function () {
        Route::post('/update', [PaymentMethodController::class, 'updateDollarRate']);
    });
    Route::get('/payment-methods', [SaleController::class, 'getPaymentMethods']);
    Route::post('/sales/get-variants', [SaleController::class, 'getVariants']);
    Route::post('/create-sale', [SaleController::class, 'store']);
});
