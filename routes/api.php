<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Register a new user
Route::post('users', [UserController::class, 'store'])->name('users.store');

// User login
Route::post('login', [UserController::class, 'login'])->name('users.login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Users
    Route::resource('users', UserController::class)->except(['store']);

    // Products
    Route::resource('products', ProductController::class);

    // Orders
    Route::resource('orders', OrderController::class);
});
