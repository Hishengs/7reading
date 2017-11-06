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

Route::get('/manage', function () {
  return view('manage');
});

Route::get('/essay', 'EssayController@index');

Route::get('/essay/post', function () {
 	return view('post');
});

Route::post('/essay/post', 'EssayController@post');

Route::get('/essay/{id}/edit', 'EssayController@edit');
Route::post('/essay/update', 'EssayController@update');
