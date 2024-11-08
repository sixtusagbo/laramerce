<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/locale', function () {
    $locale = request('locale');
    if (!in_array($locale, config('app.available_locales'))) {
        abort(404);
    }
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('locale.set');

Route::get('/', function () {
    return redirect()->route('products.index');
});

// Route::get('/products', [ProductController::class, 'index']);

Route::resource('products', ProductController::class);

// Cart routes
Route::resource('cart', CartController::class)->only(['index', 'store']);
Route::delete('/cart/{cart?}', [CartController::class, 'clear'])->name('cart.clear');
Route::delete('/cart/{cart}/product/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/payment/verify', [CartController::class, 'verify_payment'])->name('payment.verify');

// Route::get('/mimi/link-storage', function () {
//     Artisan::call('storage:link');
//     return 'success';
// });
// remove and add quantity from cart routes
Route::post('/cart/add/{product}', [CartController::class, 'addquantity'])->name('cart.add');
Route::post('/cart/minus/{product}', [CartController::class, 'removequantity'])->name('cart.minus');
// remove and add quantity from cart routes
Route::post('/cart/add/{product}', [CartController::class, 'addquantity'])->name('cart.add');
Route::post('/cart/minus/{product}', [CartController::class, 'removequantity'])->name('cart.minus');

Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified', 'role.is_admin'])->name('admin.dashboard');
