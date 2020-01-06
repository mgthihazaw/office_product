<?php

use Illuminate\Http\Request;

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
Route::group(['middleware' => 'jwt.auth'], function ($router) {

    Route::get('test', function () {
        return "TEST";
    });

    Route::group([
        'prefix' => 'keys'
    ], function ($router) {
        Route::get('/count', 'KeyController@countData');
        Route::get('/', 'KeyController@all');
        Route::post('/', 'KeyController@create');
        Route::Delete('/delete', 'KeyController@delete');
    });
});