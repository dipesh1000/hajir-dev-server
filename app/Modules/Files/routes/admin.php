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


Route::group([
    'prefix'=>config('filesroute.prefix.admin'),
    'namespace' => config('filesroute.namespace.admin'),
    'as' => config('filesroute.as.admin'),
    'middleware' => ['web']

],function(){

    Route::get('/', 'FilesController@index')->name('index');
    Route::get('/upload','FilesController@upload')->name('upload');
    Route::post('/store','FilesController@store')->name('store');
    Route::post('/delete/{id}','FilesController@delete')->name('delete');
    Route::get('/view/{id}','FilesController@view')->name('view');

    Route::get('/getUploadFiles','FilesController@getUploadFiles')->name('getUploadFiles');


});
