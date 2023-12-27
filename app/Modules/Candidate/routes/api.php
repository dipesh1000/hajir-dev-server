<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('candidateRoute.prefix.api'),
    'namespace' => config('candidateRoute.namespace.api'),
], function () {

    Route::post('register', 'ApiCandidateAuthController@register')->name('register');

    Route::post('verify-opt', 'ApiCandidateAuthController@verifyOtp')->name('verifyOtp');

    Route::post('password-submit', 'ApiCandidateAuthController@passwordSubmit')->name('passwordSubmit');
    
     Route::post('device-token', 'ApiCandidateAuthController@updateDeviceToken')->name('updateDeviceToken');

    Route::group([
        'middleware' => ['candidateMiddleware']
    ], function () {

        Route::get('logout', 'ApiCandidateAuthController@logout')->name('logout');

        Route::get('get-profile', 'ApiCandidateAuthController@getProfile')->name('getProfile');

        Route::post('profile-update', 'ApiCandidateAuthController@profileUpdate')->name('profileUpdate');

        Route::get('today-details/{company_id}', 'ApiAttendanceCandidateController@getCandidateTodayDetails');

        Route::get('currentDayAttendanceDelete/{company_id}', 'ApiAttendanceCandidateController@currentDayAttendanceDelete');

        Route::post('attendance-store/{company_id}', 'ApiAttendanceCandidateController@attendanceStore');

        Route::post('attendance-update/{company_id?}/{attendance_id?}', 'ApiAttendanceCandidateController@attendanceUpdate');

        Route::post('attendance-break-store/{attendance_id}', 'ApiAttendanceCandidateController@attendanceBreakStore');

        Route::post('attendance-break-update/{break_id}', 'ApiAttendanceCandidateController@attendanceBreakUpdate');

        Route::group([
            'prefix' => 'incomehistory',
        ], function () {
            Route::get('weekly/{companyid}', 'CompanyCandidateIncomeHistory@weeklyIncomeHistory');

            Route::get('monthly/{companyid}', 'CompanyCandidateIncomeHistory@monthlyIncomeHistory');
        });


        Route::get('get-companies', 'ApiCandidateCompanyController@getCompaniesByCandidateID');

        Route::post('delete-company/{id}', 'ApiCandidateCompanyController@deleteCandidateCompany');

        Route::get('all-leaves/{companyid}', 'ApiCandidateLeaveController@allCandidateLeave');

        Route::post('store-leave/{companyid}', 'ApiCandidateLeaveController@storeCandidateLeave');

        Route::get('update-leave/{companyid}/{leave_id}', 'ApiCandidateLeaveController@updateCandidateLeave');

        Route::get('delete-leave/{companyid}/{leave_id}', 'ApiCandidateLeaveController@deleteCandidateLeave');

        Route::get('leave-types/{company_id}', 'ApiCandidateLeaveController@getLeaveTypes');


        Route::group([
            'prefix' => '/invitation',

        ], function () {
            Route::get('all', 'ApiCandidateInvitationController@allCandidateInvitations');

            Route::post('invitation-update/{invitation_id}', 'ApiCandidateInvitationController@updateCandidateInvitation');
        });



        Route::group([
            'prefix' => 'report',
        ], function () {
            Route::get('month/allweeks/{company_id}/{date}', 'ApiCandidateReportController@monthAllWeeks');

            Route::get('year/allmonths/{company_id}/{year}', 'ApiCandidateReportController@yearAllMonths');

            Route::get('weekly/{company_id}', 'ApiCandidateReportController@weeklyReport');

            Route::get('monthly/{company_id}/{month}', 'ApiCandidateReportController@monthlyReport');

            Route::get('yearly/{company_id}/{year}', 'ApiCandidateReportController@yearlyReport');
        });



        Route::post('change-phonenumber', 'ApiCandidateAuthController@changePhone')->name('changePhone');

        //unread notifications
        Route::get('notifications', 'ApiCandidateNotificationController@notifications');


        Route::get('/mark-notification-read', 'ApiCandidateNotificationController@markNotificationRead')->name('markNotificationRead');
        Route::get('/mark-singlenotification-read/{id}', 'ApiCandidateNotificationController@markSingleNotificationRead')->name('markSingleNotificationRead');
    });


    Route::group([
        'prefix' => 'approver'
    ], function () {
        Route::get('enroll-attendee/clock-in/{companyid}', 'EnrollController@clockIn');

        Route::get('enroll-attendee/{companyid}', 'EnrollController@clockIn');

        Route::post('report-submit/{companyid}/{candidateid}/{attendanceid}', 'EnrollController@report');

        //missing attendance submit
        Route::post('missing-attendance-submit', 'EnrollController@missingAttenanceSubmit');
        //
        Route::get('approve-attendance/{attendance_id}', 'EnrollController@approveAttendance');

        
        Route::get('candidates/{companyid}', 'EnrollController@getCandidates');
    });

    //enroll attendee

});
