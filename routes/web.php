<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('inventories')->middleware(['role:super-admin'])->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::get('/create', [InventoryController::class, 'create'])->name('create');
    Route::post('/store', [InventoryController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [InventoryController::class, 'edit'])->name('edit');
    Route::patch('/update/{id}', [InventoryController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [InventoryController::class, 'destroy'])->name('delete');
});

Route::middleware('auth')->prefix('sales')->middleware(['role:super-admin|sales|manager'])->name('sales.')->group(function () {
    Route::get('/', [SalesController::class, 'index'])->name('index');
    Route::get('/create', [SalesController::class, 'create'])->name('create')->middleware(['role:super-admin|sales']);
    Route::post('/store', [SalesController::class, 'store'])->name('store')->middleware(['role:super-admin|sales']);
    Route::get('/edit/{id}', [SalesController::class, 'edit'])->name('edit')->middleware(['role:super-admin|sales']);
    Route::patch('/update/{id}', [SalesController::class, 'update'])->name('update')->middleware(['role:super-admin|sales']);
    Route::delete('/delete/{id}', [SalesController::class, 'destroy'])->name('delete')->middleware(['role:super-admin|sales']);
});

Route::middleware('auth')->prefix('purchase')->middleware(['role:super-admin|purchase|manager'])->name('purchase.')->group(function () {
    Route::get('/', [PurchaseController::class, 'index'])->name('index');
    Route::get('/create', [PurchaseController::class, 'create'])->name('create')->middleware(['role:super-admin|purchase']);
    Route::post('/store', [PurchaseController::class, 'store'])->name('store')->middleware(['role:super-admin|purchase']);;
    Route::get('/edit/{id}', [PurchaseController::class, 'edit'])->name('edit')->middleware(['role:super-admin|purchase']);;
    Route::patch('/update/{id}', [PurchaseController::class, 'update'])->name('update')->middleware(['role:super-admin|purchase']);;
    Route::delete('/delete/{id}', [PurchaseController::class, 'destroy'])->name('delete')->middleware(['role:super-admin|purchase']);;
});

require __DIR__.'/auth.php';
