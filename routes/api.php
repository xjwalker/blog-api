<?php

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

Route::post('signup', 'AuthController@signup');
Route::post('login', 'AuthController@login');

Route::get('/', 'BlogController@getDashboard')->middleware('auth.jwt');

Route::group(['prefix' => 'blog'], function () {
    Route::get('/', 'BlogController@getBlogContent');
    Route::group(['middleware' => 'jwt.auth:api'], function () {
        Route::post('/', 'BlogController@createBlog');
        Route::patch('/', 'BlogController@updateBlog');
        Route::delete('/', 'BlogController@deleteBlog');
    });
});
