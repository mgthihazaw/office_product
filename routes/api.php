<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
Route::group(['middleware' => 'jwt.auth'], function ($router) {

    Route::get('users', function () {
        $users = DB::table('users')->where('role', '!=', 1)->get();
        return response()->json(['users' => $users], 200);
    });

    Route::group([
        'prefix' => 'keys'
    ], function ($router) {
        Route::get('/count', 'KeyController@countData');
        Route::get('/', 'KeyController@all');
        Route::post('/', 'KeyController@create');
        Route::put('/update', 'KeyController@update');
        Route::Delete('/delete', 'KeyController@delete');
    });
});