<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input("title");
        $filter = $request->input('filter', '');


        $books = Book::when(
            $title,
            function ($query) use ($title){
                return $query->title($title);
            });

        $books = match($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->PopularLast6Months(),
            'highest_rated_last_month' => $books->HighestRatedLastMonth(),
            'highest_rated_last_6months' => $books->HighestRatedLast6Months(),
            default =>$books->latest()
        };

       // $books = $books->get();
       //$books = Cache::remember('', 3600, fn() => $books->get);

       $cacheKey = 'books:' . $filter .':' . $title;
        $books = cache()->remember($cacheKey , 3600, fn() => $books->get());

        // $books = cache()->remember($cacheKey , 3600, function()use($books){
        //    // dd('not from cache');
        //     return $books->get();
        // } );


        return view('books.index', ['books'=> $books]);
    }

    /**
     * fn($query, $title) => $query->title($title)
     * function ($query) use ($title){ return $query->title($title);
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $cacheKey = 'book:'. $book->id;

        $book = cache()->remember($cacheKey,3600, fn() => $book->load([
            'reviews' => fn ($query) => $query->latest()
        ]));



        return view('books.show', ['book'=> $book]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
