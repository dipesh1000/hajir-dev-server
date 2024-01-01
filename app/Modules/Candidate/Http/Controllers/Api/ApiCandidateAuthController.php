<?php

namespace Candidate\Http\Controllers\Api;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Candidate\Models\Candidate;
use App\Models\User;
use Candidate\Http\Requests\CandidateRegisterRequest;
use Candidate\Http\Requests\RegisterRequest;
use Candidate\Repositories\auth\AuthCandidateInterface;
use Candidate\Http\Requests\ProfileUpdateRequest;
use Candidate\Http\Resources\CandidateProfileResource;
use Carbon\Carbon;
use Files\Repositories\FileInterface;
use Illuminate\Http\Request;

class ApiCandidateAuthController extends Controller
{
    protected $authCandidate, $response, $file;
    public function __construct(AuthCandidateInterface $authCandidate, ResponseService $response, FileInterface $file)
    {
        $this->authCandidate = $authCandidate;
        $this->response = $response;
        $this->file = $file;
    }

    //candidate verification with otp
    public function register(CandidateRegisterRequest $request)
    {
        try {
            $candidatesubmit = $this->authCandidate->register($request);
            if ($candidatesubmit) {
                $data = [
                    'otp' => $candidatesubmit['otp'] ?? null,
                    'token' => $candidatesubmit['token'] ?? null
                ];
                return $this->response->responseSuccess($data, "Successfully Registered. OTP Sent Successfull", 200);
            }
            return $this->response->responseError("Something went wrong",400);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $userdata = $this->authCandidate->verifyOtp($request);
            if ($userdata) {
                $data = [
                    'user' => new UserResource($userdata['user']),
                    'token' => $userdata['token']
                ];
                return $this->response->responseSuccess($data,"Successfully Verified", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function passwordSubmit(Request $request)
    {
        try {
            $passwordsubmit = $this->authCandidate->passwordSubmit($request);
            if ($passwordsubmit) {
                $user = $passwordsubmit['user'];
                $token = $passwordsubmit['token'];
                return response(['user' => $user, 'token' => $token]);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function getProfile()
    {
        try {
            $user = User::where('id', auth()->user()->id)->with('profileImage')->candidateCheck()->first();
            if ($user) {
                $data = new CandidateProfileResource($user);
                return $this->response->responseSuccess($data, "Successfully Fetched", 200);
            }
            return $this->response->responseError("User Not Found.",404);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function profileUpdate(ProfileUpdateRequest $request)
    {
        try {
            $user = User::where('id', auth()->user()->id)->candidateCheck()->first();
            if ($user) {
                $user->name = $request->name;
                // $user->lastname = $request->lastname;
                $user->email = $request->email;
                $user->dob = $request->dob;
                $user->address = $request->address;
                if ($request->uploadfile) {
                    $uploaded = $this->file->storeFile($request->uploadfile);
                    $user->image_id = $uploaded->id;
                }
                if ($user->update()) {
                    return $this->response->responseSuccessMsg("Successfully Updated.",200);
                }
                return $this->response->responseError("Something went wrong while updating candidate.",400);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }


    public function logout()
    {
        try {
            $user = auth()->user()->token();
            $user->revoke();
            return $this->response->responseSuccessMsg("Successfully Logged Out.",200);
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }

    public function changePhone(Request $request)
    {
        try {
            $changePhone = $this->authCandidate->changePhone($request);
            if ($changePhone) {
                $data = [
                    'otp' => $changePhone['otp']
                ];
                return $this->response->responseSuccess($data, "Successfully Changed", 200);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
    
    public function updateDeviceToken(Request $request)
    {
        try {
            $user = User::where('id', auth()->user()->id)->candidateCheck()->first();
            if ($user && $request->device_token) {
                $user->device_token = $request->device_token;
                if ($user->update()) {
                    return $this->response->responseSuccessMsg("Successfully Updated.",200);
                }
                return $this->response->responseError("Something went wrong while updating.",400);
            }
        } catch (\Exception $e) {
            return $this->response->responseError($e->getMessage());
        }
    }
}