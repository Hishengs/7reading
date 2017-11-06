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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 文章
Route::prefix('essay')->group(function(){

	Route::get('/random', 'EssayController@random');

	Route::post('/add', 'EssayController@add');

	Route::post('/save', 'EssayController@save');

	Route::post('/delete', 'EssayController@del');

	Route::post('/list', 'EssayController@getList');

	Route::post('/query', 'EssayController@query');

	Route::post('/fetch', 'EssayController@fetch');
});

// 文集
Route::prefix('corpus')->group(function(){

	Route::post('/add', 'CorpusController@add');

	Route::post('/save', 'CorpusController@save');

	Route::post('/delete', 'CorpusController@del');

	Route::post('/list', 'CorpusController@getList');

	Route::post('/query', 'CorpusController@query');

});