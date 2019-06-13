<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


// These routes require the user to be logged in
Route::group(['middleware' => 'auth'], function () {
  //Start a game
  Route::post('games/start', 'GameController@store')->name('games.start');
  //Make a new play in an existing game
  Route::post('games/{game}/play', 'GameController@play')->name('games.play');
});

// These routes require no user to be logged in
Route::group(['middleware' => 'guest'], function () {

  //Get a list of games
  Route::get('games/', 'GameController@index')->name('games.list');
  //Details of a single game
  Route::get('games/{game}', 'GameController@show')->name('games.detail');


  //Player Details
  Route::get('players/{user}', 'PlayerController@details')->name('player.details');
  //List of players games
  Route::get('players/{user}/games', 'PlayerController@games')->name('player.games');


  //Get Leaderboard info
  Route::get('leaderboard', 'Leaderboard@index')->name('leaderboard');

});


