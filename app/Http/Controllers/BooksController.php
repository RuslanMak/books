<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Book;
use App\Genre;
use App\Writer;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalItems =  DB::table('books')->count();

        return view('books')
            ->with('totalItems', $totalItems);
    }

    public function allBooks($skip, $take)
    {

        return DB::table('books')
            ->join('writers', 'books.id_writer', '=', 'writers.id')
            ->join('genres', 'books.id_genre', '=', 'genres.id')
//            ->select('books.id', 'books.title', 'books.pages', 'books.year', 'books.price', 'books.isbn', 'writers.name', 'genres.genre')
            ->select('books.id', 'books.title', 'books.pages', 'books.year', 'books.price', 'books.isbn', 'books.id_writer', 'books.id_genre', 'writers.name', 'genres.genre')
            ->skip($skip)->take($take)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());
//        return $request->all();

        $book = new Book();
        $book->id_writer = $request->writer;
        $book->title = $request->title;
        $book->id_genre = $request->genre;
        $book->pages = $request->pages;
        $book->year = $request->year;
        $book->price = $request->price;
        $book->isbn = $request->isbn;
        $book->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Book::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->id_writer = $request->writer;
        $book->title = $request->title;
        $book->id_genre = $request->genre;
        $book->pages = $request->pages;
        $book->year = $request->year;
        $book->price = $request->price;
        $book->isbn = $request->isbn;
        $book->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Book::findOrFail($id)->delete();
    }

    /**
     * FIlter
     */
    public function filteredBooks(Request $request, $skip, $take)
    {
        if (!isset($request->pages_max)) {
            $request->pages_max = 999999999;
        }
//            dd($request->pages_max);

        return DB::table('books')
            ->join('writers', 'books.id_writer', '=', 'writers.id')
            ->join('genres', 'books.id_genre', '=', 'genres.id')
//            ->select('books.id', 'books.title', 'books.pages', 'books.year', 'books.price', 'books.isbn', 'writers.name', 'genres.genre')
            ->select('books.id', 'books.title', 'books.pages', 'books.year', 'books.price', 'books.isbn', 'books.id_writer', 'books.id_genre', 'writers.name', 'genres.genre')
            ->where('books.title', 'LIKE', '%'. $request->title .'%')
            ->where('books.pages', '>=', ''.$request->pages_min)
            ->where('books.pages', '<=', ''.$request->pages_max)
            ->where('books.year', 'LIKE', '%'. $request->year .'%')
            ->where('books.price', 'LIKE', '%'. $request->price .'%')
            ->where('books.isbn', 'LIKE', '%'. $request->isbn .'%')
            ->where('writers.name', 'LIKE', '%'. $request->name .'%')
            ->where('genres.genre', 'LIKE', '%'. $request->genre .'%')
            ->skip($skip)->take($take)
            ->get();
    }
}
