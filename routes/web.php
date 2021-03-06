<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'api'], function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/books', 'BooksController@index');
    Route::get('/api-all-books/{skip}/{take}', 'BooksController@allBooks');
    Route::get('/api-all-writers/{skip}/{take}', 'WritersController@allWritersPaginated');
    Route::get('/api-all-writers', 'WritersController@allWriters');
    Route::get('/api-all-genres', 'GenresController@allGenres');
    Route::post('/api-save-book', 'BooksController@store');
    Route::get('/api-book/{id}', 'BooksController@show');
    Route::delete('/api-book-delete/{id}', 'BooksController@destroy');
    Route::post('/api-book-update/{id}', 'BooksController@update');

    Route::get('/api-filtered-books/{skip}/{take}/', 'BooksController@filteredBooks');

    Route::get('/writers', 'WritersController@index');
    Route::post('/api-save-writer', 'WritersController@store');
    Route::get('/api-writer/{id}', 'WritersController@show');
    Route::post('/api-writer-update/{id}', 'WritersController@update');
    Route::delete('/api-writer-delete/{id}', 'WritersController@destroy');
    Route::get('/api-filtered-writers/{skip}/{take}/', 'WritersController@filter');

    Route::get('/genres', 'GenresController@index');
    Route::get('/api-all-genres/{skip}/{take}', 'GenresController@allPaginated');
    Route::get('/api-filtered-genres/{skip}/{take}/', 'GenresController@filter');
    Route::post('/api-save-genres', 'GenresController@store');
    Route::get('/api-genre/{id}', 'GenresController@show');
    Route::post('/api-genre-update/{id}', 'GenresController@update');
    Route::delete('/api-genre-delete/{id}', 'GenresController@destroy');
});
