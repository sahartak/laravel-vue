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


Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', 'Api\UserController@login')->name('api.login');

    Route::group(['middleware' => ['api.user']], function () {
        Route::get('account', 'Api\UserController@account');
        Route::put('settings', 'Api\UserController@settings');
        Route::post('items', 'Api\ItemController@index')->name('api.items');
        Route::get('items/{id}', 'Api\ItemController@item')->where('id', '[0-9]+');
        Route::post('items/{id}/bid', 'Api\ItemController@bid')->name('api.items')->where('id', '[0-9]+');
    });
});


