<?php

namespace SuperAdmin\Http\Controllers\Backend;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Employer\Models\Company;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    
    public function dashboard()
    {
        try{
            $companies = Company::query();
            $latestCompanies = $companies->latest()->limit(10)->get();
            $companiesCount = $companies->count();
            $candidatesCount= User::candidateCheck()->count();
            $employersCount= 1;
            return view('SuperAdmin::backend.dashboard',
                    compact('companiesCount','latestCompanies','employersCount','candidatesCount'));
        }catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function profile(){
        try{
            $user = Auth::user();
            return view('SuperAdmin::backend.profile.userprofile',compact('user'));
        }catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

}
