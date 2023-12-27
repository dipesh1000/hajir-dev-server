<?php


use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('employerRoute.prefix.api'),

    'namespace' => config('employerRoute.namespace.api'),
], function () {

    //employer opt verification and first register

    Route::post('register', 'ApiEmployerAuthController@register');

    Route::post('verify-opt', 'ApiEmployerAuthController@verifyOtp');

    Route::post('password-submit', 'ApiEmployerAuthController@passwordSubmit');

    Route::group([
        'middleware' => ['auth:api', 'employerMiddleware']
    ], function () {

        Route::get('logout', 'ApiEmployerAuthController@logout');

        Route::post('profile-update', 'ApiEmployerAuthController@profileUpdate');

        Route::get('get-profile', 'ApiEmployerAuthController@getProfile')->name('getProfile');

        Route::group([
            'prefix' => 'company',
            'as' => 'company.'
        ], function () {
            Route::get('/all',  'ApiCompanyController@index');

            Route::post('/change-status/{companyid}',  'ApiCompanyController@changeStatus');

            Route::get('/active',  'ApiCompanyController@activeCompanies');

            Route::get('/inactive',  'ApiCompanyController@inactiveCompanies');

            Route::post('/store',  'ApiCompanyController@store');

            Route::post('/update/{id}',  'ApiCompanyController@update');

            Route::post('/status/{id}',  'ApiCompanyController@status');

            Route::post('/destroy/{id}',  'ApiCompanyController@destroy');

            Route::get('/employercompanies', 'ApiCompanyController@getCompaniesByEmployer');

            Route::get('/{id}',  'ApiCompanyController@getCompanyByID');

            Route::get('/{companyid}/generate-code', 'ApiCompanyController@generatecode');
        });


        Route::group([
            'prefix' => 'candidate'
        ], function () {
            Route::post('store/{companyid}', 'ApiCandidateController@store');

            Route::post('update/{company_id}/{candidate_id}', 'ApiCandidateController@update');

            Route::get('get-candidate/{companyid}/{candidate_id}', 'ApiCandidateController@getCompanySingleCandidate');

            Route::get('get-candidates/{companyid}', 'ApiCandidateController@getCandidatesByCompany');

            Route::get('get-activecandidates/{companyid}', 'ApiCandidateController@getActiveCandidatesByCompany');

            Route::get('get-inactivecandidates/{companyid}', 'ApiCandidateController@getInActiveCandidatesByCompany');

            Route::get('get-companies/{candidateid}', 'ApiCandidateController@getCompaniesByCandidateID');

            Route::post('/change-status/{companyid}/{candidate_id}',  'ApiCandidateController@changeStatus');

            Route::get('/destroy/{companyid}/{candidate_id}',  'ApiCandidateController@destroy');
        });

        Route::group([
            'prefix' => 'approver'
        ], function () {
            Route::post('store/{company_id}/{candidate_id}', 'ApiCandidateController@storeApprover');
            Route::post('delete/{company_id}/{candidate_id}', 'ApiCandidateController@deleteApprover');
            Route::get('list/{company_id}', 'ApiCandidateController@getApprovers');
        });

        Route::group([
            'prefix' => 'leave-type',

        ], function () {
            Route::get('/all', 'ApiLeavetypeController@index');

            Route::post('/store', 'ApiLeavetypeController@store');

            Route::post('/update/{leave_type_id}', 'ApiLeavetypeController@update');

            Route::post('/destroy/{leave_type_id}', 'ApiLeavetypeController@destroy');
        });

        Route::group([
            'prefix' => '{company_id}/invitation',

        ], function () {
            Route::get('/', 'ApiInvitationController@index');

            Route::post('/store', 'ApiInvitationController@store');

            Route::post('/update/{leave_type_id}', 'ApiInvitationController@update');

            Route::post('/destroy/{leave_type_id}', 'ApiInvitationController@destroy');

            Route::get('/all-candidates', 'ApiInvitationController@allCandidates');
        });

        Route::group([
            'prefix' => '{company_id}/attendance',

        ], function () {
            Route::get('/currentDayAttendaceDelete', 'ApiAttendanceController@currentDayAttendanceDelete');

            Route::get('/currentDayAttendace', 'ApiAttendanceController@currentDayAttendance');

            Route::post('/store', 'ApiAttendanceController@store');

            Route::post('/update/{leave_type_id}', 'ApiAttendanceController@update');

            Route::post('/destroy/{leave_type_id}', 'ApiAttendanceController@destroy');

            Route::get('/all-candidates', 'ApiAttendanceController@allCandidates');
        });

        //company candidate leaves

        Route::group([
            'prefix' => 'candidateLeave'
        ], function () {
            Route::get('all/{companyid}', 'ApiEmployerCandidateLeaveController@all');

            Route::get('detail/{id}', 'ApiEmployerCandidateLeaveController@leaveDetail');

            Route::post('change-status/{id}', 'ApiEmployerCandidateLeaveController@changeStatus');
        });


        Route::group([
            'prefix' => 'report'
        ], function () {
            //index
            Route::get('today/{companyid}', 'ApiEmployerReportController@currentDayReport');

            Route::get('today/all-candidate/{companyid}', 'ApiEmployerReportController@allCompanyCandidates'); //New 
            
            Route::get('today/active-candidate/{companyid}', 'ApiEmployerReportController@activeCompanyCandidates');

            Route::get('today/inactive-candidate/{companyid}', 'ApiEmployerReportController@inactiveCompanyCandidates');


            Route::get('daily-report/{company_id}/{candidate_id}', 'ApiEmployerReportController@dailyReport');

            Route::get('weekly-report/{company_id}/{candidate_id}', 'ApiEmployerReportController@weeklyReport');

            Route::get('report-filter/{company_id}/{year}/{month}', 'ApiEmployerReportController@filterReport');

            Route::get('report-pdf/{company_id}/{year}/{month}', 'ApiEmployerReportController@reportPdf');
            //get all months from two dates
            Route::get('all-months/{company_id}/{candidate_id}', 'ApiEmployerReportController@getAllCompanyCandidateMonths');

            Route::get('all-years/{company_id}/{candidate_id}', 'ApiEmployerReportController@getAllCompanyCandidateYears');

            Route::get('monthly-report/{company_id}/{candidate_id}/{month?}', 'ApiEmployerReportController@monthlyReport');

            Route::get('yearly-report/{company_id}/{candidate_id}/{year?}', 'ApiEmployerReportController@yearlyReport');

            Route::post('payment-submit/{company_id}/{candidate_id}', 'ApiEmployerReportController@paymentSubmit');

            Route::get('check-payment-status/{companyid}/{candidateid}/{month}', 'ApiEmployerReportController@checkPayment');

            //overall report
            Route::get('daily/{companyid}', 'ApiEmployerOverallReportController@dailyReport');

            Route::get('weekly/{companyid}', 'ApiEmployerOverallReportController@weeklyReport');

            Route::get('monthly/{companyid}', 'ApiEmployerOverallReportController@monthlyReport');

            Route::get('yearly/{companyid}', 'ApiEmployerOverallReportController@yearlyReport');
        });

        Route::post('change-phonenumber', 'ApiEmployerAuthController@changePhone')->name('changePhone');

        Route::group([
            'prefix' => 'package'
        ], function () {
            Route::get('all', 'ApiPackageController@index');
        });


        //notification
        Route::post('notification-send/{companyid}/{candidateid}', 'ApiEmployerCandidateNotificationController@notificationSent');

        //payment
        Route::post('payment-store/company_id/employer_id', 'ApiPaymentController@paymentStore')->name('paymentStore');


        
    });

    Route::get('report-pdf/{company_id}/{year}/{month}', 'ApiEmployerReportController@testPDF');
});


