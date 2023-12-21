<?php

namespace Employer\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Candidate\Models\Attendance;
use Candidate\Models\CompanyCandidate;
use Carbon\Carbon;

class EmployerOverallReportController extends Controller
{

    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function dailyOverallReport($company_id)
    {
        // try {
            $companyCandidate = CompanyCandidate::where('company_id', $company_id)->first();
            if ($companyCandidate) {
                $totalattendee = Attendance::where('company_id', $company_id)
                        ->whereDate('created_at', today())
                        ->count();
                $absentCount = Attendance::where('company_id', $company_id)
                            ->whereDate('created_at', today())
                            ->where('employee_status', 'Absent')
                            ->count();

                $presentCount = Attendance::where('company_id', $company_id)
                            ->whereDate('created_at', today())
                            ->where(function ($query) {
                                $query->where('employee_status', 'Present')
                                    ->orWhere('employee_status', 'Late');
                            })
                            ->count();

                $lateCount = Attendance::where('company_id', $company_id)
                            ->whereDate('created_at', today())
                            ->where('employee_status', 'Late')
                            ->count();

                $leaveCount = Attendance::where('company_id', $company_id)
                            ->whereDate('created_at', today())
                            ->where('employee_status', 'Leave')
                            ->count();

                $punchOutCount = Attendance::where('company_id', $company_id)
                            ->whereDate('created_at', today())
                            ->where('employee_status', 'Punch-Out')
                            ->count();
                $totalPresentToday = $presentCount ?? 0;
                $presentPercentage = 0;
                if( $totalPresentToday != 0){
                    $presentPercentage = number_format(($totalPresentToday * 100) / $totalattendee, 2, '.', '');
                }
               
              
                return view(
                    'Employer::backend.overAllReports.candidateDailyOverallReport',
                    compact(
                        'totalattendee',
                        'presentCount',
                        'absentCount',
                        'lateCount',
                        'punchOutCount',
                        'leaveCount',
                        'presentPercentage',
                        'companyCandidate'
                    )
                );
            }
            Toastr::error("Company Not Found.");
            return redirect()->back();
        // } catch (\Exception $e) {

        //     Toastr::error($e->getMessage());
        //     return redirect()->back();
        // }
    }

    public function weeklyOverallReport($id)
    {
        try {
            $companyCandidate = CompanyCandidate::where('company_id', $id)->first();
            if ($companyCandidate) {

                $totalattendee = Attendance::where('company_id', $id)
                                ->whereBetween('created_at',
                                    [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                                )->count();

                $absentCount = Attendance::where('company_id', $id)
                            ->whereBetween( 'created_at',
                                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                            )->where('employee_status', 'Absent')
                            ->count();

                $presentCount = Attendance::where('company_id', $id)
                            ->whereBetween('created_at',
                                    [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                            )
                            ->where(function ($query) {
                                $query->where('employee_status', 'Present')
                                    ->orWhere('employee_status', 'Late');
                            })->count();

                $lateCount = Attendance::where('company_id', $id)
                                ->whereBetween('created_at',
                                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                            )
                            ->where('employee_status', 'Late')
                            ->count();

                $leaveCount = Attendance::where('company_id', $id)
                           ->whereBetween('created_at',
                                    [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                                )
                            ->where('employee_status', 'Leave')
                            ->count();

                $punchOutCount = Attendance::where('company_id', $id)
                               ->whereBetween('created_at',
                                    [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                                )
                                ->where('employee_status', 'Punch-Out')
                                ->count();

                $totalPresentToday = $presentCount ?? 0;
                $presentPercentage = number_format(($totalPresentToday * 100) / $totalattendee, 2, '.', '');

                return view( 'Employer::backend.overAllReports.candidateWeeklyOverallReport',
                            compact('totalattendee','presentCount','absentCount','lateCount',
                                'punchOutCount','leaveCount','presentPercentage','companyCandidate'));
            }
            Toastr::error("Company Not Found.");
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function monthlyOverallReport($id)
    {
        try {
            $companyCandidate = CompanyCandidate::where('company_id', $id)->first();
            if ($companyCandidate) {
                $totalattendee = Attendance::where('company_id', $id)
                                    ->whereMonth('created_at', date('m'))
                                    ->count();
                $absentCount = Attendance::where('company_id', $id)
                    ->whereMonth('created_at', date('m'))
                    ->where('employee_status', 'Absent')
                    ->count();

                $presentCount = Attendance::where('company_id', $id)
                    ->whereMonth('created_at', date('m'))
                    ->where(function ($query) {
                        $query->where('employee_status', 'Present')
                            ->orWhere('employee_status', 'Late');
                    })
                    ->count();

                $lateCount = Attendance::where('company_id', $id)
                    ->whereMonth('created_at', date('m'))
                    ->where('employee_status', 'Late')
                    ->count();

                $leaveCount = Attendance::where('company_id', $id)
                    ->whereMonth('created_at', date('m'))
                    ->where('employee_status', 'Leave')
                    ->count();

                $punchOutCount = Attendance::where('company_id', $id)
                    ->whereMonth('created_at', date('m'))
                    ->where('employee_status', 'Punch-Out')
                    ->count();
                $totalPresentToday = $presentCount ?? 0;
                $presentPercentage = number_format(($totalPresentToday * 100) / $totalattendee, 2, '.', '');


                return view(
                    'Employer::backend.overAllReports.candidateMonthlyOverallReport',
                    compact(
                        'totalattendee',
                        'presentCount',
                        'absentCount',
                        'lateCount',
                        'punchOutCount',
                        'leaveCount',
                        'presentPercentage',
                        'companyCandidate'
                    )
                );
            }
            Toastr::error("Company Not Found.");
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function yearlyOverallReport($id)
    {
        try {
            $companyCandidate = CompanyCandidate::where('company_id', $id)->first();
            if ($companyCandidate) {
                $totalattendee = Attendance::where('company_id', $id)
                            ->whereYear('created_at', date('Y'))->count();
                $absentCount = Attendance::where('company_id', $id)
                    ->whereYear('created_at', date('Y'))
                    ->where('employee_status', 'Absent')
                    ->count();

                $presentCount = Attendance::where('company_id', $id)
                    ->whereYear('created_at', date('Y'))
                    ->where(function ($query) {
                        $query->where('employee_status', 'Present')
                            ->orWhere('employee_status', 'Late');
                    })
                    ->count();

                $lateCount = Attendance::where('company_id', $id)
                    ->whereYear('created_at', date('Y'))
                    ->where('employee_status', 'Late')
                    ->count();

                $leaveCount = Attendance::where('company_id', $id)
                    ->whereYear('created_at', date('Y'))
                    ->where('employee_status', 'Leave')
                    ->count();

                $punchOutCount = Attendance::where('company_id', $id)
                    ->whereYear('created_at', date('Y'))
                    ->where('employee_status', 'Punch-Out')
                    ->count();
                $totalPresentToday = $presentCount ?? 0;
                $presentPercentage = number_format(($totalPresentToday * 100) / $totalattendee, 2, '.', '');

                return view(
                    'Employer::backend.overAllReports.candidateYearlyOverallReport',
                    compact(
                        'totalattendee',
                        'presentCount',
                        'absentCount',
                        'lateCount',
                        'punchOutCount',
                        'leaveCount',
                        'presentPercentage',
                        'companyCandidate'
                    )
                );
            }
            Toastr::error("Company Not Found.");
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }
}
