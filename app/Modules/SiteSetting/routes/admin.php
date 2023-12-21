<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'=>config('sitesettingroute.prefix.backend'),
    'namespace' => config('sitesettingroute.namespace.backend'),
    'as' => config('sitesettingroute.as.backend'),
    'middleware' => ['web','superAdminMiddleware']

],function(){

    Route::get('/create','SiteSettingController@create')->name('create');

    Route::post('/submit','SiteSettingController@siteSettingSubmit')->name('store');



});



