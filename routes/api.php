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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('posts', 'App\Http\Controllers\PostController@index');
Route::get('comments', 'App\Http\Controllers\CommentController@index');
Route::delete('posts/{id}', 'App\Http\Controllers\PostController@delete');
Route::delete('comments/{id}', 'App\Http\Controllers\CommentController@delete');
Route::post('comments', 'App\Http\Controllers\CommentController@store');
