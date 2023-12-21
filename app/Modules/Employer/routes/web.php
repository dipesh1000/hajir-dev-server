<?php


use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('employerRoute.prefix.backend'),
    'namespace' => config('employerRoute.namespace.backend'),
    'middleware' => ['web'],
    'as' => config('employerRoute.as.backend'),
], function () {


    Route::get('register', 'EmployerAuthController@register')->name('register');

    Route::post('register-submit', 'EmployerAuthController@registerSubmit')->name('registerSubmit');

    Route::get('verify-otp', 'EmployerAuthController@verifyOtp')->name('verifyOtp');

    Route::post('verify-otp-submit', 'EmployerAuthController@verifyOtpSubmit')->name('verifyOtpSubmit');

    Route::group([
        'middleware' => ['employerWebMiddleware']
    ], function () {
        // Company 
        Route::get('all-companies','EmployerCompanyController@index')->name('company.index');

        Route::get('company/create','EmployerCompanyController@create')->name('company.create');

        Route::post('company/store-submit','EmployerCompanyController@store')->name('company.store');

        Route::get('company/edit/{slug}','EmployerCompanyController@edit')->name('company.edit');

        Route::post('company/update-submit/{slug}','EmployerCompanyController@update')->name('company.update');

        Route::get('company/view/{slug}','EmployerCompanyController@show')->name('company.show');

        Route::get('company/delete/{slug}','EmployerCompanyController@destroy')->name('company.destroy');

        Route::get('company/get-companies-data','EmployerCompanyController@getCompanyData')->name('company.getCompanyData');

        Route::post('company/status-change/{slug}','EmployerCompanyController@statusChange')->name('company.statusChange');

        Route::get('company/view-employees/{slug}','EmployerCompanyController@viewEmployees')->name('company.viewEmployees');

        Route::get('company/monthly-reports/{slug}','EmployerCompanyController@monthlyReports')->name('company.monthlyReports');

        // Candidates 
        // Route::get('all-candidates','EmployerCandidateController@index')->name('candidate.index');

        Route::post('candidate/send-invitation/{company_id}','EmployerCandidateController@sendInvitation')->name('candidate.sendInvitation');

        Route::get('candidate/create/{company_slug}','EmployerCandidateController@create')->name('candidate.create');

        Route::post('candidate/store-submit/{company_slug}','EmployerCandidateController@store')->name('candidate.store');

        Route::get('candidate/edit/{company_candidate_id}','EmployerCandidateController@edit')->name('candidate.edit');

        Route::post('candidate/update-submit/{company_candidate_id}','EmployerCandidateController@update')->name('candidate.update');

        Route::get('candidate/view/{company_candidate_id}','EmployerCandidateController@show')->name('candidate.show');

        Route::get('candidate/delete/{company_candidate_id}','EmployerCandidateController@destroy')->name('candidate.destroy');

        Route::post('candidate-status-change/{company_candidate_id}','EmployerCandidateController@statusChange')->name('candidate.statusChange');
        
        Route::get('candidate/get-candidates-data/{company_id}','EmployerCandidateController@getCandidateData')->name('candidate.getCandidateData');
        
        Route::get('company/today-attendance/{company_id}','EmployerReportController@currentDayAttenanceReport')->name('company.currentDayReport');
        
        Route::get('/company/candidate-daily-report/{company_id}/{candidate_id}','EmployerReportController@candidateDailyAttendanceReport')->name('company.candidateDailyAttendanceReport');

        Route::get('/company/candidate-weekly-report/{company_id}/{candidate_id}','EmployerReportController@candidateWeeklyAttendanceReport')->name('company.candidateWeeklyAttendanceReport');

        Route::get('/company/candidate-monthly-report/{company_id}/{candidate_id}','EmployerReportController@candidateMonthlyAttendanceReport')->name('company.candidateMonthlyAttendanceReport');

        Route::get('/company/candidate-yearly-report/{company_id}/{candidate_id}','EmployerReportController@candidateYearlyAttendanceReport')->name('company.candidateYearlyAttendanceReport');

        Route::post('/company/candidate-notification-submit/{company_id}/{candidate_id}','EmployerCandidateNotificationController@notificationMessageSent')->name('company.notificationMessageSent');

        Route::post('/company/payment-submit/{company_id}/{candidate_id}','EmployerReportController@paymentSubmit')->name('company.paymentSubmit');

        Route::get('/company/daily-overall-report/{company_id}','EmployerOverallReportController@dailyOverAllReport')->name('company.dailyOverAllReport');

        Route::get('/company/weekly-overall-report/{company_id}','EmployerOverallReportController@weeklyOverAllReport')->name('company.weeklyOverAllReport');

        Route::get('/company/monthly-overall-report/{company_id}','EmployerOverallReportController@monthlyOverAllReport')->name('company.monthlyOverAllReport');

        Route::get('/company/yearly-overall-report/{company_id}','EmployerOverallReportController@yearlyOverAllReport')->name('company.yearlyOverAllReport');

        Route::get('/inbox/{company_id?}','EmployerCandidateLeaveController@all')->name('inbox');

        Route::get('/inbox-details/{leave_id}','EmployerCandidateLeaveController@leaveDetail')->name('leaveDetail');

        Route::get('/leave-approval/{leave_id}','EmployerCandidateLeaveController@leaveApproval')->name('leaveApproval');

    });


    Route::group([
        'middleware' => ['approverWebMiddleware'],
        'as' => 'approver.company.'
    ], function () {
        // Approver
        Route::get('approver/all-companies','ApproverEnrollController@allCompanies')->name('allCompanies');

        Route::get('approver/company/get-companies-data','ApproverEnrollController@getCompanyData')->name('getCompanyData');

        Route::get('candidate/all-enroll/{slug}','ApproverEnrollController@allEnroll')->name('candidate.allEnroll');

        Route::post('candidate/report/{candidate_id}/submit','ApproverEnrollController@candidateReportSubmit')->name('candidate.candidateReportSubmit');

        Route::get('candidate/report/{company_id}/{candidate_id}/{attendance_id}','ApproverEnrollController@candidateReport')->name('candidate.candidateReport');

        Route::get('candidate/missingAttenance/{company_slug}','ApproverEnrollController@missingAttenance')->name('candidate.missingAttenance');

        Route::post('candidate/missingAttenanceSubmit/','ApproverEnrollController@missingAttenanceSubmit')->name('candidate.missingAttenanceSubmit');

    });

    

    // Add Auth Middleware
    Route::group([
        'middleware' => ['employerWebMiddleware']
    ], function () {
        Route::get('dashboard', 'EmployerController@dashboard')->name('dashboard');

        Route::get('user-profile', 'EmployerController@userProfile')->name('userProfile');

        Route::post('user-profile-update', 'EmployerController@userProfileUpdate')->name('userProfileUpdate');

        Route::get('logout', 'EmployerAuthController@logout')->name('logout');

        Route::get('change-phone', 'EmployerAuthController@changePhone')->name('changePhone');

        Route::post('change-phone-submit', 'EmployerAuthController@changePhoneSubmit')->name('changePhoneSubmit');

    });



});
