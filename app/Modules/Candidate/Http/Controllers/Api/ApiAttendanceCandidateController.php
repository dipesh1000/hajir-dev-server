<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Candidate\Models\Attendance;
use App\Http\Controllers\Controller;
use Candidate\Http\Resources\TodayDetailsResource;
use Candidate\Models\AttendanceBreak;
use Candidate\Models\CompanyCandidate;
use Carbon\Carbon;
use Employer\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAttendanceCandidateController extends Controller
{
    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }
    public function attendace()
    {
    }

    public function attendanceBreakStore($id, $breakid = null)
    {
        try {
            $user = auth()->user();
            $todayDate = Carbon::now()->format('Y-m-d');
            $attendance = Attendance::where('id', $id)
                ->where('end_time', null)
                ->where('candidate_id', $user->id)
                ->where('created_at', $todayDate)
                ->first();
            if ($attendance) {
                $attendancebreak = new AttendanceBreak();
                $attendancebreak->attendance_id = $attendance->id;
                $attendancebreak->candidate_id = auth()->user()->id;
                $attendancebreak->start_time = Carbon::now();
                if ($attendancebreak->save()) {
                    $data = [
                        'attendance_id ' => $attendance->id,
                        'break_id' => $attendancebreak->id,
                    ];
                    return $this->response->responseSuccess($data, 'Successfully Saved', 200);
                }
                return $this->response->responseError('Something Went Wrong. Please Try Again', 400);
            }
            return $this->response->responseError('Attendance Not Found',404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function attendanceBreakUpdate($breakid)
    {
        try {
            $user = auth()->user();
            $attendancebreak = AttendanceBreak::where('id', $breakid)
                ->where('end_time', null)
                ->where('candidate_id', $user->id)
                ->first();

            if ($attendancebreak) {
                if ($attendancebreak->end_time != null) {
                    return $this->response->responseError('End Time Already Exists.');
                }
                $attendancebreak->end_time = Carbon::now();
                if ($attendancebreak->update()) {
                    return $this->response->responseSuccessMsg('Successfully Updated.', 200);
                }
                return $this->response->responseError('Something Went Wrong. Please Try Again.', 400);
            }
            return $this->response->responseError('Attendance Not Found.',404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function attendanceStore($companyid, Request $request)
    {
        try {
            $user = auth()->user();
            
            $todayDayNumber = Carbon::now()->addDay()->dayOfWeekIso;
            $todayDate = Carbon::now()->format('Y-m-d');

            $company = Company::where('id', $companyid)
                ->with([
                    'companyCandidates',
                    'companyBusinessLeave' => function ($q) use ($todayDayNumber) {
                        $q->where('business_leave_id', $todayDayNumber ?? 1);
                    },
                    'govLeaves' => function ($q) use ($todayDate) {
                        $q->where('leave_date', $todayDate ?? '2023-03-08');
                    },
                    'specialLeaves' => function ($q) use ($todayDate) {
                        $q->where('leave_date', $todayDate ?? '2023-03-08');
                    },
                    'companyCandidateLeaves' => function ($q) use ($todayDate, $user) {
                        $q->where('start_date', '<=', $todayDate)
                            ->where('end_date', '>=', $todayDate)
                            ->where('candidate_id', $user->id)
                            ->where('type', 'Full')
                            ->where('status', 'Approved');
                    },
                ])
                ->whereHas('companyCandidates', function ($q) use ($user) {
                    $q->where('candidate_id', $user->id)
                        ->where('verified_status', 'verified')
                        ->where('status', 'Active');
                })
                ->first();

            if ($company) {
                if (count($company->specialLeaves) > 0) {
                    return $this->response->responseError("Today is Special Holiday Can't Login.");
                } elseif (count($company->govLeaves) > 0) {
                    return $this->response->responseError("Today is Government Holiday Can't Login.");
                } elseif (count($company->companyBusinessLeave) > 0) {
                    return $this->response->responseError("Today is business Holiday Can't Login.");
                } elseif (count($company->companyCandidateLeaves) > 0) {
                    $leave = $company->companyCandidateLeaves->first();
                    return $this->response->responseError('Your are in Full Day ' . $leave->LeaveType->title . ' Leave from ' . Carbon::parse($leave->start_date)->format('Y-m-d') . ' to ' . Carbon::parse($leave->end_date)->format('Y-m-d'));
                }

                if (Carbon::parse($company->office_hour_start) > Carbon::now()) {
                    $employee_status = 'Present';
                } else {
                    $employee_status = 'Late';
                }

                $attendance = Attendance::updateOrCreate(
                    [
                        'candidate_id' => auth()->user()->id,
                        'company_id' => $companyid,
                        'created_at' => $todayDate,
                    ],
                    [
                        'employee_status' => $employee_status,
                        'start_time' => Carbon::now(),
                    ],
                );

                if ($attendance) {
                    $data = [
                        'attendance_id' => $attendance->id,
                    ];
                    return $this->response->responseSuccess($data, 'Successfully Saved.', 200);
                }
            }
        return $this->response->responseError('Company Not Found.',400);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function attendanceUpdate($companyid = null, $attendanceid = null, Request $request)
    {
        try {
            if ($attendanceid != null) {
                if ($companyid != null) {
                    $user = auth()->user();
                    $companycandidate = CompanyCandidate::where('company_id', $companyid)
                        ->where('candidate_id', $user->id)
                        ->where('verified_status', 'verified')
                        ->where('status', 'Active')
                        ->first();

                    if ($companycandidate) {
                        $attendance = Attendance::where('id', $attendanceid)
                            ->where('company_id', $companyid)
                            ->where('candidate_id', auth()->user()->id)
                            ->where('end_time', null)->where('start_time', '!=', null)
                            ->first();

                        if ($attendance) {
                            $earnings = $this->overtime($companycandidate, $attendance);
                            $attendance->end_time = Carbon::now();
                            $attendance->earning = $earnings['earning'] ?? 0;
                            $attendance->overtime_earning = $earnings['overtime_earning'] ?? 0;

                            if ($attendance->update()) {
                                return $this->response->responseSuccessMsg('Successfully Updated',200);
                            }
                        }
                    }
                }
            }
            return $this->response->responseError('Something went wrong',400);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function getCandidateTodayDetails($company_id)
    {
        try {
            $user = auth()->user();
            // dd($user);
            $todayDate = Carbon::today();
            $attendance = Attendance::where('created_at', $todayDate)
                        ->where('candidate_id', $user->id)
                        ->where('company_id', $company_id)
                        ->with('breaks','currentBreak')
                        ->first();

            $companycandidate = CompanyCandidate::where('company_id', $company_id)
                        ->where('candidate_id', $user->id)
                        ->where('verified_status', 'verified')
                        ->where('status', 'Active')
                        ->first();

            if ($companycandidate) {
                $totalEarning = Attendance::where('candidate_id', $user->id)
                            ->where('company_id', $companycandidate->company_id)
                            ->where('employee_status', '!=', 'Absent')
                            ->where('end_time', '!=', null)->where('start_time', '!=', null)
                            ->sum('earning');

                $currentMonthDays = 30;
                $currentMonthWeeks = 4;
                $dailySalary = 0;
                if ($companycandidate->salary_amount != null && $companycandidate->salary_amount > $currentMonthDays) {
                    if ($companycandidate->salary_type == 'monthly') {
                        $dailySalary = $companycandidate->salary_amount / $currentMonthDays;
                    } elseif ($companycandidate->salary_type == 'weekly') {
                        $dailySalary = $companycandidate->salary_amount / $currentMonthWeeks;
                    } elseif ($companycandidate->salary_type == 'daily') {
                        $dailySalary = $companycandidate->salary_amount;
                    }
                }

               
                if($attendance && $attendance->end_time == null){
                    $officeAssignMinutes = Carbon::parse($companycandidate->office_hour_end)
                                    ->diffInMinutes(Carbon::parse($companycandidate->office_hour_start));
                    if($officeAssignMinutes<=0){
                        $minuteSalary = 0;
                    }else{
                        $minuteSalary =  $dailySalary / $officeAssignMinutes;
                    }

                   
                    $companycandidate->salary_in_minute = round($minuteSalary, 2);
                    $companycandidate->total_earning = round($totalEarning, 2);
                }
              
                $companycandidate->attendace = $attendance;
                
                $deatils = new TodayDetailsResource($companycandidate);
                $data = $deatils ?? [];

                return $this->response->responseSuccess($data, 'Successfully Fetched.', 200);
            }
            return $this->response->responseError('Employer Not Verified.',400);
        } catch (Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }



    private function overtime($companycandidate, $attendance)
    {
        $dailySalary = 0;
        $earning = 0;
        $overtimeEarning = 0;
        $currentMonthDays = 30;
        $currentMonthWeeks = 4;
        $allowLateAttendence = 0;
        $totalOverTimeInMinute = 0;
        $totalDecuctSlarayByMinute = 0;

        if ($companycandidate->salary_amount != null && $companycandidate->salary_amount > $currentMonthDays) {
            $start_time = $attendance->start_time;
            $end_time = Carbon::now();
            $officeAssignMinutes = Carbon::parse($companycandidate->office_hour_end)->diffInMinutes(Carbon::parse($companycandidate->office_hour_start));
            $officeAttendInMinutes = $end_time->diffInMinutes($start_time);

            $totalLateinMinute = $officeAssignMinutes  - $officeAttendInMinutes;

            if ($totalLateinMinute < 0) {
                $totalOverTimeInMinute = abs($totalLateinMinute);
            }

            if ($companycandidate->salary_type == 'monthly') {
                $dailySalary = $companycandidate->salary_amount / $currentMonthDays;

            } elseif ($companycandidate->salary_type == 'weekly') {
                $dailySalary = $companycandidate->salary_amount / $currentMonthWeeks;
            } elseif ($companycandidate->salary_type == 'daily') {
                $dailySalary = $companycandidate->salary_amount;
            }
        }

        $minuteSalary =  $dailySalary / $officeAssignMinutes;

        if ($totalLateinMinute > 0) {
            $totalDecuctSlarayByMinute = $minuteSalary * $totalLateinMinute;
        }

        if ($companycandidate->overtime != null && $companycandidate->overtime > 0) {
            if ($totalOverTimeInMinute > 0) {
                $overtimeEarning = ($totalOverTimeInMinute * $minuteSalary) * $companycandidate->overtime;
            }
        }

        $earning =  ($dailySalary + $overtimeEarning) - $totalDecuctSlarayByMinute;

        return [
            'earning' => round($earning, 2),
            'overtime_earning' => round($overtimeEarning, 2),
        ];
    }

    public function currentDayAttendanceDelete($company_id){
        try{
            $user_id = Auth::user()->id;
            $data = Attendance::where('candidate_id',$user_id)
                    ->where('company_id',$company_id)->where('created_at',today())->get();
            foreach($data as $item){
                $item->delete();
            }
                
            
                return $this->response->responseSuccessMsg("Successfully Deleted", 200);
          
        }catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }

    }
}
