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


    Route::post('upload-image','Api\AuthController@uploadImage');


// Group
    Route::get('mygroup','groupController@myGroup');
    Route::get('group','groupController@index');
    Route::get('group/follow/{id}','groupController@follow');
    Route::get('group/{username}','groupController@show');
    Route::post('group','groupController@create');
    Route::post('group/edit/{id}','groupController@edit');
    Route::get('group/delete/{id}','groupController@delete');
    Route::get('group/quest/{id}','groupController@quest');//group

// QNA

    Route::get('quest/home','qnaController@quest_home');//home
    Route::get('quest/home/explore','qnaController@quest_home_explore');//home
    Route::post('quest','qnaController@create');
    Route::get('quest/follow/{id}','qnaController@follow');




    
});