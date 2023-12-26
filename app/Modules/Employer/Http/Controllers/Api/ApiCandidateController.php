<?php

namespace Employer\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Candidate\Http\Requests\CandidateStoreRequest;
use Candidate\Models\CompanyCandidate;
use Candidate\Models\Leave;
use Employer\Http\Resources\CompanyCandidateAllResource;
use Employer\Http\Resources\CompanyCandidateResource;
use Employer\Repositories\candidate\CandidateInterface;
use Employer\Http\Resources\CompanyResource;

class ApiCandidateController extends Controller
{
    protected $response, $candidate;

    public function __construct(ResponseService $response, CandidateInterface $candidate)
    {
        $this->response = $response;
        $this->candidate = $candidate;
    }

    public function store(CandidateStoreRequest $request, $id)
    {
        try {
           
            $candidate = $this->candidate->store($request, $id);
            if ($candidate) {
                return $this->response->responseSuccessMsg("Successfully Saved.", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function update(Request $request, $company_id, $candidate_id)
    {
        try {
            $candidate = $this->candidate->update($request, $company_id, $candidate_id);
            if ($candidate) {
                return $this->response->responseSuccessMsg("Successfully Updated.", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function getCompanySingleCandidate($company_id, $candidate_id)
    {
        try {
            $companyCandidate = CompanyCandidate::where('company_id', $company_id)->where('candidate_id', $candidate_id)->first();
            if ($companyCandidate) {
                $data = new CompanyCandidateAllResource($companyCandidate);
                return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Candidate Not Found.", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function getCandidatesByCompany($id)
    {
        try {
            $conmanyCandidates = CompanyCandidate::where('company_id', $id)->get();
            if ($conmanyCandidates) {
                $activeCandidates = CompanyCandidateResource::collection($conmanyCandidates->where('status', 'Active'));
                $inactiveCandidates = CompanyCandidateResource::collection($conmanyCandidates->where('status', 'Inactive'));
            }
            $data = [
                'active_candidates' => $activeCandidates ?? [],
                'inactive_candidates' => $inactiveCandidates ?? []
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function getCompaniesByCandidateID($id)
    {
        try {
            $user = User::where('id', $id)->where('type', 'candidate')->with(['userCompanies'])->first();
            if ($user) {
                $data = [
                    'companies' => CompanyResource::collection($user->userCompanies)
                ];
                return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Candidate Not Found.", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function changeStatus(Request $request, $company_id, $candidate_id)
    {
        try {
            $companyCandidate = CompanyCandidate::where('company_id', $company_id)->where('candidate_id', $candidate_id)->first();
            if ($companyCandidate) {
                $companyCandidate->status = $request->status;
                if ($companyCandidate->update()) {
                    return $this->response->responseSuccessMsg("Successfully Updated.", 200);
                }
                return $this->response->responseError("Something went wrong please try again later.", 400);
            }
            return $this->response->responseError("Candidate Not Found.", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function destroy($company_id, $candidate_id)
    {
        try {
            $companyCandidate = CompanyCandidate::where('company_id', $company_id)->where('candidate_id', $candidate_id)->first();
            if ($companyCandidate) {
                $companyCandidate->attendaces()->delete();
                Leave::where('company_id', $company_id)
                    ->where('candidate_id', $candidate_id)->delete();
                $companyCandidate->delete();
                return $this->response->responseSuccessMsg("Successfully Deleted.", 200);
            }
            return $this->response->responseError("Candidate Not Found.", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function storeApprover(Request $request, $company_id, $candidate_id)
    {
        try {

            $companyCandidate = CompanyCandidate::where('company_id', $company_id)->where('candidate_id', $candidate_id)->first();

            if ($companyCandidate) {
                $companyCandidate->is_approver = 1;
                if ($companyCandidate->update()) {
                    return $this->response->responseSuccessMsg("Successfully Added.", 200);
                }
                return $this->response->responseError("Something went wrong please try again later.", 400);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function deleteApprover(Request $request, $company_id, $candidate_id)
    {
        try {

            $companyCandidate = CompanyCandidate::where('company_id', $company_id)->where('candidate_id', $candidate_id)->first();
            if ($companyCandidate) {
                $companyCandidate->is_approver = 0;
                if ($companyCandidate->update()) {
                    return $this->response->responseSuccessMsg("Successfully Deleted.", 200);
                }
                return $this->response->responseError("Something went wrong please try again later.", 400);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function getApprovers(Request $request, $company_id)
    {
        try {

            $companyCandidates = CompanyCandidate::where('company_id', $company_id)->where('is_approver', 1)->get();
            if ($companyCandidates) {
                $data = CompanyCandidateResource::collection($companyCandidates);

                return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Something went wrong please try again later.", 400);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
