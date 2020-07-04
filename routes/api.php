<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/loginfb', 'Api\AuthController@loginfb');
Route::post('/login', 'Api\AuthController@login');


Route::middleware('auth:api')->group(function(){


    Route::get('group','GroupController@index');
    Route::get('group/{id}','GroupController@show');
    Route::post('group','GroupController@create');
    Route::post('group/edit/{id}','GroupController@edit');
    Route::get('group/delete/{id}','GroupController@delete');

    
});