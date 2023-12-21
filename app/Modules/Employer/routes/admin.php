<?php


use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('employerRoute.prefix.backend'),
    'namespace' => config('employerRoute.namespace.backend'),
    'middleware' => ['web'],
    'as' => config('employerRoute.as.backend'),


], function () {

});
