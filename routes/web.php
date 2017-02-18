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
    return view('home');
});

Route::get('collections', function() {
    include('../docs/collections.php');

    return response()->json((analyzeArray([13, 24, 91, 120, 41, 76, 91, 46, 71, 101, 259, 12, 41, 28, 73, 33, 58])));
});

Route::post('github/authorize', 'GithubController@authorizeUser');
Route::get('github/profile', 'GithubController@viewProfile');

Auth::routes();

Route::get('/home', 'HomeController@index');
