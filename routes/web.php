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

Route::get('/profile', function () {
    return view( 'my-profile' );
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get( '/artist/{username}', '\App\Http\Controllers\ArtistController@getArtistView' );
Route::get( '/place/{username}', '\App\Http\Controllers\PlaceController@getPlaceView' );
Route::get( '/artistInvitations/{username}', '\App\Http\Controllers\AjaxController@loadArtistInvitations' );
Route::get( '/placeInvitations/{username}', '\App\Http\Controllers\AjaxController@loadPlaceInvitations' );
Route::get( '/artistEdit', '\App\Http\Controllers\AjaxController@editArtist' );

Route::post( '/dashboardFilter', '\App\Http\Controllers\AjaxController@dashboardFilter' );
Route::post( '/eventsFilter', '\App\Http\Controllers\AjaxController@eventsFilter' );
Route::post( '/addNewArtist', '\App\Http\Controllers\AjaxController@addNewArtist' );
Route::post( '/addNewPlace', '\App\Http\Controllers\AjaxController@addNewPlace' );
Route::post( '/inviteArtist', '\App\Http\Controllers\AjaxController@inviteArtist' );
Route::post( '/createEvent', '\App\Http\Controllers\AjaxController@createEvent' );
Route::post( '/createNewEvent', '\App\Http\Controllers\AjaxController@createNewEvent' );
Route::post( '/storeImage', '\App\Http\Controllers\FileUploadController@store' );

Route::put( '/editPlace', '\App\Http\Controllers\AjaxController@updateEditedPlace' );
Route::put( '/editArtist', '\App\Http\Controllers\AjaxController@updateEditedArtist' );
Route::put( '/statusInvitation', '\App\Http\Controllers\AjaxController@statusInvitation' );

Route::delete( '/artistDelete/{username}', '\App\Http\Controllers\AjaxController@deleteArtist' );
Route::delete( '/placeDelete/{username}', '\App\Http\Controllers\AjaxController@deletePlace' );
Route::delete( '/deleteInvitation', '\App\Http\Controllers\AjaxController@deleteInvitation' );
