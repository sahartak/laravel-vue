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

Route::middleware('auth:api')->group(function () {

});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', 'Api\AuthController@login')->name('api.login');
});

Route::group(['middleware' => ['cors', 'json.response', 'api.user']], function () {
    Route::get('/items', 'Api\ItemController@index')->name('api.items');
    Route::get('/item/{id}', 'Api\ItemController@item');
});
