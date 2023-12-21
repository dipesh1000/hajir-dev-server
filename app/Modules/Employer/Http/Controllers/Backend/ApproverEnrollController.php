<?php

namespace Employer\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MissingAttendanceStoreRequest;
use App\Http\Requests\ReportStoreRequest;
use App\Models\Report;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Candidate\Models\Attendance;
use Candidate\Models\Candidate;
use Candidate\Models\CompanyCandidate;
use Carbon\Carbon;
use Employer\Http\Resources\CompanyCandidateEnrollReportResource;
use Employer\Models\Company;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ApproverEnrollController extends Controller
{

    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function allCompanies(){
        try{
            return view('Employer::backend.approver.allCompanies');
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function getCompanyData(Request $request){
        try {
            
            $employer_id = Auth::guard('web')->id();
            if ($request->ajax()) {
                $data = Company::query()->where('employer_id',$employer_id)
                                ->with('employer','candidates')->active();
               
                    return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('employer',function($row){
                        return $row->employer ? $row->employer->name : 'No Employer';
                    })
                    ->editColumn('candidate_count',function($row){
                        return $row->candidates->count();
                    })
                    ->editColumn('status',function($row){
                        $main = '<span class="badge badge-success>'.$row->status.'</span>"';
                        return $main;
                    })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <a class="btn btn-primary btn-table" href="'. route('employer.approver.company.candidate.allEnroll',$row->slug) .'">Enroll Attendee</a>
                        <a class="btn btn-primary btn-table mt-2    " href="'. route('employer.approver.company.candidate.missingAttenance',$row->slug) .'">Missing Attendance</a>

                   ';
                    return $actionBtn;
                })
                ->rawColumns(['action','status'])
                ->make(true);
            }
        } catch (Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function allEnroll(Request $request,$slug){
        try{
            $company = Company::where('slug',$slug)->first();
            if($company){
                $companyCandidates = CompanyCandidate::where('company_id', $company->id)
                                ->active()->verified();

                if($request->status && $request->status == "Clock-Out"){
                    $activeStatus = "Clock-Out";
                    $companyCandidates = $companyCandidates
                                ->whereHas('companyEnrollTodayCandidateAttendacesCheckOut', function ($q) {
                                    $q->whereDate('created_at', Carbon::today());
                                })
                                ->with(['candidate','companyCandidateAttendaces'])
                                ->get();
                }else{
                    $activeStatus = "Clock-In";
                    $companyCandidates = $companyCandidates
                                ->whereHas('companyEnrollTodayCandidateAttendacesCheckIn', function ($q) {
                                    $q->whereDate('created_at', Carbon::today());
                                })
                                ->with(['candidate','companyCandidateAttendaces'])
                                ->get();
                }

                $absentCount = CompanyCandidate::where('company_id', $company->id)
                                ->active()->verified()
                                ->whereHas('candidate', function ($q) {
                                    $q->whereDoesntHave('todayattendances');
                                })->count();

                $presentCount = CompanyCandidate::where('company_id', $company->id)
                            ->active()->verified()
                            ->whereHas('candidate',function ($q) {
                                    $q->whereHas('attendances', function ($q) {
                                        $q->whereDate('created_at', today())
                                            ->where('employee_status', 'Present');
                                    });
                                }) ->count();

                $lateCount = CompanyCandidate::where('company_id', $company->id)
                            ->active()->verified()
                            ->whereHas('candidate',function ($q) {
                                    $q->whereHas('attendances', function ($q) {
                                        $q->whereDate('created_at', today())
                                            ->where('employee_status', 'Late');
                                    });
                                })->count();

                $totalattendee = CompanyCandidate::where('company_id', $company->id)
                                    ->verified()->count();

        
                return view('Employer::backend.approver.enrollAttendee',
                        compact('totalattendee','presentCount','lateCount','absentCount',
                                'company','companyCandidates','activeStatus'));
            }
            Toastr::error('Company Not Found.');
            return redirect()->back();
        }catch(Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }



    // public function clockIn($id)
    // {
    //     try {
    //         $companyCandidates = CompanyCandidate::where('company_id', $id)
    //                     ->active()->verified()
    //                     ->whereHas('companyEnrollCandidateAttendaces', function ($q) {
    //                         $q->whereDate('created_at', Carbon::today());
    //                     })
    //                     ->with(['candidate', 'companyEnrollCandidateAttendaces'])
    //                     ->get();

    //         if ($companyCandidates && $companyCandidates->count() > 0) {
    //             $companyCandidates =  CompanyCandidateEnrollReportResource::collection($companyCandidates);
    //         }

    //         $absentCount = CompanyCandidate::where('company_id', $id)
    //                         ->active()->verified()
    //                         ->whereHas('candidate', function ($q) {
    //                             $q->whereDoesntHave('todayattendances');
    //                         })->count();

    //         $presentCount = CompanyCandidate::where('company_id', $id)
    //                         ->active()->verified()
    //                         ->whereHas('candidate',function ($q) {
    //                                 $q->whereHas('attendances', function ($q) {
    //                                     $q->whereDate('created_at', today())
    //                                         ->where('employee_status', 'Present');
    //                                 });
    //                             })
    //                         ->count();

    //         $lateCount = CompanyCandidate::where('company_id', $id)
    //                         ->active()->verified()
    //                         ->whereHas('candidate',function ($q) {
    //                                 $q->whereHas('attendances', function ($q) {
    //                                     $q->whereDate('created_at', today())
    //                                         ->where('employee_status', 'Late');
    //                                 });
    //                             }
    //                         )->count();

    //         $totalattendee = CompanyCandidate::where('company_id', $id)
    //                         ->verified()->count();
    //         $data = [
    //             'total_attendee' =>  $totalattendee ?? 0,
    //             'present' => $presentCount ?? 0,
    //             'late' =>  $lateCount  ?? 0,
    //             'absent' => $absentCount ?? 0,
    //             'candidates' => $companyCandidates ?? [],
    //             // 'candidates' => CandidateResource::collection($companyCandidates)
    //         ];
            
    //         return view('Employer::backend.approver.enrollAttendee');
    //     } catch (\Exception $e) {
    //          Toastr::error($e->getMessage());
    //         return redirect()->back();
    //     }
    // }

    // public function clockOut($id)
    // {
    //     try {
    //         $companyCandidates = CompanyCandidate::where('company_id', $id)
    //                         ->active()->verified()
    //                         ->whereHas('companyEnrollCandidateAttendaces', function ($q) {
    //                             $q->whereDate('created_at', Carbon::today());
    //                         })
    //                         ->with(['candidate', 'companyEnrollCandidateAttendaces'])
    //                         ->get();

    //         if ($companyCandidates && $companyCandidates->count() > 0) {
    //             $companyCandidates =  CompanyCandidateEnrollReportResource::collection($companyCandidates);
    //         }

    //         $absentCount = CompanyCandidate::where('company_id', $id)
    //                         ->active()->verified()
    //                         ->whereHas('candidate',function ($q) {
    //                                     $q->whereDoesntHave('todayattendances');
    //                                 }
    //                             )->count();

    //         $presentCount = CompanyCandidate::where('company_id', $id)
    //                         ->active()->verified()
    //                         ->whereHas('candidate',function ($q) {
    //                                 $q->whereHas('attendances', function ($q) {
    //                                     $q->whereDate('created_at', today())
    //                                         ->where('employee_status', 'Present');
    //                                 });
    //                             }
    //                         )->count();

    //         $lateCount = CompanyCandidate::where('company_id', $id)
    //                     ->active()->verified()  
    //                     ->whereHas('candidate', function ($q) {
    //                             $q->whereHas('attendances', function ($q) {
    //                                 $q->whereDate('created_at', today())
    //                                     ->where('employee_status', 'Late');
    //                             });
    //                         }
    //                     )->count();

    //         $totalattendee = CompanyCandidate::where('company_id', $id)
    //                             ->verified() 
    //                             ->count();
    //         $data = [
    //             'total_attendee' =>  $totalattendee ?? 0,
    //             'present' => $presentCount ?? 0,
    //             'late' =>  $lateCount  ?? 0,
    //             'absent' => $absentCount ?? 0,
    //             'candidates' => $companyCandidates ?? [],
    //             // 'candidates' => CandidateResource::collection($companyCandidates)
    //         ];
    //         return view('Employer::backend.approver.enrollAttendee');

    //     } catch (\Exception $e) {
    //          Toastr::error($e->getMessage());
    //         return redirect()->back();
    //     }
    // }

    public function candidateReport($companyid, $candidateid, $attendanceid){
        try{
            $candidate = User::where('id',$candidateid)->first();
            if($candidate){
                return view('Employer::backend.approver.reportModal',compact('companyid','candidate','attendanceid'));
            }
            return $this->response->responseError('Company Not Found.');
        }catch(Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }



    public function candidateReportSubmit(ReportStoreRequest $request, $candidateid)
    {
        try {
            $report = new Report();
            $report->company_id = $request->companyid;
            $report->candidate_id = $candidateid;
            $report->attendance_id = $request->attendanceid;
            $report->remarks = $request->remarks;
            if ($report->save()) {
                return $this->response->responseSuccessMsg("Successfully Reported");
            }
            return $this->response->responseError("Something went wrong please try again later");
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function missingAttenance($company_slug)
    {
        try {
            $company = Company::where('slug',$company_slug)
                            ->with('candidates')->first();
            if($company){
                return view('Employer::backend.approver.missingAttendace',compact('company'));
            }
            Toastr::error('Company Not Found.');
            return redirect()->back();
        } catch (\Exception $e) {
             Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function missingAttenanceSubmit(MissingAttendanceStoreRequest $request)
    {
        try {
            // dd($request->all());
            $attendance = Attendance::where('company_id', $request->company_id)
                            ->where('candidate_id', $request->candidate_id)
                            ->whereDate('created_at', $request->attendance_date)
                            ->first();
            if (!$attendance) {
                $companyCandidate = CompanyCandidate::where('company_id', $request->company_id)
                                    ->where('candidate_id', $request->candidate_id)
                                    ->verified()->active()
                                    ->first();
                if ($companyCandidate) {
                    $earning = $this->calcaulateearning($request->start_time, $request->end_time, $companyCandidate);
                    $overtime = $this->overtime($companyCandidate, $request->companyid, $earning, $request->start_time, $request->end_time);
                    $attendance = new Attendance();
                    $attendance->candidate_id = $request->candidate_id;
                    $attendance->company_id = $request->company_id;
                    $attendance->start_time = Carbon::parse($request->start_time);
                    $attendance->employee_status = $request->employee_status;
                    $attendance->end_time = Carbon::parse($request->end_time);
                    $attendance->earning = $earning;
                    $attendance->overtime_earning = $overtime;
                    $attendance->created_at = $request->attendance_date;

                    if ($attendance->save()) {
                        Toastr::success('Successfully Saved');
                        return redirect()->back();
                    }
                    Toastr::error('Something Went Wrong While Saving.');
                    return redirect()->back();
                }
                Toastr::error('Employer Not Registered To This Company.');
                return redirect()->back();
            }
            Toastr::error('Attendance Already Exists.');
            return redirect()->back();
            return $this->response->responseError("Attendance already exists for the choosen candidate",400);
        } catch (\Exception $e) {
             Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    private function calcaulateearning($start_time, $end_time, $companycandidate)
    {
        $finishTime = Carbon::parse($end_time);
        $workingtime =  $finishTime->diffInSeconds($start_time);

        $workinghour = Carbon::parse($companycandidate->office_hour_start)
            ->diffInHours($companycandidate->office_hour_end);


        if ($companycandidate->salary_type == 'monthly') {
            $monthlysalary = $companycandidate->salary_amount;
            $daysInMonth = Carbon::now()->daysInMonth;
            $monthlySalaryInSec = ((float)$monthlysalary / ($daysInMonth * (int) $workinghour * 60 * 60));
            $monthlySalaryInHour = ((float)$monthlysalary / ($daysInMonth *  (int) $workinghour));


            // $workingtime =  $finishTime->diff($startTime)->format('%H:%I:%S');
            // dd($workingtime);

            // $scheduleTime =Carbon::createFromTimestampUTC($workingtime)->diffInSeconds();


            $totalHourWorked = Carbon::parse($workingtime)->format('H');
            $totalMinWorked = Carbon::parse($workingtime)->format('I');
            $totalSecWorked = Carbon::parse($workingtime)->format('s');
            $totalIncomeInDay =  $workingtime * $monthlySalaryInSec;

            return  $monthlySalaryInHour;
        }


        if ($companycandidate->salary_type == "weekly") {
            $weeklySalary = $companycandidate->salary_amount;

            $weeklySalaryInSec =  ((float)$weeklySalary / (7 * (int) $workinghour * 60 * 60));
            $totalIncomeInDay =  $workingtime * $weeklySalaryInSec;
            $monthlySalaryInHour = ((float)$weeklySalary / (7 *  (int) $workinghour));


            return  $monthlySalaryInHour;
        }

        if ($companycandidate->salary_type == "daily") {
            $dailysalaryInSec = $companycandidate->salary_amount / ((int) $workinghour * 60 * 60);
            $dailysalaryInHour = $companycandidate->salary_amount / ((int) $workinghour);
            $totalIncomeInDay = $workingtime * $dailysalaryInSec;

            return $dailysalaryInHour;
        }
    }




    private function overtime($companycandidate = null, $companyid = null, $monthlySalaryInHour = null, $start_time = null, $end_time = null)
    {
        //companycandidate total working hours in seconds


        //  dd($companycandidate, $companyid, $monthlySalaryInHour);
        $workingHoursInSec = Carbon::parse($companycandidate->office_hour_start)
            ->diffInSeconds(Carbon::parse($companycandidate->office_hour_end));

        $workingHoursInHour = Carbon::parse($companycandidate->office_hour_start)
            ->diffInHours(Carbon::parse($companycandidate->office_hour_end));


        if ($end_time != null) {
            $today_workinghours_insec = Carbon::parse($start_time)
                ->diffInSeconds(Carbon::parse($end_time));
            $today_workinghours_inhour = Carbon::parse($start_time)
                ->diffInHours(Carbon::parse($end_time));
            // dd($today_workinghours_insec);
            if ($today_workinghours_insec > $workingHoursInSec) {
                $difference_workinghours_insec = $today_workinghours_insec - $workingHoursInSec;

                $difference_workinghours_inhour = $today_workinghours_inhour - $workingHoursInHour;
                // dd($difference_workinghours_inhour);
                // dd(CarbonInterval::seconds($difference_workinghours_insec)->cascade());

                if (isset($companycandidate->overtime)) {
                    $overtimerate = $companycandidate->overtime;
                } else {
                    $overtimerate = 0;
                }
                return $overtimerate;
            }
        }
    }
}
