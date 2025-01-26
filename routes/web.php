<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\IndexController;
// use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
         ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});
// web.php
Route::get('/', function () {
    return view('ecommerce');
});

// Route::middleware('auth')->get('/dashboard', function () {
    //     return view('dashboard');
    // });
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [IndexController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    // Route::get('/dashboard', [IndexController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/categories', [ProductController::class, 'categoriesIndex'])->name('categories');
    Route::get('/paymentMethods', [PaymentMethodController::class, 'index'])->name('paymentMethods');
    Route::get('/products/{category}', [ProductController::class, 'showByCategory'])->name('products.byCategory');
    Route::get('/products/product/{id}', [ProductController::class, 'showByProduct'])->name('productItem');
    Route::get('/createProduct', function () {
        return view('createProductItem');
    })->name('createProduct');
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Nuevas rutas para ventas y compras
    Route::get('/sales', [SaleController::class, 'index'])->name('sales');
    Route::get('/sales-orders', [SaleController::class, 'viewOrders'])->name('sales.orders');
    Route::get('/sales/{id}', [SaleController::class, 'showByOrder'])->name('sales.showByOrder');


    Route::get('/purchase', [PurchaseOrderController::class, 'index'])->name('purchase');
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'viewOrders'])->name('purchase.orders');
    Route::get('/order/{id}', [PurchaseOrderController::class, 'showByOrder'])->name('showByOrder');

});


require __DIR__.'/auth.php';
