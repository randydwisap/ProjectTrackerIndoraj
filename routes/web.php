<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// New route for the landing page
Route::get('/landing', function () {
    return view('landing');
});
Route::get('/test-db-limit', function () {
    return response()->view('errors.db-limit', [], 429);
});
