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
Route::get('/api-all-books/{skip}/{take}', 'BooksController@allBooks');
Route::get('/api-all-writers', 'WritersController@allWriters');
Route::get('/api-all-genres', 'GenresController@allGenres');
Route::post('/api-save-book', 'BooksController@store');
Route::get('/api-book/{id}', 'BooksController@show');
Route::delete('/api-book-delete/{id}', 'BooksController@destroy');
Route::post('/api-book-update/{id}', 'BooksController@update');
