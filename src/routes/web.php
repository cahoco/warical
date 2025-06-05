<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecordController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\CalendarController;

Route::get('/records/create', [RecordController::class, 'create'])->name('records.create');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/memo', [MemoController::class, 'store'])->name('memo.store');
Route::post('/memo/auto-save', [MemoController::class, 'autoSave'])->name('memo.autoSave');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');