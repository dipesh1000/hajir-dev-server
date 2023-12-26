<?php

namespace Employer\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\YearlyEarningResource;
use App\Models\CompanyGovernmentleave;
use App\Models\CompanySpecialleave;
use Brian2694\Toastr\Facades\Toastr;
use Candidate\Models\Attendance;
use Candidate\Models\CompanyBusinessleave;
use Candidate\Models\CompanyCandidate;
use Candidate\Models\Leave;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Employer\Http\Requests\PaymentStoreRequest;
use Employer\Models\Company;
use Employer\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployerReportController extends Controller
{
    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    // Company Today Attendance Reports
    public function currentDayAttenanceReport($company_slug)
    {
        try {
            $company = Company::where('slug', $company_slug)->first();
            if ($company) {
                $companyCandidates = CompanyCandidate::where('company_id', $company->id)
                            ->verified()->active()
                            ->with([
                                'candidate', 'companyCandidateAttendaces',
                                'activecompanyCandidateAttendaces', 'company'
                            ])->get();

                $absentCount = CompanyCandidate::where('company_id', $company->id)
                            ->active()->verified()
                            ->whereHas('candidate',function ($q) {
                                    $q->whereDoesntHave('todayattendances');
                                }
                            )->count();

                $presentCount = CompanyCandidate::where('company_id', $company->id)
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

                $lateCount = CompanyCandidate::where('company_id', $company->id)
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

                $totalattendee = CompanyCandidate::where('company_id', $company->id)
                        ->verified()->active()->count();
                

                return view(
                    'Employer::backend.reports.companyDailyAttendance',
                    compact('totalattendee', 'presentCount', 'lateCount', 'absentCount', 'companyCandidates', 'company')
                );
            }
            Toastr::error('Company Not Found.');
            return redirect()->back();
        } catch (Exception  $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    function weeksInMonth($numOfDaysInMonth)
    {
        $daysInWeek = 7;
        $result = $numOfDaysInMonth / $daysInWeek;
        $numberOfFullWeeks = floor($result);
        return $numberOfFullWeeks;
    }




    public function candidateDailyAttendanceReport($companyid, $candidateid, Request $request)
    {
        try {
            $now = Carbon::now();
            $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
            $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            $candidate = CompanyCandidate::where('candidate_id', $candidateid)
                                ->active()->verified()->first();
            if ($candidate) {
                $attendances = Attendance::where('candidate_id', $candidateid)->where('company_id', $companyid)
                    ->whereBetween('created_at', [$weekStart, $now])->get();
                $companyBusinessLeave = CompanyBusinessleave::where('company_id', $candidate->company_id)->get()
                    ->pluck('business_leave_id')->toArray();
                $companySpecialLeave = CompanySpecialleave::where('company_id', $candidate->company_id)
                    ->whereBetween('leave_date', [$weekStart, $weekEnd])->get()->pluck('leave_date')->toArray();
                $companyGovermentLeave = CompanyGovernmentleave::where('company_id', $candidate->company_id)
                    ->whereBetween('leave_date', [$weekStart, $weekEnd])->get()->pluck('leave_date')->toArray();
                $reportData = [];
                for ($i = 0; $i < count($attendances); $i++) {
                    $day = $weekStart->copy()->addDays($i);
                    $WeekDayNumber = $day->format('w') + 1;
                    $checkAttendance = checkAttendance($day, $attendances);
                    // dd($day,$WeekDayNumber,$checkAttendance);
                    $checkBusinessLeave = checkBusinessLeave($WeekDayNumber, $companyBusinessLeave);
                    $checkSpecialHoliday = checkSpecialHoliday($day->format('Y-m-d'), $companySpecialLeave);
                    $checkGovermentHoliday = checkGovermentHoliday($day->format('Y-m-d'), $companyGovermentLeave);
                    if ($checkAttendance) {
                        $reportData[$day->format('Y-m-d')] = $checkAttendance;
                    } elseif ($checkBusinessLeave) {
                        $reportData[$day->format('Y-m-d')] = $checkBusinessLeave;
                    } elseif ($checkSpecialHoliday) {
                        $reportData[$day->format('Y-m-d')] = $checkSpecialHoliday;
                    } elseif ($checkGovermentHoliday) {
                        $reportData[$day->format('Y-m-d')] = $checkGovermentHoliday;
                    } else {
                        $reportData[$day->format('Y-m-d')] = "Absent";
                    }
                }

                if ($request->date) {
                    $attendanceDate = Carbon::parse($request->date);
                } else {
                    $attendanceDate = Carbon::today();
                }
                $attendance = Attendance::where('candidate_id', $candidateid)
                    ->where('company_id', $companyid)
                    ->whereDate('created_at', $attendanceDate)
                    ->where(function ($q) {
                        $q->whereIn('employee_status', ['Present', 'late', 'Leave']);
                    })
                    ->first();

                if ($attendance) {
                    $startime = $attendance->start_time;
                    $endtime = $attendance->end_time;
                    $totalearning = $attendance->earning + $attendance->overtime_earning;
                    $status = 'present';
                } else {
                    $status = 'absent';
                }
                $weekly_datas = $reportData;
                $start_time = isset($startime) ? $startime : null;
                $end_time = isset($endtime) ? $endtime : null;
                $break_time = isset($attendance) ? $attendance->breakDuration : null;
                $attendance_duration = isset($attendance) ? $attendance->attendanceDuration : null;
                $earning = isset($attendance) ? $attendance->earning  : null;
                $overtime_earning = isset($attendance) ? $attendance->overtime_earning  : null;
                $totalearning = $totalearning  ?? null;
                $attendance_duration_percentage = 0;

                $completedHoursArray = explode(":", $attendance_duration);
                $break_time_data = $break_time;
                if ($attendance_duration != null) {
                    if ($break_time == null) {
                        $break_time = Carbon::createFromFormat('H:i:s', "00:00:00")->format('H:i:s');
                        $break_time_data = Carbon::createFromFormat('H:i:s', "00:00:00");
                        $totalHours = $break_time_data;
                    } else {
                        $totalHours = $break_time;
                    }
                    $totalHours->addHours($completedHoursArray[0]); // Add 3 hours
                    $totalHours->addMinutes($completedHoursArray[1]); // Add 30 minutes
                    $totalHours->addSeconds($completedHoursArray[2]); // Add 20 seconds
                    $totalHoursArray = explode(":", ($totalHours->format('h:i:s')));
                    // dd($totalHoursArray);
                    $totalHoursDecimal = $totalHoursArray[0] + ($totalHoursArray[1] / 60) + ($totalHoursArray[2] / 3600);
                    $completedHoursDecimal = $completedHoursArray[0] + ($completedHoursArray[1] / 60) + ($completedHoursArray[2] / 3600);
                    // Calculate percentage
                    $attendance_duration_percentage = ($completedHoursDecimal / $totalHoursDecimal) * 100;
                }
                return view(
                    'Employer::backend.reports.candidateDailyAttendanceReport',
                    compact(
                        'start_time',
                        'end_time',
                        'weekly_datas',
                        'break_time',
                        'status',
                        'attendance_duration',
                        'earning',
                        'overtime_earning',
                        'totalearning',
                        'candidate',
                        'attendance_duration_percentage'
                    )
                );
            }
            Toastr::error('Candidate Not Found.');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    // get all candidate join months
    public function getAllCompanyCandidateMonths($candidateJoinDate)
    {
        try {
                $start =  $candidateJoinDate;
                $end  =  Carbon::now();
                $interval = DateInterval::createFromDateString('1 month');
                $period  = new DatePeriod($start, $interval, $end);
                $months = [];
                foreach ($period as $dt) {
                    $months[] = $dt->format("M-y");
                }
                
              return $months;
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    // get all candidate join months
    public function getAllCompanyCandidateYears($companyCandidate)
    {
        try {
            if ($companyCandidate) {
                $years = [];
                for ($date = $companyCandidate->created_at->copy(); $date->lte(Carbon::now()); $date->addYear()) {
                    $years[] = $date->format('Y');
                }
               return $years;
            }
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function candidateWeeklyAttendanceReport($companyid, $candidate_id, Request $request)
    {
        try {
            if ($request->from && $request->to) {
                $weekStart = Carbon::parse($request->from);
                $weekEnd = Carbon::parse($request->to);
                $now = $weekEnd;
                $currentWeekEndDate = Carbon::now()->endOfWeek();
                if ($weekEnd > $currentWeekEndDate) {
                    Toastr::warning('Information Not Avaliable.');
                    return redirect()->back();
                }
            } else {
                $now = Carbon::now();
                $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
                $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            }
            $weekNumberInMonth = $now->weekNumberInMonth;

            $candidate = CompanyCandidate::where('candidate_id', $candidate_id)
                ->with('candidate')->first();
            $attendances = Attendance::where('candidate_id', $candidate_id)->where('company_id', $companyid)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->get();

            $companyBusinessLeave = CompanyBusinessleave::where('company_id', $candidate->company_id)->get()
                ->pluck('business_leave_id')->toArray();

            $companySpecialLeave = CompanySpecialleave::where('company_id', $candidate->company_id)
                ->whereBetween('leave_date', [$weekStart, $weekEnd])
                ->get()->pluck('leave_date')->toArray();
            $companyGovermentLeave = CompanyGovernmentleave::where('company_id', $candidate->company_id)
                ->whereBetween('leave_date', [$weekStart, $weekEnd])
                ->get()->pluck('leave_date')->toArray();

            $getWeeks = $this->getWeeks($now);
            $reportData = [];
            for ($i = 0; $i < 7; $i++) {
                $day = $weekStart->copy()->addDays($i);
                $WeekDayNumber = $day->format('w') + 1;
                $checkAttendance = checkAttendance($day, $attendances);
                $checkBusinessLeave = checkBusinessLeave($WeekDayNumber, $companyBusinessLeave);
                $checkSpecialHoliday = checkSpecialHoliday($day->format('Y-m-d'), $companySpecialLeave);
                $checkGovermentHoliday = checkGovermentHoliday($day->format('Y-m-d'), $companyGovermentLeave);
                if ($checkAttendance) {
                    $reportData[$day->format('Y-m-d')] = $checkAttendance;
                } elseif ($checkBusinessLeave) {
                    $reportData[$day->format('Y-m-d')] = $checkBusinessLeave;
                } elseif ($checkSpecialHoliday) {
                    $reportData[$day->format('Y-m-d')] = $checkSpecialHoliday;
                } elseif ($checkGovermentHoliday) {
                    $reportData[$day->format('Y-m-d')] = $checkGovermentHoliday;
                } else {
                    $reportData[$day->format('Y-m-d')] = "Absent";
                }
            }
            $weeklyDatas = collect($reportData) ?? [];
            $absentdates = array_filter($reportData, function ($var) {
                return ($var == "Absent");
            });

            foreach ($absentdates as $key => $value) {
                $attendance = Attendance::updateOrCreate([
                    'candidate_id' => $candidate_id,
                    'company_id' => $companyid,
                    'created_at' => Carbon::parse($key)
                ], [
                    'employee_status' => "Absent"
                ]);
            }

            $counts = array_count_values($reportData);
            $presentCount = $counts['Present'] ?? 0;
            $absentCount = $counts['Absent'] ?? 0;
            $lateCount = $counts['Late'] ?? 0;
            $leaveCount = $counts['leave'] ?? 0;
            $businessLeaveCount = $counts['Business Leave'] ?? 0;
            $governmentLeaveCount = $counts['Government Holiday'] ?? 0;
            $speciallLeaveCount = $counts['Special Holiday'] ?? 0;
            $specialLeaveCount = $specialleaveCount ?? 0;
            $currentWeekSalary = $attendances->sum('earning');

            return view(
                'Employer::backend.reports.candidateWeeklyAttendanceReport',
                compact('weeklyDatas',
                    'presentCount',
                    'absentCount',
                    'leaveCount',
                    'lateCount',
                    'businessLeaveCount',
                    'governmentLeaveCount',
                    'speciallLeaveCount',
                    'currentWeekSalary',
                    'candidate',
                    'getWeeks',
                    'weekNumberInMonth'
                )
            );
        } catch (Exception  $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function candidateMonthlyAttendanceReport($companyid, $candidate_id,Request $request)
    {
        // try {
            if($request->month){
                $date = Carbon::createFromFormat('M-y',$request->month)->endOfMonth();
            }else{
                $date = Carbon::now();
            }
            
            $totalDays = Carbon::parse($date)->daysInMonth;
            $monthStart = Carbon::parse($date)->firstOfMonth();
            $monthEnd = Carbon::parse($date)->endOfMonth();
            $candidate = CompanyCandidate::where('candidate_id', $candidate_id)
                            ->with('candidate')->first();

            $allMonths = $this->getAllCompanyCandidateMonths($candidate->joining_date);

            $attendances = Attendance::where('candidate_id', $candidate_id)->where('company_id', $companyid)
                            ->whereBetween('created_at', [$monthStart, $monthEnd]);

            $totalEarning = $attendances->sum('earning');
            $attendances = $attendances->get();
            // dd($attendances);
            $companyBusinessLeave = CompanyBusinessleave::where('company_id', $candidate->company_id)->get()
                        ->pluck('business_leave_id')->toArray();
            $companySpecialLeave = CompanySpecialleave::where('company_id', $candidate->company_id)
                        ->whereBetween('leave_date', [$monthStart, $monthEnd])->get()->pluck('leave_date')->toArray();
            $companyGovermentLeave = CompanyGovernmentleave::where('company_id', $candidate->company_id)
                        ->whereBetween('leave_date', [$monthStart, $monthEnd])->get()->pluck('leave_date')->toArray();
            $reportData = [];
            for ($i = 0; $i <= $totalDays; $i++) {
                $day = $monthStart->copy()->addDays($i);
                $WeekDayNumber = $day->format('w') + 1;
                $checkAttendance = checkAttendance($day, $attendances);
                $checkBusinessLeave = checkBusinessLeave($WeekDayNumber, $companyBusinessLeave);
                $checkSpecialHoliday = checkSpecialHoliday($day->format('Y-m-d'), $companySpecialLeave);
                $checkGovermentHoliday = checkGovermentHoliday($day->format('Y-m-d'), $companyGovermentLeave);
                if ($checkAttendance) {
                    $reportData[$day->format('Y-m-d')] = $checkAttendance;
                } elseif ($checkBusinessLeave) {
                    $reportData[$day->format('Y-m-d')] = $checkBusinessLeave;
                } elseif ($checkSpecialHoliday) {
                    $reportData[$day->format('Y-m-d')] = $checkSpecialHoliday;
                } elseif ($checkGovermentHoliday) {
                    $reportData[$day->format('Y-m-d')] = $checkGovermentHoliday;
                } else {
                    if ($day->format('Y-m-d') > $date) {
                        $reportData[$day->format('Y-m-d')] = "Remaining Days";
                    } else {
                        $reportData[$day->format('Y-m-d')] = "Absent";
                    }
                }
            }

            // dd($reportData);
            $reportDataCollection = collect($reportData);
            $counts = array_count_values($reportData);

            $presentCount = $counts['Present'] ?? 0;
            $absentCount = $counts['Absent'] ?? 0;
            $leaveCount = $counts['Leave'] ?? 0;
            $lateCount = $counts['Late'] ?? 0;

            $remainingDaysCount = $counts['Remaining Days'] ?? 0;

            $businessLeaveCount = $counts['Business Leave'] ?? 0;
            $governmentLeaveCount = $counts['Government Leave'] ?? 0;
            $specialLeaveCount = $counts['Special Leave'] ?? 0;

            $company = Company::where('id', $companyid)
                        ->where('status', 'Active')
                        // ->where('employer_id', auth()->user()->id)
                        ->first();

            $sickleaves = Leave::where('candidate_id', $candidate_id)
                            ->with('LeaveType')
                            ->whereHas('LeaveType', function ($q) {
                                $q->where('title', 'Sick');
                            })
                            ->where('approved', 1)
                            ->whereMonth('created_at', $request->month)
                            ->get();

            $leaveTaken = 0;
            foreach ($sickleaves as $sickleave) {
                $leaveTaken = $leaveTaken + (int) Carbon::parse($sickleave->start_date)->diffInDays(Carbon::parse($sickleave->end_date));
            }
            $companyTotalAvailableSickLeave =  $company->leave_duration ?? null;
            $totalSickDaysLeft = 0;
            if (isset($companyTotalAvailableSickLeave)  && isset($leaveTaken)) {
                $totalSickDaysLeft = $companyTotalAvailableSickLeave - $leaveTaken;
            }
          
            $overtime = 500;
            $bonus = 500;
            $allowance = 1500;
            $tax = 200;
            $penalty = 1500;
            $activeMonth = $date->format('M-y');

            return view('Employer::backend.reports.candidateMonthlyAttendanceReport',
                    compact('presentCount', 'absentCount','leaveCount','totalDays','lateCount',
                        'businessLeaveCount', 'governmentLeaveCount','specialLeaveCount',
                        'totalSickDaysLeft','leaveTaken','companyTotalAvailableSickLeave',
                        'totalEarning','overtime', 'bonus','allowance','tax','penalty',
                        'candidate','allMonths','activeMonth'));
           
        // } catch (\Exception $e) {
        //     Toastr::error($e->getMessage());
        //     return redirect()->back();
        // }
    }

    public function candidateYearlyAttendanceReport($companyid, $candidate_id,Request $request)
    {
        // try {

            $companycandidate = CompanyCandidate::where('company_id', $companyid)
                                        ->where('candidate_id', $candidate_id)
                                        ->active()
                                        ->verified()->first();
            if ($companycandidate) {
                if($request->year){
                    $year = $request->year;
                }else{
                    $year = Carbon::now()->format('Y');
                }

                $allmonths=[];
                for ($m=1; $m<=12; $m++) {
                    $allmonths[] = date('F', mktime(0,0,0,$m, 1,  $year));
                }

                $years = $this->getAllCompanyCandidateYears($companycandidate);
                    
                $attendances = Attendance::where('company_id', $companyid)
                                ->where('candidate_id', $candidate_id)
                                ->whereYear('created_at', $year)->get();
               
                $monthlyPayments = Payment::where('candidate_id', $candidate_id)
                                ->whereYear('payment_for_month', $year)
                                ->select('status')
                                ->select(
                                        DB::raw('sum(paid_amount) as totalMonthlyPayment'), 
                                        DB::raw("DATE_FORMAT(payment_for_month,'%M') as months"),
                                )
                                ->groupBy('months')
                                ->get();
            
               return view('Employer::backend.reports.candidateYearlyAttendanceReport',
                            compact('companycandidate','allmonths','monthlyPayments','years'));
            }
            Toastr::error("Candidate dees not exists");
            return redirect()->back();
        // } catch (\Exception $e) {
        //     Toastr::error($e->getMessage());
        //     return redirect()->back();
        // }
    }

    public function filterReport($companyid, $year, $month)
    {
        try {

            //paid candidate of this month
            $paymentReport = Payment::where('company_id', $companyid)
                // ->where('candidate_id', $candidateid)
                ->whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->with('candidate')
                ->get();


            //paid candidate id of this month
            if ($paymentReport->count() > 0) {
                $payedCandidate = $paymentReport->pluck('candidate_id')->toArray();
            } else {
                $payedCandidate = [];
            }


            //unpaid candidate
            if (isset($payedCandidate) && !empty($payedCandidate)) {

                $unpaidcompanycandidate = CompanyCandidate::where('company_id', $companyid)
                    ->where('status', 'Active')
                    ->where('verified_status', 'verified')
                    ->whereNotIn('candidate_id', [$payedCandidate])
                    ->with('candidate')
                    ->get();


                $newpaidCandidate = [];

                foreach ($paymentReport as $payedcandidate) {
                    $newpaidCandidate[] = [
                        'id' => $payedcandidate->candidate->id,
                        'name' => $payedcandidate->candidate->name ?? $payedcandidate->candidate->phone,
                        'status' => 'paid',
                        'amount' => $payedcandidate->paid_amount
                    ];
                }
                $newUnpaidCandidate = [];
                foreach ($unpaidcompanycandidate as $unpaidCandidate) {
                    $newUnpaidCandidate[] = [
                        'id' => $unpaidCandidate->candidate->id,
                        'name' => $unpaidCandidate->candidate->name ?? $unpaidCandidate->candidate->phone ?? null,
                        'status' => "unpaid",
                        'amount' => $unpaidCandidate->salary_amount ?? null
                    ];
                }

                $paidUnpaidCandidates = array_merge($newpaidCandidate, $newUnpaidCandidate);
                $balance = 0;
                foreach ($paidUnpaidCandidates as $candidate) {
                    $balance = $balance + (float)$candidate['amount'];
                }
                // $balance = $unpaidcompanycandidate->sum('salary_amount');
                // dd(collect());

            } else {

                $unpaidcompanycandidate = CompanyCandidate::where('company_id', $companyid)
                    ->where('status', 'Active')
                    ->where('verified_status', 'verified')
                    // ->whereNotIn('candidate_id', [$payedCandidate])
                    ->with('candidate')
                    ->get();
                foreach ($unpaidcompanycandidate as $unpaidCandidate) {
                    $paidUnpaidCandidates[] = [
                        'id' => $unpaidCandidate->candidate->id,
                        'name' => $unpaidCandidate->candidate->name ?? $unpaidCandidate->candidate->phone ?? null,
                        'status' => "unpaid",
                        'amount' => $unpaidCandidate->salary_amount ?? null
                    ];
                }
                $balance = $unpaidcompanycandidate->sum('salary_amount');
            }


            if (isset($paidUnpaidCandidates) && !empty($paidUnpaidCandidates)) {
                // $paidUnpaidCandidates = CompanyPaymentReportResource::collection($paidUnpaidCandidates);
            } else {
                $paidUnpaidCandidates = [];
            }
            $data = [
                'candidates' => $paidUnpaidCandidates,
                'balance' => $balance
            ];

            return $this->response->responseSuccess($data, "Success", 200);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }





    public function paymentSubmit(PaymentStoreRequest $request, $company_id, $candidate_id)
    {
        try {
            $monthDate = Carbon::createFromFormat('M-y',$request->payment_for_month)->endOfMonth();
            $payment = Payment::where('candidate_id',$candidate_id)
                                 ->where('company_id',$company_id)
                                 ->where('payment_for_month',$monthDate)
                                 ->first();
            if($payment){
                Toastr::info('Payment Already Made.');
                return redirect()->back();
            }
            $payment = new Payment();
            $payment->status = $request->status;
            $payment->paid_amount = $request->paid_amount;
            $payment->bonus = $request->bonus ?? 0;
            $payment->deduction = $request->deduction ?? 0;
            $payment->payment_date = Carbon::now();
            $payment->payment_for_month = $monthDate;
            $payment->company_id = $company_id;
            $payment->candidate_id = $candidate_id;
            $payment->employer_id = Auth::guard('web')->id();
            if ($payment->save() == true) {
                Toastr::success('Successfully Paid.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }



    public function checkPayment($companyid, $candidateid, $month)
    {
        try {
            $candidatePayment = Payment::where('company_id', $companyid)
                        ->where('candidate_id', $candidateid)
                        ->whereMonth('payment_for_month', $month)
                        ->exists();
            if ($candidatePayment) {
                $status = 'paid';
            } else {
                $status = 'unpaid';
            }
            return $this->response->responseSuccess($status, "Successfully fetched", 200);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }



    public function getWeeks($date)
    {
        $date = $date->copy()->firstOfMonth()->startOfDay();
        $eom = $date->copy()->endOfMonth()->startOfDay();
        $dates = [];
        for ($i = 1; $date->lte($eom); $i++) {
            $start = clone $date->startOfWeek(Carbon::SUNDAY);
            $end =  clone $date->endOfWeek(Carbon::SATURDAY);
            $dates[$i]['start'] = $start->format('Y-m-d');
            $dates[$i]['end'] = $end->format('Y-m-d');
            $date->addDays(1);
        }
        return $dates;
    }
}
