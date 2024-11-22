<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Models\Book;

Route::get('/', function () {

        return redirect()->route('books.index');
});


Route::resource('/books',BookController::class);
