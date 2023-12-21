<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('superAdminRoute.prefix.backend'),
    'namespace' => config('superAdminRoute.namespace.backend'),
    'middleware' => ['web'],
    'as' => config('superAdminRoute.as.backend'),


], function () {
    Route::group([
        'prefix'=>'super-admin',
    ],function(){
        Route::get('/login','SuperAdminAuthController@login')->name('login');

        Route::post('/login-submit','SuperAdminAuthController@loginSubmit')->name('loginSubmit');

        Route::group(
            ['middleware' => 'superAdminMiddleware']
        ,function(){
            Route::get('/dashboard','SuperAdminController@dashboard')->name('dashboard');

            Route::get('/profile','SuperAdminController@profile')->name('profile');

            Route::post('/profile-update','SuperAdminController@profileUpdate')->name('profileUpdate');

            Route::post('/password-update','SuperAdminController@passwordUpdate')->name('passwordUpdate');

            Route::get('/logout','SuperAdminAuthController@logout')->name('logout');

            // Company 
            Route::get('all-companies','SuperAdminCompanyController@index')->name('company.index');

            Route::get('company/view/{id}','SuperAdminCompanyController@show')->name('company.show');

            Route::get('company/delete/{id}','SuperAdminCompanyController@destroy')->name('company.destroy');

            Route::get('get-companies-data','SuperAdminCompanyController@getCompanyData')->name('company.getCompanyData');


            // Employeer 
            Route::get('all-employers','SuperAdminEmployerController@index')->name('employer.index');

            Route::get('employer/view/{id}','SuperAdminEmployerController@show')->name('employer.show');

            Route::get('employer/delete/{id}','SuperAdminEmployerController@destroy')->name('employer.destroy');

            Route::get('get-employers-data','SuperAdminEmployerController@getEmployerData')->name('employer.getEmployerData');

            // Leave Type CRUD 
            Route::get('all-leave-types','SuperAdminLeaveTypeController@index')->name('leave.type.index');

            Route::post('leave-type/store/','SuperAdminLeaveTypeController@store')->name('leave.type.store');

            // Route::get('leave-type/view/{id}','SuperAdminLeaveTypeController@show')->name('leave.type.show');

            Route::get('leave-type/edit/{slug}','SuperAdminLeaveTypeController@edit')->name('leave.type.edit');

            Route::post('leave-type/update/{slug}','SuperAdminLeaveTypeController@update')->name('leave.type.update');

            Route::get('leave-type/delete/{slug}','SuperAdminLeaveTypeController@destroy')->name('leave.type.destroy');

            Route::get('get-leaveTypes-data','SuperAdminLeaveTypeController@getLeaveTypeData')->name('leave.type.getLeaveTypeData');


            // Package CRUD
            Route::get('all-packages','SuperAdminPackageController@index')->name('package.index');

            Route::get('package/create','SuperAdminPackageController@create')->name('package.create');

            Route::post('package/store/','SuperAdminPackageController@store')->name('package.store');

            Route::get('package/view/{id}','SuperAdminPackageController@show')->name('package.show');

            Route::get('package/edit/{slug}','SuperAdminPackageController@edit')->name('package.edit');

            Route::post('package/update/{slug}','SuperAdminPackageController@update')->name('package.update');

            Route::get('package/delete/{slug}','SuperAdminPackageController@destroy')->name('package.destroy');

            Route::get('get-packages-data','SuperAdminPackageController@getPackageData')->name('package.getPackageData');
        });
    });
});
