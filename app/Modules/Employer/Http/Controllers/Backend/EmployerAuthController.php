<?php

namespace Employer\Http\Controllers\Backend;

use App\GlobalServices\ResponseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Employer\Http\Requests\EmployerRegisterRequest;
use Employer\Http\Requests\ProfileUpdateRequest;
use Employer\Models\Employer;
use Employer\Repositories\auth\AuthEmployerInterface;
use Exception;
use Files\Repositories\FileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EmployerAuthController extends Controller
{

    protected $authEmployer, $response, $file;
    public function __construct(AuthEmployerInterface $authEmployer, ResponseService $response, FileInterface $file)
    {
        $this->authEmployer = $authEmployer;
        $this->response = $response;
        $this->file = $file;
    }

    public function register()
    {

        try {
            $auth = Auth::guard('web');
            if($auth->check()){
                return redirect()->route('employer.company.index');
            }
            return view('Employer::backend.login'); 
            Toastr::error('Something Went Wrong');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function registerSubmit(EmployerRegisterRequest $request)
    {
        try {
            $employersubmit = $this->authEmployer->register($request);
            if ($employersubmit) {
                $phone_no = $request->phone;
                Session::put('phone_no',$phone_no );
                Toastr::success('Successfully Registered. OTP Sent Successfully : '.$employersubmit['otp']);
                return redirect()->route('employer.verifyOtp');
            }
           Toastr::error('Something Went Wrong.');
           return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function verifyOtp()
    {
        try {
            if(Auth::guard('web')->check()){
                return redirect()->route('employer.company.index');
            }
            if(Session::has('phone_no')){
                $phone_no = Session::get('phone_no');
                return view('Employer::backend.otpVerification',compact('phone_no')); 
            }
            Toastr::error('Something Went Wrong.Please Try Again.');
            return redirect()->route('employer.register');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }


    public function verifyOtpSubmit(Request $request)
    {
        try {
            $userdata = $this->authEmployer->webVerifyOtp($request);
            if ($userdata) {
                // $request->session()->forget('phone_no');
                Toastr::success('Successfully Logged In.');
                return redirect()->route('employer.company.index');
            } 

            Toastr::error('Something Went Wrong.Please Try Again.');
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function passwordSubmit(Request $request)
    {
        try {
            $passwordsubmit = $this->authEmployer->passwordSubmit($request);
            if ($passwordsubmit) {
                $user = $passwordsubmit['user'];
                $token = $passwordsubmit['token'];
                return response(['user' => $user, 'token' => $token]);
            }
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }



    public function sendSms($phone_no, $message)
    {
        $args = http_build_query(array(
            'token' => 'v2_buVrQiRyZszCEerwMB60eerIY68.LJkj',
            'from'  => 'InfoAlert',
            'to'    => $phone_no,
            'text'  => $message
        ));

        $url = "http://api.sparrowsms.com/v2/sms/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status_code == 200) {
            return true;
        } else {
            throw new Exception('Error While Send OTP Via Sms.');
        }
    }

   

    public function logout()
    {
        try {
            Auth::guard('web')->logout();
            Toastr::success('Successfully Logged Out');
            return redirect()->route('employer.register');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }



    public function changePhone()
    {
        try {
            return view();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function changePhoneSubmit(Request $request)
    {
        try {
            $changePhone = $this->authEmployer->changePhone($request);
            if ($changePhone) {
                Toastr::success('Successfully changed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }



}
