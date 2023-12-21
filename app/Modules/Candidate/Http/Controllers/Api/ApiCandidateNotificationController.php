<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiCandidateNotificationController extends Controller
{

    protected $response;

    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    public function notifications()
    {
        try {
            $user = auth()->user();
            $notifications = [];
            foreach ($user->unreadNotifications as $notification) {
                $notifications[] = [
                    'id' => $notification->id,
                    'message' => $notification->data['message'],
                    'employer_id' => $notification->data['employer_id'],
                    'employer_phone' => $notification->data['employer_phone'],
                ];
            }
            $data = [
               'notifications' => $notifications
            ];
            return $this->response->responseSuccess($data, "Successfully Fetched", 200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }



    public function markNotificationRead()
    {
        try {
            auth()->user()->unreadNotifications->markAsRead();
            return $this->response->responseSuccessMsg("Successfully Marked As Read",200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function markSingleNotificationRead($id)
    {
        try {
            auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
            return $this->response->responseSuccessMsg("Successfully Marked As Read",200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}
