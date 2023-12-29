<?php

namespace Employer\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use Employer\Http\Resources\CompanyResource;
use Employer\Models\Company;
use Employer\Repositories\company\CompanyInterface;

class ApiCompanyController extends Controller
{

    protected $response, $company;
    public function __construct(ResponseService $response, CompanyInterface $company)
    {
        $this->response = $response;
        $this->company = $company;
    }

    public function generateCode($companyid){
        try {
            $company = Company::where('id', $companyid)->where('employer_id', auth()->user()->id)->first();
            if($company){
                if($company->code == 1){
                    $code = 'C-'.rand(0000, 9999);
                }
                $data = [
                    'code' => $code ?? null
                ];
                return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Company Not Found.",404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }



    public function changeStatus($companyid, Request $request){
        try{
            $company = Company::where('id', $companyid)
                        ->where('employer_id', auth()->user()->id)->first();
            if($company){
                $company->status = $request->status;
                if($company->update()){
                    return $this->response->responseSuccess("Successfully Updated",200);
                }
                return $this->response->responseError("Something went wrong please try again later",400);
            }
            return $this->response->responseError("Company Not Found.",404);
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }


    public function index(){
        try {
            $companies = $this->company->getAllCompanies();
            if($companies){
                $companies = CompanyResource::collection($companies);
            }
            $data = [
                'companies' => $companies ?? []
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function activeCompanies(){
        try {
            $user = auth()->user();
            $companies = $this->company->activeCompaniesByEmployerID($user->id);
            if($companies){
                $companies = CompanyResource::collection($companies);
            }
            $data = [
                'companies' => $companies ?? []
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function inactiveCompanies(){
        try {
            $user = auth()->user();
            $companies = $this->company->inactiveCompaniesByEmployerID($user->id);
            if($companies){
                $companies =CompanyResource::collection($companies);
            }
            $data = [
                'companies' => $companies ?? []
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function getCompanyByID($id){
        try {
            $company = Company::where('id', $id)
                ->where('employer_id', auth()->user()->id)->with(['companyBusinessLeave', 'specialLeaves', 'govLeaves'])
                ->first();
            if($company){
                $company = new CompanyResource($company);
                $data = [
                    'company' => $company ?? null
                ];
                return $this->response->responseSuccess($data, "Successfully Fetched.", 200);
            }
            return $this->response->responseError("Company Not Found.",404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function getCompaniesByEmployer(){
        try {
            $companies = $this->company->getCompaniesByEmployerId();
            if($companies){
                $active_companies = CompanyResource::collection($companies->where('status', 'Active'));
                $inactive_companies = CompanyResource::collection($companies->where('status', 'Inactive'));
            }
            $data = [
                'active_companies' => $active_companies ?? [],
                'inactive_companies' => $inactive_companies ?? []
            ];
            return $this->response->responseSuccess($data, "Success", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function store(CompanyStoreRequest $request)
    {
        try {
            $companystore = $this->company->store($request);
            if($companystore){
                return $this->response->responseSuccessMsg("Successfully Saved", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function update(CompanyUpdateRequest $request, $id)
    {
        try {
            $companystore = $this->company->update($request, $id);
            if($companystore){
                return $this->response->responseSuccessMsg("Successfully Updated", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function status(Request $request, $id)
    {
        try {
            $companystatus = $this->company->status($request, $id);
            if($companystatus){
                return $this->response->responseSuccessMsg("Successfully Updated", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function destroy( $id)
    {
        try {
            $company= $this->company->getCompanyByIdWithCandidates($id);
            if($company){
                $company->attendances()->delete();
                $company->govLeaves()->delete();
                $company->specialLeaves()->delete();
                $company->companyBusinessLeave()->delete();
                $company->companyCandidateLeaves()->delete();
                if($company->candidates->isNotEmpty()){
                    $company->candidates()->delete();
                }
                $company->forceDelete();
                return $this->response->responseSuccessMsg("Successfully Deleted", 200);
            }
            return $this->response->responseError("Company Not Found", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
