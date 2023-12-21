<?php

namespace Employer\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\GlobalServices\ResponseService;
use App\Models\User;
use App\Notifications\EmployerCandidateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ApiEmployerCandidateNotificationController extends Controller
{
    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function notificationSent(Request $request,$companyid, $candidateid){
        try{
            $employer = auth()->user();
            $candidate = User::where('id', $candidateid)->first();
            $details = [
                'message' => $request->message,
                'actionText' => 'View My Site',
                'actionURL' => url('/'),
                'candidate_id' => $candidate->id,
                'company_id' => $companyid,
                'employer_phone' => $employer->phone,
                'employer_id' =>   $employer->id
            ];
            Notification::send($candidate, new EmployerCandidateNotification($details));

            return $this->response->responseSuccessMsg("Successfully Sent",200);
        }catch(\Exception $e){
            return $this->response->responseError($e->getMessage());
        }
    }



}
