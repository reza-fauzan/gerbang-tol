<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegencyController;

Route::get('/', function () {
    return view('welcome', ['title' => 'Informasi Lokasi Gerbang Tol di Indonesia']);
});

Route::get('/about', function () {
    return view('about', ['title' => 'About']);
});

Route::get('/tematik', [RegencyController::class, 'index']);
