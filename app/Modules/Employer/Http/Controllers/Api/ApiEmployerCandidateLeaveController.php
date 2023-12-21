<?php

namespace Employer\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use Employer\Http\Resources\EmployerCandidateLeaveDetailsResource;
use Candidate\Models\Attendance;
use Candidate\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Employer\Http\Resources\EmployerCandidateLeaveResource;

class ApiEmployerCandidateLeaveController extends Controller
{

    protected $response;

    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function all($companyid = null){
        try{
            $leaves = Leave::where('company_id', $companyid)->with(['candidate','LeaveType'])->get();
            if($leaves){
                $candidates = EmployerCandidateLeaveResource::collection($leaves);
            }
            $data = [
                'candidates' => $candidates ?? []
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

    public function leaveDetail($id){
        try{
            $leave = Leave::where('id', $id)->with(['candidate','LeaveType'])->first();
            if($leave){
                $leavedetail = new EmployerCandidateLeaveDetailsResource($leave);
            }
            $data = [
                'leavedetail' => $leavedetail ?? null
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }

    function getDatesFromRange($start, $end, $format='Y-m-d') {
        return array_map(function($timestamp) use($format) {
            return date($format, $timestamp);
        },
        range(strtotime($start) + ($start < $end ? 4000 : 8000), strtotime($end) + ($start < $end ? 8000 : 4000), 86400));
    }


    public function changeStatus($id, Request $request){

        try{
            $leave = Leave::where('id', $id)->with(['candidate', 'company', 'leaveType'])->first();
            if($leave){
                $leave->pay_status = $request['pay_status']??'Unpaid';
                $leave->status = $request['status']??'Pending';
                if($leave->update() == true){
                    if( $leave->start_date != $leave->end_date){
                        $dateRange = $this->getDatesFromRange($leave->start_date, $leave->end_date);
                        foreach($dateRange as $date){
                            $attendance = new Attendance();
                            $attendance->candidate_id = $leave->candidate->id;
                            $attendance->company_id = $leave->company->id;
                            $attendance->candidate_id = $leave->candidate->id;
                            $attendance->leave_type_id = $leave->leaveType->id;
                            $attendance->employee_status = "Leave";
                            $attendance->leave_id = $leave->id;
                            $attendance->created_at = Carbon::parse($date);
                            $attendance->save();
                        }
                    }else{
                        $attendance = new Attendance();
                        $attendance->candidate_id = $leave->candidate->id;
                        $attendance->company_id = $leave->company->id;
                        $attendance->candidate_id = $leave->candidate->id;
                        $attendance->leave_type_id = $leave->leaveType->id;
                        $attendance->employee_status = "Leave";
                        $attendance->leave_id = $leave->id;
                        $attendance->created_at = $leave->start_date;
                        $attendance->save();
                    }
                  
                    return $this->response->responseSuccessMsg("Successfully Updated",200);
                }
                return $this->response->responseError("Something went wrong while updating leave",400);
            }
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }


}
