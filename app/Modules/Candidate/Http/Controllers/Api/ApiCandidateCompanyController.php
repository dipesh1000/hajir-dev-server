<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Candidate\Http\Resources\CompanyResource;
use Candidate\Models\CompanyCandidate;
use Employer\Models\Company;

class ApiCandidateCompanyController extends Controller
{
    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function getCompaniesByCandidateID()
    {
        try {
            $user = auth()->user();
            $user = User::where('id', $user->id)
                        ->candidateCheck()
                        ->first();
            if ($user) {
                $candidateCompanies = CompanyCandidate::where('candidate_id', $user->id)
                    ->with('company')
                    ->get();

                $inactiveConpanies = $candidateCompanies->where('verified_status', 'Decline');
                $activeConpanies = $candidateCompanies->where('verified_status', 'verified')->where('status', 'Active');
                $inactiveConpanies = CompanyResource::collection($inactiveConpanies);
                $activeConpanies = CompanyResource::collection($activeConpanies);
            }
            $data = [
                'active_companies' => $activeConpanies ?? [],
                'inactive_companies' => $inactiveConpanies ?? [],
            ];

            return $this->response->responseSuccess($data, 'Successfully Fetched', 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function deleteCandidateCompany(Request $request, $company_id)
    {
        try {
            $user = auth()->user();
            $user = User::where('id', $user->id)
                        ->candidateCheck()
                        ->first();
            if ($user) {
                $candidateCompanies = CompanyCandidate::where('candidate_id', $user->id)
                        ->where('company_id',  $company_id)
                        ->first();
                if($candidateCompanies){
                    $candidateCompanies->status = "Inactive";
                    $candidateCompanies->verified_status = "Remove";
                    $candidateCompanies->update();
                    return $this->response->responseSuccessMsg('Company Deleted Successfully');
                }
                return $this->response->responseError("Candidate Company Not Found.",404);
            }
            return $this->response->responseError('Somethings Went Wrong.',400);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
