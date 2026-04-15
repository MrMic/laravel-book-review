<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// ______________________________________________________________________
Route::get('/', function () {
    return view('welcome');
});

// ______________________________________________________________________
Route::resource('books', BookController::class);
