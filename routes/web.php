<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Models\Book;

Route::get('/', function () {

        return redirect()->route('books.index');
});


Route::resource('/books',BookController::class)
    ->only(['index', 'show']);

Route::resource('books.reviews',ReviewController::class)
    ->scoped(['review' => 'book'])
    ->only(['create', 'store'])
    ->middleware(['throttle:reviews']);

Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])
    ->middleware('throttle:reviews')
    ->name('books.reviews.store');
//
