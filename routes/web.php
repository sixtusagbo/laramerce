<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/products');
});

// Route::get('/products', [ProductController::class, 'index']);
// Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::resource('products', ProductController::class);

// Route::get('/mimi/link-storage', function () {
//     Artisan::call('storage:link');
//     return 'success';
// });
