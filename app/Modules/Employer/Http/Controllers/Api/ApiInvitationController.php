<?php

namespace Employer\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Candidate\Models\CompanyCandidate;
use Employer\Http\Resources\CompanyCandidateResource;
use Employer\Http\Resources\InvitationResource;
use Employer\Models\Company;
use App\Notifications\FirebaseNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiInvitationController extends Controller
{

    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function index($company_id)
    {
        try {
            $invitations = Invitation::where('company_id', $company_id)
                            ->with('candidate')
                            ->latest()->get();
            $data = [
                'invitations' => InvitationResource::collection($invitations)
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function allCandidates($company_id)
    {
        try {
            $company = Company::where('id', $company_id)->first();
            if ($company) {
                $invitationCandidates = CompanyCandidate::where('company_id', $company_id)
                ->where(function($q){
                    $q->where('verified_status', 'not_verified')
                    ->orWhere('status', 'Inactive');
                })->get();
                if ($invitationCandidates ) {
                    $candidates = CompanyCandidateResource::collection($invitationCandidates);
                }
                $data = [
                    'candidates' => $candidates ?? []
                ];
                return $this->response->responseSuccess($data, "Successfully Fetched", 200);
            }
            return $this->response->responseError("Company Not Found.",404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function store(Request $request, $company_id)
    {
        try {
           
            $canidate = User::where('id', $request->candidate_id)->first();
            if($canidate){
                $user_id = Auth()->id();
                $invitation = Invitation::where('company_id',$company_id)
                                    ->where('candidate_id',$request->candidate_id)
                                    ->first();
                if($invitation && $invitation->status != "Decline"){
                    return $this->response->responseSuccessMsg("Invitation Already Sent.", 200);
                }
                $newinvitation = new Invitation();
                $newinvitation->employer_id = $user_id;
                $newinvitation->candidate_id = $request->candidate_id;
                $newinvitation->status = $request->status;
                $newinvitation->company_id = $company_id;
                if ($newinvitation->save() == true) {
                    CompanyCandidate::updateOrCreate([
                        'company_id' => $company_id,
                        'candidate_id' => $request->candidate_id
                    ], [
                        'invitation_id' => $newinvitation->id
                    ]);

                    $company = Company::where('id', $company_id)->first();
                    $data = [
                        'type' => 'invitation',
                        'type_id' => $newinvitation->id,
                        'title' => 'New Invitation',
                        'body' => 'You have received an invitation from ' .$company->name,
                    ];
                                                
                    return $this->response->responseSuccessMsg("Successfully Saved", 200);
                }
                return $this->response->responseError("Something Went Wrong While Saving. Please Try Again.",400);
            }
            return $this->response->responseError("Candidate Not Found.",404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function update(Request $request, $company_id, $invitation_id)
    {
        try {
            $user_id = Auth()->id();
            $invitation = Invitation::where('company_id', $company_id)->where('id', $invitation_id)->first();
            if ($invitation) {
                $invitation->employer_id = $user_id;
                $invitation->candidate_id = $request->candidate_id;
                $invitation->status = $request->status;
                $invitation->company_id = $company_id;
                if ($invitation->update() == true) {
                    return $this->response->responseSuccessMsg("Successfully Updated.", 200);
                }
                return $this->response->responseError("Something Went Wrong While Updating. Please Try Again.",400);
            }
            return $this->response->responseError("Invitation Type Not Found", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function destroy($company_id, $invitation_id)
    {
        try {
            $invitation = Invitation::where('company_id', $company_id)->where('id', $invitation_id)->first();
            if ($invitation) {
                $invitation->delete();
                return $this->response->responseSuccessMsg("Successfully Deleted", 200);
            }
            return $this->response->responseError("Invitation Record Not Found", 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
