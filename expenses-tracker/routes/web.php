<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
        // Income Category routes
        Route::get('/income-categories', [\App\Http\Controllers\IncomeCategoryController::class, 'index']);
        Route::post('/income-categories/store', [\App\Http\Controllers\IncomeCategoryController::class, 'store']);
        Route::post('/income-categories/update/{id}', [\App\Http\Controllers\IncomeCategoryController::class, 'update']);
        Route::delete('/income-categories/delete/{id}', [\App\Http\Controllers\IncomeCategoryController::class, 'destroy']);

        // Income routes
        Route::get('/income', [\App\Http\Controllers\IncomeController::class, 'index'])->name('income.page');
        Route::post('/income/store', [\App\Http\Controllers\IncomeController::class, 'store']);
        Route::post('/income/update/{id}', [\App\Http\Controllers\IncomeController::class, 'update']);
        Route::delete('/income/delete/{id}', [\App\Http\Controllers\IncomeController::class, 'destroy']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/test-sms', [ProfileController::class, 'testSms'])->name('profile.testSms');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/categories/store', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.page');

    Route::post('/expenses/store', [ExpenseController::class, 'store']);
    Route::post('/expenses/update', [ExpenseController::class, 'update']);
    Route::delete('/expenses/delete/{id}', [ExpenseController::class, 'destroy']);

    // For pie chart click
    Route::get('/expenses/category/{id}', [ExpenseController::class, 'categoryExpenses']);

    // Export expenses as PDF
    Route::get('/expenses/export-pdf', [ExpenseController::class, 'exportPdf'])->name('expenses.exportPdf');

    // Send Report
    Route::post('/expenses/send-report', [ExpenseController::class, 'sendReport'])->name('expenses.sendReport');

    // Notification routes
    Route::get('/notifications', [ExpenseController::class, 'notifications']);
    Route::post('/notifications/read/{id}', [ExpenseController::class, 'markNotificationRead']);
    Route::delete('/notifications/delete/{id}', [ExpenseController::class, 'deleteNotification']);
});



require __DIR__.'/auth.php';
