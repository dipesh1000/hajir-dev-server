<?php

namespace Employer\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Employer\Http\Resources\EmployerCandidateLeaveDetailsResource;
use Candidate\Models\Attendance;
use Candidate\Models\Leave;
use Carbon\Carbon;
use Employer\Http\Resources\EmployerCandidateLeaveResource;

class EmployerCandidateLeaveController extends Controller
{

    protected $response;

    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function all($companyid = null){
        try{
            $leaves = Leave::with(['candidate','LeaveType'])->paginate(12);
            return view('Employer::backend.inbox.index',compact('leaves'));
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
            
        }
    }

    public function leaveDetail($id){
        try{
            $leave = Leave::where('id', $id)->with(['candidate','LeaveType'])->first();
            return view('Employer::backend.inbox.show',compact('leave'));
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    function getDatesFromRange($start, $end, $format='Y-m-d') {
        return array_map(function($timestamp) use($format) {
            return date($format, $timestamp);
        },
        range(strtotime($start) + ($start < $end ? 4000 : 8000), strtotime($end) + ($start < $end ? 8000 : 4000), 86400));
    }


    public function leaveApproval($id){
        try{
            $leave = Leave::where('id', $id)->with(['candidate', 'company', 'leaveType'])->first();
            if($leave){
                $leave->approved = 1;
                if($leave->update() == true){
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
                    return $this->response->responseSuccessMsg("Successfully Updated",200);
                }
                return $this->response->responseError("Something went wrong while updating leave",400);
            }
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }


   


}
