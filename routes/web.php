<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ruta principal
Route::get('/', function () {
    return view('ecommerce');
});

// Rutas sin protección de autenticación
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
});

// Rutas protegidas por el middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route::get('/products', function () {
    //     return view('products');
    // })->name('products');
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    // Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/products/{category}', [ProductController::class, 'showByCategory'])->name('products.byCategory');
    Route::get('/products/product/{id}', [ProductController::class, 'showByProduct'])->name('productItem');
    Route::get('/profile', function () {
        return view('profile');
    });

    // Nueva ruta para ventas
    Route::get('/sales', function () {
        return view('sales'); // Asegúrate de tener una vista 'sales.blade.php'
    })->name('sales');
});
