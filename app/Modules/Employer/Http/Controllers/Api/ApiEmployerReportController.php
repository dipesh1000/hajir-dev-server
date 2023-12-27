<?php

namespace Employer\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\YearlyEarningResource;
use App\Models\CompanyGovernmentleave;
use App\Models\CompanySpecialleave;
use Candidate\Models\Attendance;
use Candidate\Models\CompanyBusinessleave;
use Candidate\Models\CompanyCandidate;
use Candidate\Models\Leave;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Employer\Http\Resources\CompanyCandidateDailyAttendanceReport;
use Employer\Http\Resources\CompanyPaymentReportResource;
use Employer\Http\Requests\PaymentStoreRequest;
use Employer\Models\Company;
use Employer\Models\Payment;
use Exception;
use Facade\FlareClient\View;
use Illuminate\Support\Facades\Auth;

class ApiEmployerReportController extends Controller
{
    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function currentDayReport($id)
    {
        try {
            $companyCandidates = CompanyCandidate::where('company_id', $id)->active()
                ->verified()
                ->with([
                    'candidate', 'companyCandidateAttendaces'
                ])->get();

            if ($companyCandidates && $companyCandidates->count() > 0) {
                $companyCandidates =  CompanyCandidateDailyAttendanceReport::collection($companyCandidates);
            }
            $absentCount = CompanyCandidate::where('company_id', $id)
                ->active()->verified()
                ->with(
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
                ->verified()->count();
            $data = [
                'total_attendee' =>  $totalattendee ?? 0,
                'present' => $presentCount ?? 0,
                'late' =>  $lateCount  ?? 0,
                'absent' => $absentCount ?? 0,
                'candidates' => $companyCandidates ?? [],
                // 'candidates' => CandidateResource::collection($companyCandidates)
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (Exception  $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function allCompanyCandidates($id)
    {
        try {
            $companyCandidates = CompanyCandidate::where('company_id', $id)
                ->verified()
                ->with([
                    'candidate', 'activecompanyCandidateAttendaces'
                ])
                ->whereHas('activecompanyCandidateAttendaces', function ($q) {
                    $q->whereIn('employee_status', ['Present', 'Late']);
                })
                ->get();

            if ($companyCandidates && $companyCandidates) {
                $candidates =  CompanyCandidateDailyAttendanceReport::collection($companyCandidates);
            }
            $data = [
                'candidates' => $candidates ?? []
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (Exception  $e) {
            return $this->response->responseError($e->getMessage());
        }
    }



    public function activeCompanyCandidates($id)
    {
        try {
            $companyCandidates = CompanyCandidate::where('company_id', $id)
                ->active()->verified()
                ->with([
                    'candidate', 'activecompanyCandidateAttendaces'
                ])
                ->whereHas('activecompanyCandidateAttendaces', function ($q) {
                    $q->whereIn('employee_status', ['Present', 'Late']);
                })
                ->get();

            if ($companyCandidates && $companyCandidates) {
                $candidates =  CompanyCandidateDailyAttendanceReport::collection($companyCandidates);
            }
            $data = [
                'candidates' => $candidates ?? []
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (Exception  $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function inactiveCompanyCandidates($id)
    {
        try {
            $companyCandidates = CompanyCandidate::where('company_id', $id)
                ->inactive()->verified()
                ->with([
                    'candidate', 'activecompanyCandidateAttendaces'
                ])
                ->whereDoesntHave('activecompanyCandidateAttendaces')
                ->get();

            if ($companyCandidates) {
                $candidates =  CompanyCandidateDailyAttendanceReport::collection($companyCandidates);
            }
            $data = [
                'candidates' => $candidates ?? []
            ];

            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (Exception  $e) {
            return $this->response->responseError($e->getMessage());
        }
    }



    function weeksInMonth($numOfDaysInMonth)
    {
        $daysInWeek = 7;
        $result = $numOfDaysInMonth / $daysInWeek;
        $numberOfFullWeeks = floor($result);
        return $numberOfFullWeeks;
    }




    public function dailyReport($companyid, $candidateid)
    {
        try {
            $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
            $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            $candidate = CompanyCandidate::where('candidate_id', $candidateid)
                ->active()->verified()->first();
            if ($candidate) {
                $attendances = Attendance::where('candidate_id', $candidateid)->where('company_id', $companyid)
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->get();

                $companyBusinessLeave = CompanyBusinessleave::where('company_id', $candidate->company_id)->get()
                    ->pluck('business_leave_id')->toArray();

                $companySpecialLeave = CompanySpecialleave::where('company_id', $candidate->company_id)
                    ->whereBetween('leave_date', [$weekStart, $weekEnd])->get()->pluck('leave_date')->toArray();
                $companyGovermentLeave = CompanyGovernmentleave::where('company_id', $candidate->company_id)
                    ->whereBetween('leave_date', [$weekStart, $weekEnd])->get()->pluck('leave_date')->toArray();
                $reportData = [];
                for ($i = 0; $i <= 6; $i++) {
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
                $attendance = Attendance::where('candidate_id', $candidateid)
                    ->where('company_id', $companyid)
                    ->where('created_at', Carbon::today())
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

                $data = [
                    'weekly_datas' => $reportData,
                    'start_time' => isset($startime) ? $startime : null,
                    'end_time' => isset($endtime) ? $endtime : null,
                    'break_time' => isset($attendance) ? $attendance->breakDuration : null,
                    'attendance_duration' => isset($attendance) ? $attendance->attendanceDuration : null,
                    'earning' => isset($attendance) ? $attendance->earning  : null,
                    'overtime_earning' => isset($attendance) ? $attendance->overtime_earning  : null,
                    'totalearning' => $totalearning  ?? null,

                ];
                return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Candidate Not Found.", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    // get all candidate join months
    public function getAllCompanyCandidateMonths($companyid, $candidateid)
    {
        try {
            $companycandidate = CompanyCandidate::where('company_id', $companyid)
                ->where('candidate_id', $candidateid)
                ->active()->verified()
                ->first();
            if ($companycandidate) {
                $start =  $companycandidate->updated_at;
                $end  =  Carbon::now();
                $interval = DateInterval::createFromDateString('1 month');
                $period   = new DatePeriod($start, $interval, $end);
                $months = [];
                foreach ($period as $dt) {
                    $months[] =  [
                        'month' => $dt->format("Y-m-d")
                    ];
                }
                $data = [
                    'months' => $months
                ];
                return $this->response->responseSuccess($data, "Successfully Fetched", 200);
            }
            return $this->response->responseError("Company Candidate Not Found", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    // get all candidate join months
    public function getAllCompanyCandidateYears($companyid, $candidateid)
    {
        try {
            $companycandidate = CompanyCandidate::where('company_id', $companyid)
                ->where('candidate_id', $candidateid)
                ->active()->verified()
                ->first();
            if ($companycandidate) {
                $years = [];
                for (
                    $date = $companycandidate->updated_at->copy();
                    $date->lte(Carbon::now());
                    $date->addYear()
                ) {
                    $years[] = [
                        'year' => $date->format('Y-m-d')
                    ];
                }
                $data = [
                    'years' => $years
                ];
                return $this->response->responseSuccess($data, "Successfully Fetched", 200);
            }
            return $this->response->responseError("Company Candidate Not Found", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function weeklyReport($companyid, $candidate_id)
    {
        // dd('aksdkjh');
        try {
            $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
            $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            $candidate = CompanyCandidate::where('candidate_id', $candidate_id)->first();
            $attendances = Attendance::where('candidate_id', $candidate_id)->where('company_id', $companyid)->whereBetween('created_at', [$weekStart, $weekEnd])->get();
            $companyBusinessLeave = CompanyBusinessleave::where('company_id', $candidate->company_id)->get()
                ->pluck('business_leave_id')->toArray();

            $companySpecialLeave = CompanySpecialleave::where('company_id', $candidate->company_id)
                ->whereBetween('leave_date', [$weekStart, $weekEnd])->get()->pluck('leave_date')->toArray();
            $companyGovermentLeave = CompanyGovernmentleave::where('company_id', $candidate->company_id)
                ->whereBetween('leave_date', [$weekStart, $weekEnd])->get()->pluck('leave_date')->toArray();
            $reportData = [];
            for ($i = 0; $i <= 6; $i++) {
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
            $reportDataCollection = collect($reportData);

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
            $leaveCount = $counts['leave'] ?? 0;
            $businessleaveCount = $counts['Business Holiday'] ?? 0;
            $governmentleaveCount = $counts['Government Holiday'] ?? 0;
            $specialleaveCount = $counts['Special Holiday'] ?? 0;
            $companyCandidate = CompanyCandidate::where('company_id', $companyid)
                ->where('candidate_id', $candidate_id)->first();

            $candidateMonthlySalary = $companyCandidate->salary_amount;
            $numberOfDaysInMonth = Carbon::now()->daysInMonth;
            $weekInCurrentMonth = (int) $this->weeksInMonth($numberOfDaysInMonth);
            $daysInCurrentMonth = (int) Carbon::parse(today())->daysInMonth;
            $salaryInWeek = (float)$candidateMonthlySalary / $weekInCurrentMonth;
            $salaryPerDay = (float)$candidateMonthlySalary / $daysInCurrentMonth;

            $salaryCountingdays = 7 - $absentCount;
            $currentweekSalary = floor($salaryCountingdays * $salaryPerDay);
            $data = [
                'present' =>  $presentCount ?? 0,
                'absent' => $absentcount ?? 0,
                'leave' => $leaveCount ?? 0,
                'weekly_datas' => $reportDataCollection ?? [],
                'businessleaveCount' => $businessleaveCount ?? 0,
                'governmentLeaveCount' => $governmentleaveCount ?? 0,
                'specialLeaveCount' => $specialleaveCount ?? 0,
                'current_week_salary' => $currentweekSalary
            ];

            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
            // }
            // return $this->response->responseError("Company not found");

        } catch (Exception  $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function monthlyReport($companyid, $candidate_id, $month = null)
    {
        try {
            $date = $month;
            $totaldays = Carbon::parse($date)->daysInMonth;
            $monthStart = Carbon::parse($date)
                ->firstOfMonth();
            $monthEnd = Carbon::parse($date)
                ->endOfMonth();
            $candidate = CompanyCandidate::where('candidate_id', $candidate_id)->first();
            $attendances = Attendance::where('candidate_id', $candidate_id)->where('company_id', $companyid)
                ->whereBetween('created_at', [$monthStart, $monthEnd]);

            $totalearning = $attendances->sum('earning');
            $attendances = $attendances->get();

            $companyBusinessLeave = CompanyBusinessleave::where('company_id', $candidate->company_id)->get()
                ->pluck('business_leave_id')->toArray();

            $companySpecialLeave = CompanySpecialleave::where('company_id', $candidate->company_id)
                ->whereBetween('leave_date', [$monthStart, $monthEnd])->get()->pluck('leave_date')->toArray();
            $companyGovermentLeave = CompanyGovernmentleave::where('company_id', $candidate->company_id)
                ->whereBetween('leave_date', [$monthStart, $monthEnd])->get()->pluck('leave_date')->toArray();
            $reportData = [];
            for ($i = 0; $i <= $totaldays; $i++) {
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
                    $reportData[$day->format('Y-m-d')] = "Absent";
                }
            }

            $reportDataCollection = collect($reportData);
            $counts = array_count_values($reportData);

            $presentCount = $counts['Present'] ?? 0;
            $absentCount = $counts['Absent'] ?? 0;
            $leaveCount = $counts['leave'] ?? 0;
            $businessleaveCount = $counts['Business Holiday'] ?? 0;
            $governmentleaveCount = $counts['Government Holiday'] ?? 0;
            $specialleaveCount = $counts['Special Holiday'] ?? 0;

            $company = Company::where('id', $companyid)
                ->where('status', 'Active')
                // ->where('employer_id', auth()->user()->id)
                ->first();


            // dd($candidate_id);
            $Sickleaves = Leave::where('candidate_id', $candidate_id)
                ->with('LeaveType')
                ->whereHas('LeaveType', function ($q) {
                    $q->where('title', 'Sick');
                })
                ->where('status', 'Approved')
                ->whereMonth('created_at', $month)
                ->get();

            $counter = 0;
            foreach ($Sickleaves as $sickleave) {
                $counter = $counter + (int) Carbon::parse($sickleave->start_date)->diffInDays(Carbon::parse($sickleave->end_date));
            }
            $company_totalavailablesickleave =  $company->leave_duration ?? null;
            if (isset($company_totalavailablesickleave)  && isset($counter)) {
                $totalsickdaysleft = $company_totalavailablesickleave - $counter;
            }
            $data = [
                'presentCount' => $presentCount ?? 0,
                'absentcount' => $absentCount ?? 0,
                'leavecount' => (int)$leaveCount ?? 0,
                'totaldays' => $totaldays ?? 0,
                'businessleavedays' => $businessleaveCount ?? 0,
                'governmentLeavedaysCount' => $governmentleaveCount ?? 0,
                'specialLeavedaysCount' => $specialleaveCount ?? 0,
                'salary' => $totalearning ?? 0,
                'total_leave' => $company_totalavailablesickleave ?? 7,
                'total_available' => $totalsickdaysleft ?? 0,
                'taken' => $counter ?? 0,
                'overtime' => 500,
                'bonus' => 500,
                'allowance' => 1500,
                'tax' => 200,
                'penalty' => 1500,
            ];

            return $this->response->responseSuccess($data, "Success", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
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
            return $this->response->responseError($e->getMessage());
        }
    }


    public function reportPdf($companyid, $year, $month)
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
            $data = view('Employer::reportexport.monthly-report', compact('data'))->render();


            return $this->response->responseSuccess($data, "Success", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function testPDF($companyid, $year, $month)
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

            return view('Employer::reportexport.monthly-report', compact('data'));
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function yearlyReport($companyid, $candidate_id, $year = null)
    {
        try {

            if (!$year) {
                $year = date('Y');
            }

            $allmonths = [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Nov", "Dec"
            ];

            $companycandidate = CompanyCandidate::where('company_id', $companyid)
                ->where('candidate_id', $candidate_id)->where('verified_status', 'verified')
                ->where('status', 'Active')->first();

            if ($companycandidate) {
                $payments = Payment::where('company_id', $companyid)
                    ->where('candidate_id', $candidate_id)->whereYear('payment_for_month', $year)->get();


                $attendances = Attendance::where('company_id', $companyid)
                    ->where('candidate_id', $candidate_id)->whereYear('created_at', $year)->get();
                $monthData = [];
                foreach ($allmonths as $month) {
                    $monthData[$month] = $payments->where('payment_for_month', $month)->first();
                }

                $datas = ["jan"];


                $data = [
                    'datas' => YearlyEarningResource::collection($datas),
                    'total' => 50000
                ];
                return $this->response->responseSuccess($data, "Success", 200);
            }
            return $this->response->responseError("Candidate dees not exists");
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function paymentSubmit(PaymentStoreRequest $request, $company_id, $candidate_id)
    {
        try {
            $payment = new Payment();
            $payment->status = $request->status;
            $payment->paid_amount = 20000;
            $payment->bonus = $request->bonus ?? 0;
            $payment->deduction = $request->deduction ?? 0;
            $payment->payment_date = Carbon::now();
            $payment->payment_for_month = Carbon::parse($request->payment_for_month);
            $payment->company_id = $company_id;
            $payment->candidate_id = $candidate_id;
            $payment->employer_id = Auth::id();
            if ($payment->save() == true) {
                return $this->response->responseSuccessMsg("Successfully Stored", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
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
            return $this->response->responseError($e->getMessage());
        }
    }
}
