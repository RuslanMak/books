<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Writer;
use App\Genre;

class WritersController extends Controller
{
//    api for all writers
    public function allWriters()
    {
        return Writer::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalWriters =  DB::table('writers')->count();

        return view('writers')
            ->with('totalWriters', $totalWriters);
    }

    public function allWritersPaginated($skip, $take)
    {

        return DB::table('writers')
            ->select('writers.id', 'writers.name', 'writers.birthday', 'writers.id_genres')
            ->skip($skip)->take($take)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->genre);

        $writer = new Writer();
        $writer->name = $request->name;
        $writer->birthday = $request->birthday;
        $writer->id_genres = $request->genre;
        $writer->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Writer::findOrFail($id);
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
        $writer = Writer::findOrFail($id);
        $writer->name = $request->name;
        $writer->birthday = $request->birthday;
        $writer->id_genres = $request->genre;
        $writer->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Writer::findOrFail($id)->delete();
    }

    /**
     * FIlter
     */
    public function filter(Request $request, $skip, $take)
    {
        if (!isset($request->pages_max)) {
            $request->pages_max = 999999999;
        }
//            dd($request->pages_max);
//        ->select('writers.id', 'writers.name', 'writers.birthday', 'writers.id_genres')

        return DB::table('writers')
            ->join('genres', 'writers.id_genres', '=', 'genres.id')
//            ->select('books.id', 'books.title', 'books.pages', 'books.year', 'books.price', 'books.isbn', 'writers.name', 'genres.genre')
            ->select('writers.id', 'writers.name', 'writers.birthday', 'writers.id_genres', 'genres.genre')
            ->where('writers.name', 'LIKE', '%'. $request->name .'%')
//            ->where('writers.birthday', '=', ''.$request->birthday)
            ->where('writers.birthday', 'LIKE', '%'. $request->birthday .'%')
//            ->where('writers.id_genres', 'LIKE', '%'. $request->id_genres .'%')
            ->where('genres.genre', 'LIKE', '%'. $request->id_genres .'%')
            ->skip($skip)->take($take)
            ->get();
    }
}
