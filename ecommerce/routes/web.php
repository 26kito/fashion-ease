<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;

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
Auth::routes();

Route::middleware('is_user')->group(function() {
    Route::get('/', [HomeController::class, 'home'])->name('home');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

    Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart']);
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

    Route::get('/products/{id}', [ProductsController::class, 'index']);
    
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    
    Route::get('/contact', [ContactController::class, 'index']);
});

Route::middleware(['is_admin'])->group(function() {
    Route::prefix('admin')->group(function() {
        Route::get('/', [HomeController::class, 'admin'])->name('admin');
    
        Route::prefix('tables')->group(function() {
            Route::get('/user', [UsersController::class, 'usersList']);
            Route::get('/productsList', [ProductsController::class, 'productsList']);
        });
    
        Route::prefix('form')->group(function() {
            Route::prefix('user')->group(function() {
                Route::get('/insert', [App\Http\Controllers\Admin\AdminUserController::class, 'insert']);
                Route::post('/insert', [App\Http\Controllers\Admin\AdminUserController::class, 'insertAction']);
            });
            Route::prefix('product')->group(function() {
                Route::get('/insert', [App\Http\Controllers\Admin\AdminProductController::class, 'insert']);
                Route::post('/insert', [App\Http\Controllers\Admin\AdminProductController::class, 'insertAction']);
            });
        });
    
        Route::prefix('edit')->group(function() {
            Route::get('/product/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'edit']);
            Route::put('/product/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'update']);
            
            Route::get('/user/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'edit']);
            Route::put('/user/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'update']);
    
            // Route::get('/order_items/{id}', [App\Http\Controllers\Admin\OrderController::class, 'editOrderItem']);
            // Route::put('/order_items/{id}', [App\Http\Controllers\Admin\OrderController::class, 'updateOrderItem']);
        });
        
        Route::prefix('delete')->group(function() {
            Route::get('/product/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'delete']);
            Route::get('/user/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'delete']);
            Route::get('/order/{id}', [App\Http\Controllers\Admin\OrderController::class, 'deleteOrder']);
            Route::get('/order_items/{id}', [App\Http\Controllers\Admin\OrderController::class, 'deleteOrderItem']);
        });
    
        Route::prefix('order')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\OrderController::class, 'index']);
    
            Route::get('/insert', [App\Http\Controllers\Admin\OrderController::class, 'insert']);
            Route::post('/insert', [App\Http\Controllers\Admin\OrderController::class, 'insertAction']);
    
            Route::get('/lihat-pesanan/{id}', [App\Http\Controllers\Admin\OrderController::class, 'lihatPesanan']);
            Route::post('/lihat-pesanan/{id}', [App\Http\Controllers\Admin\OrderController::class, 'insertOrder']);
        });
    });
});
