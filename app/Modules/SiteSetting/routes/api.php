<?php


use Illuminate\Support\Facades\Route;


Route::group([
    'prefix'=>config('sitesettingroute.prefix.api'),
    'namespace' => config('sitesettingroute.namespace.api'),
    // 'middleware' => ['auth:api'],

],function(){

    Route::get('/','SiteSettingApiController@getSiteDetails');

});



