<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'ShortenedUrlController@index');
Route::post('shorten', 'ShortenedUrlController@store')->name('shorten.store');
Route::get('{code}', 'ShortenedUrlController@shortUrlLink')->name('shorten.url');
