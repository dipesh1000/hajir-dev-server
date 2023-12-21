<?php

namespace Employer\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Employer\Http\Requests\ProfileUpdateRequest;
use Employer\Models\Employer;
use Employer\Repositories\auth\AuthEmployerInterface;
use Files\Repositories\FileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{

    protected $authEmployer, $response, $file;
    public function __construct(AuthEmployerInterface $authEmployer, ResponseService $response, FileInterface $file)
    {
        $this->authEmployer = $authEmployer;
        $this->response = $response;
        $this->file = $file;
    }

    public function dashboard(){

        // try {
            return view('Employer::backend.dashboard');
        // } catch (\Exception $e) {
        //     Toastr::error($e->getMessage());
        //     return redirect()->back();
        // }
    }

    public function userProfile(){
        try {
            $user = Auth::guard('web')->user();
            return view('Employer::backend.profile.userprofile', compact('user'));
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function userProfileUpdate(ProfileUpdateRequest $request)
    {
        try {
            // dd($request->all());
            $user = User::where('id', Auth::guard('web')->id())->first();
            if ($user) {
                $user->firstname = $request->firstname;
                $user->email = $request->email;
                $user->address = $request->address;
                $user->dob = Carbon::parse($request->dob);
                if ($request->profile_image) {
                    $uploaded = $this->file->storeFile($request->profile_image);
                    $user->image_id = $uploaded->id;
                }
                if ($user->update()) {
                    $employer = Employer::where('user_id', $user->id)->first();
                    $employer->name = $request->firstname;
                    $employer->email = $request->email;

                    if ($request->profile_image) {
                        $employer->profile_id = $uploaded->id;
                    }
                    $employer->dob = Carbon::parse($request->dob);
                    if ($employer->update()) {
                        Toastr::success('Successfully Updated');
                        return redirect()->back();  
                    }
                    Toastr::success('Something went wrong while updating.');
                        return redirect()->back();
                }
                Toastr::success('Something went wrong while updating.');
                return redirect()->back();
            }
            Toastr::success('Employer Not Found');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

}
