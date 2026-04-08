<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/categories/{slug}/products', [CatalogController::class, 'categoryListing'])
    ->name('catalog.category.listing');
Route::get('/products/{slug}', [CatalogController::class, 'productShow'])
    ->name('catalog.product.show');
Route::get('/cart', function () {
    return Inertia::render('Cart/Index');
})->name('cart.index');
Route::get('/cart/state', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/items', [CartController::class, 'addItem'])->name('cart.items.store');
Route::patch('/cart/items/{item}', [CartController::class, 'updateItem'])->name('cart.items.update');
Route::delete('/cart/items/{item}', [CartController::class, 'destroyItem'])->name('cart.items.destroy');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::post('/cart/items', [CartController::class, 'addItem'])->name('cart.items.store');
    Route::patch('/cart/items/{item}', [CartController::class, 'updateItem'])->name('cart.items.update');
    Route::delete('/cart/items/{item}', [CartController::class, 'destroyItem'])->name('cart.items.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
