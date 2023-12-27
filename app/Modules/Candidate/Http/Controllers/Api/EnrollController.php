<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportStoreRequest;
use App\Models\Report;
use Candidate\Http\Resources\CandidateResource;
use Candidate\Models\Attendance;
use Candidate\Models\CompanyCandidate;
use Carbon\Carbon;
use Employer\Http\Resources\CompanyCandidateEnrollReportResource;
use Illuminate\Support\Facades\Auth;

class EnrollController extends Controller
{

    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function clockIn($id)
    {
        try {
            $companyCandidates = CompanyCandidate::where('company_id', $id)
                ->active()->verified()
                // ->whereHas('companyEnrollCandidateAttendaces', function ($q) {
                //     $q->whereDate('created_at', Carbon::today());
                // })
                ->with(['candidate', 'companyEnrollCandidateAttendaces'])
                ->get();


            if ($companyCandidates && $companyCandidates->count() > 0) {
                $companyCandidates =  CompanyCandidateEnrollReportResource::collection($companyCandidates);
            }

            $absentCount = CompanyCandidate::where('company_id', $id)
                            ->active()->verified()
                            ->whereHas('candidate', function ($q) {
                                $q->whereDoesntHave('todayattendances');
                            })->count();

            $presentCount = CompanyCandidate::where('company_id', $id)
                ->active()->verified()
                ->whereHas('candidate', function ($q) {
                    $q->whereHas('attendances', function ($q) {
                        $q->whereDate('created_at', today())
                            ->where('employee_status', 'Present');
                    });
                })
                ->count();

            $lateCount = CompanyCandidate::where('company_id', $id)
                ->active()->verified()
                ->whereHas(
                    'candidate',
                    function ($q) {
                        $q->whereHas('attendances', function ($q) {
                            $q->whereDate('created_at', today())
                                ->where('employee_status', 'Late');
                        });
                    }
                )->count();

            $totalattendee = CompanyCandidate::where('company_id', $id)
                ->verified()->count();
            $data = [
                'total_attendee' =>  $totalattendee ?? 0,
                'present' => $presentCount ?? 0,
                'late' =>  $lateCount  ?? 0,
                'absent' => $absentCount ?? 0,
                'pending' => 0,
                'clockin_candidates' => $companyCandidates ?? [],
                'clockout_candidates' => $companyCandidates ?? [],
            ];
            return $this->response->responseSuccess($data, "Success", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }




    public function clockOut($id)
    {
        try {
            $companyCandidates = CompanyCandidate::where('company_id', $id)
                ->active()->verified()
                ->whereHas('companyEnrollCandidateAttendaces', function ($q) {
                    $q->whereDate('created_at', Carbon::today());
                })
                ->with(['candidate', 'companyEnrollCandidateAttendaces'])
                ->get();

            if ($companyCandidates && $companyCandidates->count() > 0) {
                $companyCandidates =  CompanyCandidateEnrollReportResource::collection($companyCandidates);
            }

            $absentCount = CompanyCandidate::where('company_id', $id)
                            ->active()->verified()
                            ->whereHas(
                                'candidate',
                                function ($q) {
                                    $q->whereDoesntHave('todayattendances');
                                }
                            )->count();

            $presentCount = CompanyCandidate::where('company_id', $id)
                ->active()->verified()
                ->whereHas(
                    'candidate',
                    function ($q) {
                        $q->whereHas('attendances', function ($q) {
                            $q->whereDate('created_at', today())
                                ->where('employee_status', 'Present');
                        });
                    }
                )->count();

            $lateCount = CompanyCandidate::where('company_id', $id)
                ->active()->verified()
                ->whereHas(
                    'candidate',
                    function ($q) {
                        $q->whereHas('attendances', function ($q) {
                            $q->whereDate('created_at', today())
                                ->where('employee_status', 'Late');
                        });
                    }
                )->count();

            $totalattendee = CompanyCandidate::where('company_id', $id)
                ->verified()
                ->count();
            $data = [
                'total_attendee' =>  $totalattendee ?? 0,
                'present' => $presentCount ?? 0,
                'late' =>  $lateCount  ?? 0,
                'absent' => $absentCount ?? 0,
                'candidates' => $companyCandidates ?? [],
                // 'candidates' => CandidateResource::collection($companyCandidates)
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function report(ReportStoreRequest $request, $companyid, $candidateid, $attendanceid)
    {
        try {
            $report = new Report();
            $report->company_id = $companyid;
            $report->candidate_id = $candidateid;
            $report->attendance_id = $attendanceid;
            $report->remarks = $request->remarks;
            if ($report->save()) {
                return $this->response->responseSuccessMsg("Successfully Saved");
            }
            return $this->response->responseError("Something went wrong please try again later");
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function missingAttenanceSubmit(Request $request)
    {
        try {
            $attendance = Attendance::where('company_id', $request->companyid)
                ->where('candidate_id', $request->candidateid)
                ->whereDate('created_at', $request->attendance_date)
                ->first();


            if (!$attendance ) {
                $companyCandidate = CompanyCandidate::where('company_id', $request->companyid)
                    ->where('candidate_id', $request->candidateid)
                    ->verified()->active()
                    ->first();

                if ($companyCandidate) {

                    $earning = $this->calcaulateearning($request->start_time, $request->end_time, $companyCandidate);
                    $overtime = $this->overtime($companyCandidate, $request->companyid, $earning, $request->start_time, $request->end_time);
                    $attendance = new Attendance();
                    $attendance->candidate_id = $request->candidateid;
                    $attendance->company_id = $request->companyid;
                    $attendance->start_time = Carbon::parse($request->start_time);
                    $attendance->employee_status = $request->type;
                    $attendance->end_time = Carbon::parse($request->end_time);
                    $attendance->earning = $earning;
                    $attendance->overtime_earning = $overtime;
                    if ($attendance->save()) {
                        return $this->response->responseSuccessMsg("Successfully Saved", 200);
                    }
                    return $this->response->responseError("Company Candidate Not Found.", 404);
                }
            }
            return $this->response->responseError("Attendance already exists for the choosen candidate", 400);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
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

    public function approveAttendance($attendance_id)
    {
        try {
            $attendance = Attendance::where('id', $attendance_id)
                ->where('start_time', !null)
                ->where('end_time', !null)
                ->where('approve_status', 0)
                ->first();
            if ($attendance) {
                $attendance->approver_id =  Auth::id();
                $attendance->approve_status = 1;
                if ($attendance->update()) {
                    return $this->response->responseSuccessMsg('Successfully Approved.');
                }
                return $this->response->responseSuccessMsg('Something Went Wrong. Please Try Again.');
            }
            return $this->response->responseError("Attendance Not Found.", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function getCandidates($companyid)
    {
        try {
           $companyCandidates = CompanyCandidate::where('company_id', $companyid)->get();
           $data = [];
           if($companyCandidates){
               $data = CandidateResource::collection($companyCandidates);
               return $this->response->responseSuccess($data, "Successfully Fetched", 200);
           }
           return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
