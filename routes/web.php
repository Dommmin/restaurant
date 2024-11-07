<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;
use Laravel\Cashier\Http\Controllers\PaymentController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('reservations', [UserController::class, 'reservations'])->name('user.reservations');
    Route::get('orders', [UserController::class, 'orders'])->name('user.orders');
});

Route::get('/', HomeController::class)->name('home');
Route::get('tables/{table}', TableController::class)->name('table');
Route::post('tables/{table}', [BookingController::class, 'store'])->name('booking.store');
Route::get('menu', MenuController::class)->name('menu');
Route::put('cart/{cartItem}/increment', [CartController::class, 'increment'])->name('cart.increment');
Route::put('cart/{cartItem}/decrement', [CartController::class, 'decrement'])->name('cart.decrement');
Route::resource('cart', CartController::class)
    ->parameters(['cart' => 'cartItem'])
    ->names([
        'index' => 'cart.index',
        'store' => 'cart.store',
        'update' => 'cart.update',
        'destroy' => 'cart.destroy',
    ]);

Route::put('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

// Przelewy24
Route::post('payment/status', [OrderController::class, 'status'])->name('payment.status');
Route::post('orders', [OrderController::class, 'store'])->name('order.store');
Route::get('orders/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');

// Stripe
Route::post('/webhook/stripe', [PaymentController::class, 'handleWebhook']);
Route::get('/payment/confirm', [PaymentController::class, 'confirm']);
