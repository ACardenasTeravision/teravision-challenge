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

Route::get('get-top-urls', 'ShortenedUrlController@getTopUrls');
Route::get('get-shorten-url/{url}', 'ShortenedUrlController@getShortenUrl')->where('url', '(.*)');
Route::get('get-link/{code}', 'ShortenedUrlController@getLink');