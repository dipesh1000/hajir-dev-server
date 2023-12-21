<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyGovernmentleave;
use App\Models\CompanySpecialleave;
use Candidate\Models\Attendance;
use Candidate\Models\CompanyBusinessleave;
use Candidate\Models\CompanyCandidate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CompanyCandidateIncomeHistory extends Controller
{

    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function weeklyIncomeHistory($companyid = null)
    {
        try {
            $candidateid = auth()->user()->id;
            $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);

            $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            $candidate = CompanyCandidate::where('company_id', $companyid)
                ->where('candidate_id', $candidateid)
                ->where('verified_status', 'verified')
                ->where('status', 'Active')
                ->first();
            if ($candidate) {
                $attendances = Attendance::where('candidate_id', $candidateid)
                    ->where('company_id', $companyid)
                    ->whereBetween('created_at', [$weekStart, $weekEnd])->addSelect('*', DB::raw("DATE(created_at)  as check_date"))->get();

                $companyBusinessLeave = CompanyBusinessleave::where('company_id', $candidate->company_id)->get()
                    ->pluck('business_leave_id')->toArray();

                $companySpecialLeave = CompanySpecialleave::where('company_id', $candidate->company_id)
                    ->whereBetween('leave_date', [$weekStart, $weekEnd])->get()->pluck('leave_date')->toArray();
                $companyGovermentLeave = CompanyGovernmentleave::where('company_id', $candidate->company_id)
                    ->whereBetween('leave_date', [$weekStart, $weekEnd])->get()->pluck('leave_date')->toArray();
                $reportData = [];
                $daily_earning = 0;
                for ($i = 0; $i <= 6; $i++) {
                    $day = $weekStart->copy()->addDays($i);

                    if ($day->format('m') == date('m')) {
                        $WeekDayNumber = $day->format('w') + 1;

                        $checkAttendance = checkIncomeHistoryAttendance($day, $attendances);
                        $checkBusinessLeave = checkIncomeHistoryBusinessLeave($WeekDayNumber, $companyBusinessLeave, $companyid, $candidateid);
                        $checkSpecialHoliday = checkIncomeHistorySpecialHoliday($day->format('Y-m-d'), $companySpecialLeave, $companyid, $candidateid);
                        $checkGovermentHoliday = checkIncomeHistoryGovermentHoliday($day->format('Y-m-d'), $companyGovermentLeave, $companyid, $candidateid);

                        if ($checkAttendance) {
                            $daily_earning +=  $checkAttendance['earning'] ?? 0;
                            $reportData[$day->format('Y-m-d')] = [
                                'type' => $checkAttendance['status'],
                                'earning' => $daily_earning
                            ];
                        } elseif ($checkBusinessLeave) {
                            $daily_earning +=  $checkBusinessLeave['earning'] ?? 0;

                            $reportData[$day->format('Y-m-d')] = [
                                'type' => $checkBusinessLeave['status'],
                                'earning' => $daily_earning
                            ];
                        } elseif ($checkSpecialHoliday) {
                            $daily_earning +=  $checkSpecialHoliday['earning'] ?? 0;

                            $reportData[$day->format('Y-m-d')] = [
                                'type' => $checkSpecialHoliday['status'],
                                'earning' => $daily_earning
                            ];
                        } elseif ($checkGovermentHoliday) {
                            $daily_earning +=  $checkGovermentHoliday['earning'] ?? 0;
                            $reportData[$day->format('Y-m-d')] = [
                                'type' => $checkGovermentHoliday['status'],
                                'earning' => $daily_earning
                            ];
                        } else {

                            $reportData[$day->format('Y-m-d')] = [
                                'type' => "Absent",
                                'earning' => $daily_earning
                            ];
                        }
                    }
                }
            }
            $data = [
                'datas' => $reportData ?? []
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }




    public function monthlyIncomeHistory($companyid = null)
    {
        try {

            $candidateid = auth()->user()->id;

            $totaldays = Carbon::now()->daysInMonth;
            $monthStart = Carbon::now()
                        ->firstOfMonth();
            $monthEnd = Carbon::now()
                        ->endOfMonth();
            $candidate = CompanyCandidate::where('company_id', $companyid)
                ->where('candidate_id', $candidateid)
                ->where('verified_status', 'verified')
                ->where('status', 'Active')
                ->first();
            if ($candidate) {
                $attendances = Attendance::where('candidate_id', $candidateid)
                    ->where('company_id', $companyid)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])->addSelect('*', DB::raw("DATE(created_at)  as check_date"))->get();


                $companyBusinessLeave = CompanyBusinessleave::where('company_id', $candidate->company_id)->get()
                    ->pluck('business_leave_id')->toArray();

                $companySpecialLeave = CompanySpecialleave::where('company_id', $candidate->company_id)
                    ->whereBetween('leave_date', [$monthStart, $monthEnd])->get()->pluck('leave_date')->toArray();
                $companyGovermentLeave = CompanyGovernmentleave::where('company_id', $candidate->company_id)
                    ->whereBetween('leave_date', [$monthStart, $monthEnd])->get()->pluck('leave_date')->toArray();
                $reportData = [];
                $daily_earning = 0;
                for ($i = 0; $i <= $totaldays; $i++) {
                    $day = $monthStart->copy()->addDays($i);

                    if ($day->format('m') == date('m')) {
                        $WeekDayNumber = $day->format('w') + 1;

                        $checkAttendance = checkIncomeHistoryAttendance($day, $attendances);
                        $checkBusinessLeave = checkIncomeHistoryBusinessLeave($WeekDayNumber, $companyBusinessLeave, $companyid, $candidateid);
                        $checkSpecialHoliday = checkIncomeHistorySpecialHoliday($day->format('Y-m-d'), $companySpecialLeave, $companyid, $candidateid);
                        $checkGovermentHoliday = checkIncomeHistoryGovermentHoliday($day->format('Y-m-d'), $companyGovermentLeave, $companyid, $candidateid);

                        if ($checkAttendance) {
                            $daily_earning +=  $checkAttendance['earning'] ?? 0;
                            $reportData[$day->format('Y-m-d')] = [
                                'type' => $checkAttendance['status'],
                                'earning' => $daily_earning
                            ];
                        } elseif ($checkBusinessLeave) {
                            $daily_earning +=  $checkBusinessLeave['earning'] ?? 0;

                            $reportData[$day->format('Y-m-d')] = [
                                'type' => $checkBusinessLeave['status'],
                                'earning' => $daily_earning
                            ];
                        } elseif ($checkSpecialHoliday) {
                            $daily_earning += $checkSpecialHoliday['earning'] ?? 0;

                            $reportData[$day->format('Y-m-d')] = [
                                'type' => $checkSpecialHoliday['status'],
                                'earning' => $daily_earning
                            ];
                        } elseif ($checkGovermentHoliday) {
                            $daily_earning +=  $checkGovermentHoliday['earning'] ?? 0;
                            $reportData[$day->format('Y-m-d')] = [
                                'type' => $checkGovermentHoliday['status'],
                                'earning' => $daily_earning
                            ];
                        } else {

                            $reportData[$day->format('Y-m-d')] = [
                                'type' => "Absent",
                                'earning' => $daily_earning
                            ];
                        }
                    }
                }
            }
            $data = [
                'datas' => $reportData ?? []
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
