<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RpsController;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('rps')->name('rps.')->group(function () {
        Route::get('/', [RpsController::class, 'index'])->name('index');
        Route::get('/create', [RpsController::class, 'create'])->name('create');
        Route::post('/', [RpsController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RpsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RpsController::class, 'update'])->name('update');
        Route::get('/{id}/export/{format}', [RpsController::class, 'export'])->name('export');
    });
});

require __DIR__.'/settings.php';
