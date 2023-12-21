<?php

namespace SuperAdmin\Http\Controllers\Backend;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SuperAdminAuthController extends Controller
{
    public function login(){
        try{
        return view('SuperAdmin::backend.login');

        }catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }
   
    public function loginSubmit(Request $request){
        // try {
            $user = User::where('email', $request->email)->first();
            if ($user) {

                $roles = $user->getRoleNames();
                if ($roles->contains('superadmin' || 'admin')) {
                    $credentials = [
                        'email' => $request->email,
                        'password' => $request->password,
                    ];
                    if (Auth::guard('web')->attempt($credentials)) {
                        Toastr::success('Successfully Logged In.');
                        return redirect()->route('backend.dashboard');
                    }
                    Toastr::error("Credentails Don't Match!!");
                    return redirect()->back()->with('error', "Something went wrong")->withInput($request->input());
                   
                }
                Toastr::error("You Do Not Have Permission To LogIn.");
                return redirect()->back()->with('error', "You Do Not Have Permission To LogIn.")->withInput($request->input());;
            }
            Toastr::error("User Not Found.");
            return redirect()->back()->with('error', "User not found")->withInput($request->input());
        // } catch (\Exception $e) {
        //     Toastr::error($e->getMessage());
        //     return redirect()->back();
        // }
    }

    public function logout(){
        try{
            Auth::logout();
            return redirect()->route('backend.login');
        }catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }
}
