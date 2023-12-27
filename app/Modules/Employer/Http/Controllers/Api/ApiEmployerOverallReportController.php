<?php

namespace Employer\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use Candidate\Models\CompanyCandidate;

class ApiEmployerOverallReportController extends Controller
{

    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function dailyReport($id)
    {
        try {
            $totalattendee = CompanyCandidate::where('company_id', $id)->count();
            $absentCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas('candidate', function ($q) {
                                    $q->whereDoesntHave('todayattendances');
                                })->count();

            $presentCount = CompanyCandidate::where('company_id', $id)
                        ->whereHas('candidate', function ($q) {
                            $q->whereHas('attendances', function ($q) {
                                $q->whereDate('created_at', today())
                                    ->where('employee_status', 'Present');
                            });
                        })->count();

            $lateCount = CompanyCandidate::where('company_id', $id)
                        ->whereHas('candidate', function ($q) {
                                $q->whereHas('attendances', function ($q) {
                                    $q->whereDate('created_at', today())
                                        ->where('employee_status', 'Late');
                                });
                            }
                        )->count();

            $LeaveCount = CompanyCandidate::where('company_id', $id)
                        ->whereHas('candidate',function ($q) {
                                $q->whereHas('attendances', function ($q) {
                                    $q->whereDate('created_at', today())
                                        ->where('employee_status', 'Leave');
                                });
                            })->count();

            $punchOutCount = CompanyCandidate::where('company_id', $id)
                                ->whereHas('candidate', function ($q) {
                                    $q->whereHas('attendances', function ($q) {
                                        $q->whereDate('created_at', today())
                                            ->where('employee_status', 'punchOut');
                                    });
                                })->count();

            $totalPresentToday = $presentCount ?? 0 +  $lateCount ?? 0;
            $presentPercentage = ($totalPresentToday*100)/$totalattendee;

            // dd($totalattendee,$presentCount,$absentCount,$lateCount);
            $data = [
                'total_attendee' =>   $totalattendee ?? 0,
                'present' => $presentCount ?? 0,
                'absent' => $absentCount ?? 0,
                'late' => $lateCount ?? 0,
                'punch_out' => $punchOutCount ?? 0,
                'leave_taken' => $LeaveCount ?? 0,
                'percentage' => $presentPercentage ?? 0
            ];

            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function weeklyReport($id)
    {
        try {
            $totalattendee = CompanyCandidate::where('company_id', $id)->count();
            $absentCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas('candidate',function ($q) {
                                    $q->whereDoesntHave('weeklyattendances');
                                })->count();

            $presentCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas( 'candidate',function ($q) {
                                $q->whereHas('attendances', function ($q) {
                                    $q->whereDate('created_at', today())
                                        ->where('employee_status', 'Present');
                                });
                            })->count();

            $lateCount = CompanyCandidate::where('company_id', $id)
                        ->whereHas('candidate', function ($q) {
                            $q->whereHas('attendances', function ($q) {
                                $q->whereDate('created_at', today())
                                    ->where('employee_status', 'Late');
                            });
                        })->count();

            $LeaveCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas('candidate',function ($q) {
                                $q->whereHas('attendances', function ($q) {
                                    $q->whereDate('created_at', today())
                                        ->where('employee_status', 'Leave');
                                });
                            })->count();

            $punchOutCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas('candidate',function ($q) {
                                    $q->whereHas('attendances', function ($q) {
                                        $q->whereDate('created_at', today())
                                            ->where('employee_status', 'punchOut');
                                    });
                                })->count();
                                
            $totalPresentToday = $presentCount ?? 0 +  $lateCount ?? 0;
            $presentPercentage = ($totalPresentToday*100)/$totalattendee;

            $data = [
                'total_attendee' =>   $totalattendee ?? 0,
                'present' => $presentCount ?? 0,
                'absent' => $absentCount ?? 0,
                'late' => $lateCount ?? 0,
                'punch_out' => $punchOutCount ?? 0,
                'leave_taken' => $LeaveCount ?? 0,
                'percentage' => $presentPercentage ?? 0
            ];

            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function monthlyReport($id)
    {
        try {
            $totalattendee = CompanyCandidate::where('company_id', $id)->count();
            $absentCount = CompanyCandidate::where('company_id', $id)
                                ->whereHas('candidate',function ($q) {
                                    $q->whereDoesntHave('monthlyattendances');
                                })->count();

            $presentCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas('candidate',function ($q) {
                                    $q->whereHas('attendances', function ($q) {
                                        $q->whereMonth('created_at', today()->format('m'))
                                            ->where('employee_status', 'Present');
                                    });
                                })->count();

            $lateCount = CompanyCandidate::where('company_id', $id)
                        ->whereHas('candidate',function ($q) {
                                $q->whereHas('attendances', function ($q) {
                                    $q->whereMonth('created_at', today()->format('m'))
                                        ->where('employee_status', 'Late');
                                });
                            })->count();

            $LeaveCount = CompanyCandidate::where('company_id', $id)
                        ->whereHas('candidate',function ($q) {
                                $q->whereHas('attendances', function ($q) {
                                    $q->whereMonth('created_at', today()->format('m'))
                                        ->where('employee_status', 'Leave');
                                });
                            })->count();

            $punchOutCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas('candidate',function ($q) {
                                    $q->whereHas('attendances', function ($q) {
                                        $q->whereMonth('created_at', today()->format('m'))
                                            ->where('employee_status', 'punchOut');
                                    });
                                })->count();

            $totalPresentToday = $presentCount ?? 0 +  $lateCount ?? 0;
            $presentPercentage = ($totalPresentToday*100)/$totalattendee;

            $data = [
                'total_attendee' => $totalattendee ?? 0,
                'present' => $presentCount ?? 0,
                'absent' => $absentCount ?? 0,
                'late' => $lateCount ?? 0,
                'punch_out' => $punchOutCount ?? 0,
                'leave_taken' => $LeaveCount ?? 0,
                'percentage' => $presentPercentage ?? 0
            ];

            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function yearlyReport($id)
    {
        try {
            $totalattendee = CompanyCandidate::where('company_id', $id)->count();
            $absentCount = CompanyCandidate::where('company_id', $id)
                            ->with('candidate', function ($q) {
                                $q->whereDoesntHave('yearlyattendances');
                            })->count();

            $presentCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas('candidate', function ($q) {
                                $q->whereHas('attendances', function ($q) {
                                    $q->whereYear('created_at', date('Y'))
                                        ->where('employee_status', 'Present');
                                });
                            })->count();

            $lateCount = CompanyCandidate::where('company_id', $id)
                        ->whereHas('candidate',function ($q) {
                            $q->whereHas('attendances', function ($q) {
                                $q->whereYear('created_at', date('Y'))
                                    ->where('employee_status', 'Late');
                            });
                        })->count();

            $LeaveCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas('candidate', function ($q) {
                                $q->whereHas('attendances', function ($q) {
                                    $q->whereYear('created_at', date('Y'))
                                        ->where('employee_status', 'Leave');
                                });
                            })->count();

            $punchOutCount = CompanyCandidate::where('company_id', $id)
                            ->whereHas('candidate', function ($q) {
                                $q->whereHas('attendances', function ($q) {
                                    $q->whereYear('created_at', date('Y'))
                                        ->where('employee_status', 'punchOut');
                                });
                            })->count();

            $totalPresentToday = $presentCount ?? 0 +  $lateCount ?? 0;
            $presentPercentage = ($totalPresentToday*100)/$totalattendee;

            $data = [
                'total_attendee' =>   $totalattendee ?? 0,
                'present' => $presentCount ?? 0,
                'absent' => $absentCount ?? 0,
                'late' => $lateCount ?? 0,
                'punch_out' => $punchOutCount ?? 0,
                'leave_taken' => $LeaveCount ?? 0,
                'percentage' => $presentPercentage ?? 0
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
