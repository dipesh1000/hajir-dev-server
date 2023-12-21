<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyGovernmentleave;
use App\Models\CompanySpecialleave;
use Candidate\Http\Resources\CandidateYearlyResource;
use Candidate\Models\Attendance;
use Candidate\Models\CompanyBusinessleave;
use Candidate\Models\CompanyCandidate;
use Candidate\Models\Leave;
use Carbon\Carbon;
use Employer\Models\Company;
use Employer\Models\Payment;
use Exception;
use Illuminate\Support\Facades\DB;

class ApiCandidateReportController extends Controller
{

    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    function weeksInMonth($numOfDaysInMonth)
    {
        $daysInWeek = 7;
        $result = $numOfDaysInMonth / $daysInWeek;
        $numberOfFullWeeks = floor($result);
        return $numberOfFullWeeks;
    }


    function monthlySalary()
    {
        $numberOfDaysInMonth = Carbon::now()->daysInMonth;
        $absentdaysCount = Attendance::where('employee_status', 'Absent')->whereBetween(
            'created_at',
            [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
        )->count();

        $salaryPerDay = 20000 / (int)$numberOfDaysInMonth;
        $absentSalary = $absentdaysCount * $salaryPerDay;
        $totalSalaryEarn = 20000 - (int) $absentSalary;
    }

    public function monthAllWeeks($companyid, $date)
    {
        if ($date) {
            $candidateid = auth()->user()->id;
            $candidate = CompanyCandidate::where('candidate_id', $candidateid)->where('company_id', $companyid)->first();
            if ($candidate) {
                $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
                $month = $carbonDate->format('m');
                $year = $carbonDate->format('Y');

                $firstDayOfMonth = Carbon::createFromDate($year, $month, 1);
                $lastDayOfMonth = Carbon::createFromDate($year, $month, $firstDayOfMonth->daysInMonth);

                $startOfWeek = $firstDayOfMonth->startOfWeek();
                $endOfWeek = $firstDayOfMonth->copy()->endOfWeek();

                $weeks = [];

                while ($startOfWeek->lte($lastDayOfMonth)) {
                    $weeks[] = [
                        'name' => 'Week ' . $startOfWeek->weekOfMonth,
                        'from' => $startOfWeek->copy()->format('Y-m-d'),
                        'to' => $endOfWeek->copy()->format('Y-m-d'),
                    ];

                    // Move to the next week
                    $startOfWeek->addWeek();
                    $endOfWeek->addWeek();
                }
                return $this->response->responseSuccess($weeks, "Successfully fetched", 200);
            }
            return $this->response->responseError("Candidate Not Found",400);
        }
        return $this->response->responseError("Date Not Found",400);
    }


    public function yearAllMonths($companyid, $year)
    {
        if ($year) {
            $candidateid = auth()->user()->id;
            $candidate = CompanyCandidate::where('candidate_id', $candidateid)->where('company_id', $companyid)->first();
            if ($candidate) {
                $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfMonth();

                $endOfYear = Carbon::createFromDate($year, 12, 1)->endOfMonth();

                $months = [];

                while ($startOfYear->lte($endOfYear)) {
                    $months[] = [
                        'name' => $startOfYear->format('F'),
                        'from' => $startOfYear->copy()->format('Y-m-d'),
                        'to' => $startOfYear->copy()->endOfMonth()->format('Y-m-d'),
                    ];

                    $startOfYear->addMonth();
                }
                return $this->response->responseSuccess($months, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Candidate Not Found.", 404);
        }
        return $this->response->responseError("Date Not Found.");
    }

    public function allYearsList($companyid, $year)
    {

            $candidateid = auth()->user()->id;
            $candidate = CompanyCandidate::where('candidate_id', $candidateid)->where('company_id', $companyid)->first();
            if ($candidate) {
                $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfMonth();
                $endOfYear = Carbon::createFromDate($year, 12, 1)->endOfMonth();
                $months = [];

                while ($startOfYear->lte($endOfYear)) {
                    $months[] = [
                        'name' => $startOfYear->format('F'),
                        'from' => $startOfYear->copy()->format('Y-m-d'),
                        'to' => $startOfYear->copy()->endOfMonth()->format('Y-m-d'),
                    ];

                    $startOfYear->addMonth();
                }

                return $this->response->responseSuccess($months, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Candidate Not Found.",404);
    }

    public function weeklyReport(Request $request, $companyid)
    {
        try {
            $candidateid = auth()->user()->id;
            if($request->input('from') && $request->input('to')){
                $weekStart = Carbon::parse($request->from);
                $weekEnd = Carbon::parse($request->to);
            }else{
                $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
                $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            }
            $candidate = CompanyCandidate::where('candidate_id', $candidateid)->first();
            if ($candidate) {
                $attendances = Attendance::where('candidate_id', $candidateid)->where('company_id', $companyid)->whereBetween('created_at', [$weekStart, $weekEnd])->get();

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
                        $todayDate = date('Y-m-d');
                        if (strtotime($todayDate) >= strtotime($day->format('Y-m-d'))) {
                            $reportData[$day->format('Y-m-d')] = "Absent";
                        } else {
                            $reportData[$day->format('Y-m-d')] = "NA";
                        }
                    }
                }

                $absentdates = array_filter($reportData, function ($var) {
                    return ($var == "Absent");
                });

                foreach ($absentdates as $key => $value) {
                    $attendance = Attendance::updateOrCreate([
                        'candidate_id' => auth()->user()->id,
                        'company_id' => $companyid,
                        'created_at' => Carbon::parse($key)
                    ], [
                        'employee_status' => "Absent",
                        'earning' => 0
                    ]);
                }

                $counts = array_count_values($reportData);

                $presentCount = $counts['Present'] ?? 0;
                $absentCount = $counts['Absent'] ?? 0;
                $leaveCount = $counts['leave'] ?? 0;
                $businessleaveCount = $counts['Business Leave'] ?? 0;
                $governmentleaveCount = $counts['Goverment Leave'] ?? 0;
                $specialleaveCount = $counts['Special Leave'] ?? 0;

                $companyCandidate = CompanyCandidate::where('company_id', $companyid)
                    ->where('candidate_id', auth()->user()->id)->first();
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
                    'absent' => $absentCount ?? 0,
                    'leave' => $leaveCount ?? 0,
                    'businessleaveCount' => $businessleaveCount ?? 0,
                    'governmentLeaveCount' => $governmentleaveCount ?? 0,
                    'specialLeaveCount' => $specialleaveCount ?? 0,
                    'weekdata' =>  $reportData ?? [],

                    'current_week_salary' => $currentweekSalary,
                    'current_week_overtime' => 0,
                    'current_week_bonus' => 0,
                    'current_week_allowance' => 0,
                    'current_week_total_salary' => $currentweekSalary,
                ];
                return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Candidate Not Found.",404);
        } catch (Exception  $e) {
            return $this->response->responseError($e->getMessage());
        }
    }





    public function monthlyReport($companyid, $month = null)
    {
        try {

            $date = $month;
            $candidate_id = auth()->user()->id;
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

            $counts = array_count_values($reportData);

            $presentCount = $counts['Present'] ?? 0;
            $absentCount = $counts['Absent'] ?? 0;
            $leaveCount = $counts['leave'] ?? 0;
            $businessleaveCount = $counts['Business Holiday'] ?? 0;
            $governmentleaveCount = $counts['Government Holiday'] ?? 0;
            $specialleaveCount = $counts['Special Holiday'] ?? 0;

            $data = [
                'presentCount' => $presentCount ?? 0,
                'absentcount' => $absentCount ?? 0,
                'leavecount' => $leaveCount ?? 0,
                'totaldays' => $totaldays ?? 0,
                'businessleavedays' => $businessleaveCount ?? 0,
                'governmentLeavedaysCount' => $governmentleaveCount ?? 0,
                'specialLeavedaysCount' => $specialleaveCount ?? 0,
                'monthly_salary' => 0,
                'monthly_overtime' => 0,
                'monthly_bonus' => 0,
                'monthly_allowance' => 0,
                'monthly_tax' => 0,
                'monthly_penalty' => 0,
                'monthly_total_salary' => 5000,
                'monthly_total_deduction' => 0,
                'monthly_sick_leave' => 3,
                'monthly_casual_leave' => 5,
                'avaliable_leave' => 4,

            ];

            return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }



    public function yearlyReport($companyid, $year)
    {
        try {
            if (!$year) {
                $year = date('Y');
            }
            $candidate_id = auth()->user()->id;
            $companycandidate = CompanyCandidate::where('company_id', $companyid)
                ->where('candidate_id', $candidate_id)->where('verified_status', 'verified')
                ->where('status', 'Active')->first();
            if ($companycandidate) {
                $payments = Payment::where('company_id', $companyid)
                    ->where('candidate_id', $candidate_id)->whereYear('payment_for_month', $year)
                    ->addSelect('*', DB::raw("MONTH(payment_for_month)  as check_month"))
                    ->get();

                $attendances = Attendance::where('company_id', $companyid)
                    ->where('candidate_id', $candidate_id)->whereYear('created_at', $year)
                    ->addSelect('*', DB::raw("MONTH(created_at)  as check_month"))->get();

                $monthlydata = [];
                $totalearning = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $monthlydata[] = checkPaymentandAttendanceOfCandidate($payments, $attendances, $i);
                    $totalearning += $monthlydata[$i]['earning'] ?? 0;
                }
                $data = [
                    'monthly_datas' =>  $monthlydata,
                    'total' => 2000
                ];
                return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Candidate Not Found.",404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
