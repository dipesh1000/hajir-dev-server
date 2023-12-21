<?php

use CMS\Http\Controllers\Gallery\GalleryController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('candidateRoute.prefix.backend'),
    'namespace' => config('candidateRoute.namespace.backend'),
    'middleware' => ['web'],
    'as' => config('candidateRoute.as.backend'),


], function () {

});
