<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskPrintController;
use App\Models\TaskFumigasi;

Route::get('/', function () {
    return view('landing');
});

Route::get('/landing', function () {
    return view('landing');
});

Route::get('/test-db-limit', function () {
    return response()->view('errors.db-limit', [], 429);
});

// ------------------------------
// Route untuk Cetak PDF
// ------------------------------

// Cetak task reguler
Route::get('/task/{task}/print', [TaskPrintController::class, 'print'])
    ->name('task.print');

// Cetak task alih media
Route::get('/task-alih-media/{taskAlihMedia}/print', [TaskPrintController::class, 'printAlihMedia'])
    ->name('task-alih-media.print');

Route::get('/task-fumigasi/{taskFumigasi}/print', [TaskPrintController::class, 'printFumigasi'])
    ->name('task-fumigasi.print');
