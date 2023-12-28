<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Candidate\Models\Leave;
use Candidate\Http\Resources\CandidateLeaveResource;
use Candidate\Models\CompanyCandidate;
use Employer\Repositories\candidate\CandidateInterface;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Employer\Http\Resources\LeavetypeResource;
use Employer\Models\Company;
use Employer\Models\LeaveType;
use Files\Repositories\FileInterface;


class ApiCandidateLeaveController extends Controller
{
    protected $response, $candidate, $file;

    public function __construct(ResponseService $response, CandidateInterface $candidate, FileInterface $file)
    {
        $this->response = $response;
        $this->file = $file;
        $this->candidate = $candidate;
    }

    public function getLeaveTypes($company_id)
    {
        try {
            $companyCandidate = CompanyCandidate::where('candidate_id', auth()->id())
                ->where('company_id', $company_id)->with('company')->first();
            if ($companyCandidate) {
                $company = $companyCandidate->company;
                $leaveTypes = LeaveType::get();

                if ($leaveTypes) {
                    $leavetypes = LeavetypeResource::collection($leaveTypes);
                }
                $data = [
                    'leaveTypes' => $leavetypes ?? [],
                    'avaliable_leave' => [
                        'sick_leave' => $company->leave_duration??null,
                        'casual_leave' => $companyCandidate->casual_leave??null,
                    ]
                ];
                return $this->response->responseSuccess($data, "Successfully Fetched", 200);
            }

            return $this->response->responseError('Candidate Not Found.', 400);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function allCandidateLeave($company_id)
    {
        try {
            $user_id = auth()->id();
            $userLeaves = Leave::where('candidate_id', $user_id)
                        ->where('company_id', $company_id)
                        ->with(['LeaveType', 'document'])->get();
            if ($userLeaves) {
                $approveLeaves = $userLeaves->where('status', 'Approved');
                $unapproveLeaves = $userLeaves->where('status', 'Pending');
                $rejectedLeaves = $userLeaves->where('status', 'Rejected');

                $candidateLeaves = [    
                    'approved_leaves' => CandidateLeaveResource::collection($approveLeaves),
                    'unapproved_leaves' => CandidateLeaveResource::collection($unapproveLeaves),
                    'rejected_leaves' => CandidateLeaveResource::collection($rejectedLeaves),
                ];
            }

            $data = $candidateLeaves ?? [];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }



    private function getSundays()
    {
        return new \DatePeriod(
            Carbon::parse("first sunday of this month"),
            CarbonInterval::week(),
            Carbon::parse("first sunday of next month")
        );
    }


    public function storeCandidateLeave(Request $request, $company_id)
    {
        try {
            $user = auth()->user();
            $leave = new Leave();
            $leave->candidate_id = $user->id;
            $leave->start_date = Carbon::parse($request->start_date);
            $leave->end_date = Carbon::parse($request->end_date);
            $leave->remarks = $request->remarks;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->type = $request->type;
            $leave->company_id = $company_id;
            if ($request->has('document')) {
                $uploadFile = $this->file->storeFile($request->document);
                if ($uploadFile) {
                    $leave->document_id = $uploadFile->id;
                }
            }
            if ($leave->save() == true) {
                return $this->response->responseSuccessMsg("Successfully Saved", 200);
            }
            return $this->response->responseError("Something Went Wrong While Saving. Please Try Again.", 400);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function updateCandidateLeave(Request $request, $company_id, $leave_id)
    {
        try {
            $user = auth()->user();
            $leave = Leave::where('company_id', $company_id)->where('id', $leave_id)->first();
            if ($leave) {
                if ($leave->approved == 0) {
                    $leave->candidate_id = $user->candidate->id;
                    $leave->user_id = $user->id;
                    $leave->start_date = Carbon::parse($request->start_date);
                    $leave->end_date = Carbon::parse($request->end_date);
                    $leave->remarks = $request->remarks;
                    $leave->leave_type_id = $request->leave_type_id;
                    $leave->status = $request->status;
                    $leave->company_id = $request->company_id;
                    if ($request->has('document')) {
                        $uploadFile = $this->file->storeFile($request->document);
                        if ($uploadFile) {
                            $leave->document_id = $uploadFile->id;
                        }
                    }
                    if ($leave->update() == true) {
                        return $this->response->responseSuccessMsg("Successfully Saved", 200);
                    }
                    return $this->response->responseError("Something Went Wrong While Updateing. Please Try Again.", 400);
                }
                return $this->response->responseError("Can Not Update Approved Leave Request.", 404);
            }
            return $this->response->responseError("Leave Record Not Found", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }




    public function deleteCandidateLeave($company_id, $leave_id)
    {
        try {
            $leave = Leave::where('company_id', $company_id)->where('id', $leave_id)->first();
            if ($leave) {
                $leave->delete();
                return $this->response->responseSuccessMsg("Successfully Deleted", 200);
            }
            return $this->response->responseError("Leave Record Not Found", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
