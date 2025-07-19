<?php

use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\Cabinet\TransactionMonthlyController;
use App\Http\Controllers\Cabinet\TransactionYearlyController;
use App\Http\Controllers\Cabinet\TransactionAllTimeController;
use App\Http\Controllers\Cabinet\CashAccountController;
use App\Http\Controllers\Cabinet\CategoriesExpenseController;
use App\Http\Controllers\Cabinet\CategoriesIncomeController;
use App\Http\Controllers\Cabinet\TransactionHistoryController;
use App\Http\Controllers\Cabinet\DebtController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'monthlyStats.index' : 'login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/monthlyStats', [TransactionMonthlyController::class, 'index'])->name('monthlyStats.index');
    Route::post('/monthlyStats', [TransactionMonthlyController::class, 'store'])->name('monthlyStats.store');
    Route::get('/yearlyStats', [TransactionYearlyController::class, 'index'])->name('yearlyStats');
    Route::get('/allTimeStats', [TransactionAllTimeController::class, 'index'])->name('allTimeStats');

    Route::resource('accounts', CashAccountController::class);
    Route::resource('categoriesExpense', CategoriesExpenseController::class);
    Route::resource('categoriesIncome', CategoriesIncomeController::class);


    Route::get('/transactionHistory', [TransactionHistoryController::class, 'index'])
        ->name('transactionHistory.index');

    Route::delete('/transactionHistory/{transaction}', [TransactionHistoryController::class, 'destroy'])
        ->name('transactionHistory.destroy');

    Route::resource('debts', DebtController::class);
    Route::patch('/debts/{debt}/toggle-status', [DebtController::class, 'toggleStatus'])->name('debts.toggleStatus');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/export/sql', [ProfileController::class, 'exportSql'])->name('profile.export.sql');
    Route::post('/profile/import', [ProfileController::class, 'importSql'])->name('cabinet.profile.import.sql');





});

require __DIR__.'/auth.php';
