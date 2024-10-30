<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/products');
});

// Route::get('/products', [ProductController::class, 'index']);
// Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::resource('products', ProductController::class);

// Cart routes
Route::resource('cart', CartController::class)->only(['index', 'store']);
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::delete('/cart/{cart}/product/{product}', [CartController::class, 'remove'])->name('cart.remove');

// Route::get('/mimi/link-storage', function () {
//     Artisan::call('storage:link');
//     return 'success';
// });
