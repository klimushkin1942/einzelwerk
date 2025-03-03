<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounterpartyController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/counterparties', [CounterpartyController::class, 'store'])->name('counterparties.store');
    Route::get('/counterparties', [CounterpartyController::class, 'index'])->name('counterparties.index');
});
