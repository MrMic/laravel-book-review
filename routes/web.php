<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// ______________________________________________________________________
Route::get('/', function () {
    return view('welcome');
});

// ______________________________________________________________________
Route::resource('books', BookController::class)
    ->only(['index', 'show']);

// ______________________________________________________________________
Route::resource('books.reviews', ReviewController::class)
    ->scoped(['review' => 'book'])
    ->only(['create', 'store']);
