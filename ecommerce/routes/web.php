<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\VoucherController;

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

// Auth::routes();
Auth::routes(['register' => false]);

Route::middleware('is_user')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

    Route::get('/search/{keyword}', [SearchController::class, 'searchResult']);

    Route::get('/cart', [CartController::class, 'index'])->name('cart');//->middleware('auth');

    Route::get('/add-to-cart/{product_id}', [CartController::class, 'addToCart']);

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/save-order', [CheckoutController::class, 'saveOrder']);

    Route::get('/product/{product_name}/{product_code}/{product_id}', [ProductsController::class, 'index']);

    Route::get('/category', [CategoryController::class, 'index'])->name('category');

    Route::get('/contact', [ContactController::class, 'index']);

    Route::group(['prefix' => 'user'], function () {
        Route::get('/address', [UserController::class, 'getUserAddress']);
    });
});

Route::middleware(['is_admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('admin');

        Route::prefix('tables')->group(function () {
            Route::get('/user', [UserController::class, 'usersList']);
            Route::get('/productsList', [AdminProductController::class, 'index']);
        });

        Route::prefix('form')->group(function () {
            Route::prefix('user')->group(function () {
                Route::get('/insert', [AdminUserController::class, 'insert']);
                Route::post('/insert', [AdminUserController::class, 'insertAction']);
            });

            Route::prefix('product')->group(function () {
                Route::get('/insert', [AdminProductController::class, 'insert']);
                Route::post('/insert', [AdminProductController::class, 'insertAction']);
            });
        });

        Route::prefix('edit')->group(function () {
            Route::get('/product/{id}', [AdminProductController::class, 'edit']);
            Route::put('/product/{id}', [AdminProductController::class, 'update']);

            Route::get('/user/{id}', [AdminUserController::class, 'edit']);
            Route::put('/user/{id}', [AdminUserController::class, 'update']);
        });

        Route::prefix('delete')->group(function () {
            Route::get('/product/{id}', [AdminProductController::class, 'delete']);
            Route::get('/user/{id}', [AdminUserController::class, 'delete']);
            Route::get('/order/{id}', [OrderController::class, 'deleteOrder']);
            Route::get('/order_items/{id}', [OrderController::class, 'deleteOrderItem']);
        });

        Route::prefix('order')->group(function () {
            Route::get('/', [OrderController::class, 'index']);

            Route::get('/insert', [OrderController::class, 'insert']);
            Route::post('/insert', [OrderController::class, 'insertAction']);

            Route::get('/lihat-pesanan/{id}', [OrderController::class, 'lihatPesanan']);
            // Route::post('/lihat-pesanan/{id}', [OrderController::class, 'insertOrder']);
        });

        Route::prefix('voucher')->group(function () {
            Route::get('/list', [VoucherController::class, 'index']);
            Route::get('/insert', [VoucherController::class, 'insertView']);
        });
    });
});

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('postregister');
});
