<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Genre;

class GenresController extends Controller
{
    //    api for all writers
    public function allGenres()
    {
        return Genre::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalGenres =  DB::table('genres')->count();

        return view('genres')
            ->with('totalGenres', $totalGenres);
    }

    /**
     * Paginated
     */
    public function allPaginated($skip, $take)
    {

        return DB::table('genres')
            ->select('genres.id', 'genres.genre')
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
        $genre = new Genre();
        $genre->genre = $request->genre;
        $genre->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Genre::findOrFail($id);
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
        $genreNew = Genre::findOrFail($id);
        $genreNew->genre = $request->genre;
        $genreNew->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Genre::findOrFail($id)->delete();
    }

    /**
     * FIlter
     */
    public function filter(Request $request, $skip, $take)
    {
        return DB::table('genres')
            ->select('genres.id', 'genres.genre')
            ->where('genres.genre', 'LIKE', '%'. $request->genre .'%')
            ->skip($skip)->take($take)
            ->get();
    }
}
