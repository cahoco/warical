<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecordController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;

Route::get('/records/create', [RecordController::class, 'create'])->name('records.create');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/memo', [MemoController::class, 'store'])->name('memo.store');
Route::post('/memo/auto-save', [MemoController::class, 'autoSave'])->name('memo.autoSave');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::post('/travel-mode-toggle', [SettingController::class, 'toggleTravelMode'])->name('toggle.travel_mode');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/settings/payment', [SettingController::class, 'editPayment'])->name('settings.payment');
Route::post('/settings/payment', [SettingController::class, 'updatePayment'])->name('settings.payment.update');
Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');

Route::prefix('settings')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
