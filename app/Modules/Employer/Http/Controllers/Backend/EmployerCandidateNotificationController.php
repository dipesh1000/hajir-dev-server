<?php

namespace Employer\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\GlobalServices\ResponseService;
use App\Models\User;
use App\Notifications\EmployerCandidateNotification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class EmployerCandidateNotificationController extends Controller
{
    protected $response;
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function notificationMessageSent(Request $request,$company_id, $candidate_id){
        try{
            $employer = auth()->user();
            $candidate = User::where('id', $candidate_id)->first();
            $details = [
                'message' => $request->message,
                'actionText' => 'View My Site',
                'actionURL' => url('/'),
                'candidate_id' => $candidate->id,
                'company_id' => $company_id,
                'employer_phone' => $employer->phone,
                'employer_id' =>   $employer->id
            ];
            Notification::send($candidate, new EmployerCandidateNotification($details));

            Toastr::success("Message Sent Successfully.");
            return redirect()->back();
        }catch(\Exception $e){
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }



}
