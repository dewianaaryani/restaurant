<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
Route::get('/', [PageController::class, 'landingPage'])->name('landingPage');
Auth::routes();

/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:user'])->group(function () {
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/order-summary/{order}', [OrderController::class, 'summary'])->name('order.summary');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/view/{id}', [OrderController::class, 'view'])->name('orders.view');
    Route::post('/payment/upload/{orderId}', [OrderController::class, 'uploadPaymentProof'])->name('payment.upload');


});
  
/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [HomeController::class, 'adminHome'])->name('home'); // Admin home route

    // Orders Routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index'); // List all orders
        Route::get('/{order}', [OrderController::class, 'show'])->name('show'); // Show specific order
        Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('updateStatus'); // Update order status
    });
});
  