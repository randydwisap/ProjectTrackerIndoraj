<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskPrintController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/landing', function () {
    return view('landing');
});

Route::get('/test-db-limit', function () {
    return response()->view('errors.db-limit', [], 429);
});

// Route print dipindah ke luar, agar tidak di-nest secara salah
Route::get('/task/{task}/print', [TaskPrintController::class, 'print'])
    ->name('task.print');
