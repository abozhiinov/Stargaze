<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/artists', function () {
    return view('artists');
});

Route::get('/places', function () {
    return view('places');
});

Route::get('/events', function () {
    return view('events');
});

Route::get('/register', function () {
    return view('register');
});

Route::get( '/artist/{username}', '\App\Http\Controllers\ArtistController@getArtistView' );

Route::get( '/place/{name}', '\App\Http\Controllers\PlaceController@getPlaceView' );

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');