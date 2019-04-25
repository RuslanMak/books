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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/books', 'BooksController@index');
Route::get('/api-all-books/{skip}/{take}', 'BooksController@allBooks')->middleware('api');
Route::get('/api-all-writers/{skip}/{take}', 'WritersController@allWritersPaginated')->middleware('api');
Route::get('/api-all-writers', 'WritersController@allWriters')->middleware('api');
Route::get('/api-all-genres', 'GenresController@allGenres')->middleware('api');
Route::post('/api-save-book', 'BooksController@store')->middleware('api');
Route::get('/api-book/{id}', 'BooksController@show')->middleware('api');
Route::delete('/api-book-delete/{id}', 'BooksController@destroy')->middleware('api');
Route::post('/api-book-update/{id}', 'BooksController@update')->middleware('api');

Route::get('/api-filtered-books/{skip}/{take}/', 'BooksController@filteredBooks')->middleware('api');

Route::get('/writers', 'WritersController@index');
Route::post('/api-save-writer', 'WritersController@store')->middleware('api');
Route::get('/api-writer/{id}', 'WritersController@show')->middleware('api');
Route::post('/api-writer-update/{id}', 'WritersController@update')->middleware('api');
Route::delete('/api-writer-delete/{id}', 'WritersController@destroy')->middleware('api');
Route::get('/api-filtered-writers/{skip}/{take}/', 'WritersController@filter')->middleware('api');

Route::get('/genres', 'GenresController@index');
Route::get('/api-all-genres/{skip}/{take}', 'GenresController@allPaginated')->middleware('api');
Route::get('/api-filtered-genres/{skip}/{take}/', 'GenresController@filter')->middleware('api');
Route::post('/api-save-genres', 'GenresController@store')->middleware('api');
Route::get('/api-genre/{id}', 'GenresController@show')->middleware('api');
Route::post('/api-genre-update/{id}', 'GenresController@update')->middleware('api');
Route::delete('/api-genre-delete/{id}', 'GenresController@destroy')->middleware('api');
