<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AuthenticatedSessionController;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('ecommerce');
});

// Rutas sin autenticación
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
->name('logout');
// Rutas protegidas por el middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('products');
    Route::get('/categories', [ProductController::class, 'categoriesIndex'])->name('categories');
    Route::post('/categories/{category}', [CategoryController::class, 'update']);
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/{category}', [ProductController::class, 'showByCategory'])->name('products.byCategory');
    Route::get('/products/product/{id}', [ProductController::class, 'showByProduct'])->name('productItem');
    Route::get('/purchase', [PurchaseOrderController::class, 'index'])->name('purchase');
    Route::get('/profile', function () {
        return view('profile');
    });
    Route::get('/createProduct', function () {
        return view('createProductItem');
    })->name('createProduct');
    // Nueva ruta para ventas
    Route::get('/sales', [SaleController::class, 'index'])->name('sales');

    Route::get('/paymentMethods', [PaymentMethodController::class, 'index'])->name('paymentMethods');

});

