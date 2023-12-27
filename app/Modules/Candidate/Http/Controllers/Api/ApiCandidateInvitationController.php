<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Candidate\Http\Resources\CandidateInvitationResource;
use Candidate\Models\CompanyCandidate;
use Employer\Repositories\candidate\CandidateInterface;
use Files\Repositories\FileInterface;

class ApiCandidateInvitationController extends Controller
{
    protected $response, $candidate, $file;

    public function __construct(ResponseService $response, CandidateInterface $candidate, FileInterface $file)
    {
        $this->response = $response;
        $this->file = $file;
        $this->candidate = $candidate;
    }

    public function allCandidateInvitations()
    {
        try {
            $user_id = Auth()->id();
            // $invs = CompanyCandidate::where('candidate_id', $user_id)
            //             ->whereHas('invitation')
            //             ->with('invitation', function($q){
            //                 $q->where('status', 'Not-Approved')
            //                     ->with('company', 'employer')
            //                     ->latest();
            //             })
            //             ->get();


            // $invitations= collect();
            // foreach($invs as $inv){
            //     $invitations->push($inv->invitation);
            // }
           
            $invitations = Invitation::where('candidate_id', $user_id)
                            ->where('status', 'Not-Approved')
                            ->with('company', 'employer')
                            ->latest()
                            ->get();

            if ($invitations) {
                // dd($invitations);
                $candidateInvitations = CandidateInvitationResource::collection($invitations);
            }

            $data = [
                'candidateInvitations' => $candidateInvitations ?? [],
            ];
            return $this->response->responseSuccess($data, 'Successfully Fetched', 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function updateCandidateInvitation(Request $request, $invitation_id)
    {
        try {
            $user = auth()->user();
            $invitation = Invitation::where('id', $invitation_id)
                        ->where('candidate_id', $user->id)
                        ->first();
                        
            if ($invitation) {
                if ($invitation->status == 'Not-Approved' || $invitation->status == 'Decline') {
                    $invitation->status = $request->status;

                    if ($invitation->update() == true) {
                        if ($request->status == 'Approved') {
                            $verified_status = 'verified';
                            $status = 'Active';
                        } else {
                            $verified_status = 'Decline';
                            $status = 'Inactive';
                        }
                        $companycandidate = CompanyCandidate::updateOrCreate(
                            [
                                'company_id' => $invitation->company_id,
                                'candidate_id' => $user->id,
                            ],
                            [
                                'verified_status' => $verified_status,
                                'status' => $status,
                            ],
                        );

                        return $this->response->responseSuccessMsg('Successfully Created', 200);
                    }
                    return $this->response->responseError('Something Went Wrong While Updateing. Please Try Again.',400);
                }
                return $this->response->responseError('Can Not Update Approved Join Request.');
            }
            return $this->response->responseError('Join Request Not Found', 404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
