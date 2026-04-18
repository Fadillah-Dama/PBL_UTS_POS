<?php

use App\Http\Controllers\CashierAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [CashierAuthController::class, 'create'])->name('cashier.login');
    Route::post('/login', [CashierAuthController::class, 'store'])->name('cashier.login.store');
});

Route::middleware('cashier')->group(function () {
    Route::get('/', function () {
        return view('cashier');
    })->name('cashier.index');

    Route::post('/logout', [CashierAuthController::class, 'destroy'])->name('cashier.logout');
});
