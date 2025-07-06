<?php

use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\Cabinet\TransactionMonthlyController;
use App\Http\Controllers\Cabinet\TransactionYearlyController;
use App\Http\Controllers\Cabinet\TransactionAllTimeController;
use App\Http\Controllers\Cabinet\CashAccountController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/monthlyStats', [TransactionMonthlyController::class, 'index'])->name('monthlyStats');
    Route::get('/yearlyStats', [TransactionYearlyController::class, 'index'])->name('yearlyStats');
    Route::get('/allTimeStats', [TransactionAllTimeController::class, 'index'])->name('allTimeStats');

    Route::get('/transactions', [TransactionMonthlyController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionMonthlyController::class, 'store'])->name('transactions.store');

    Route::get('/cashAccount', [CashAccountController::class, 'index'])->name('cashAccount');
    Route::put('/accounts/{account}', [CashAccountController::class, 'update'])->name('accounts.update');
    Route::post('/accounts', [CashAccountController::class, 'store'])->name('accounts.store');
    Route::delete('/accounts/{account}', [CashAccountController::class, 'destroy'])->name('accounts.destroy');




    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
