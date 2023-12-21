<?php

namespace Employer\Repositories\company;

use Carbon\Carbon;
use Employer\Models\Company;
use Employer\Repositories\company\CompanyInterface;
use Exception;
use Files\Repositories\FileInterface;
use Illuminate\Support\Facades\Auth;


class CompanyRepository implements CompanyInterface
{

    protected $file = null;
    public function __construct(FileInterface $file)
    {
        $this->file = $file;
    }


    public function getAllCompanies()
    {
        $companies = Company::latest()->get();
        return $companies;
    }

    public function activeCompaniesByEmployerID($id){
        $companies = Company::where('employer_id', $id)->active()->latest()->get();
        return $companies;
    }


    public function inactiveCompaniesByEmployerID($id){
        $companies = Company::where('employer_id', $id)->inactive()->latest()->get();
        return $companies;
    }


    public function getCompanyById($id)
    {
        $company = Company::where('id', $id)->first();
        if($company){
            return $company;
        }
        throw new Exception("Company Not Found.",404);

    }

    public function getCompanyByIdWithCandidates($id)
    {
        $company = Company::where('id', $id)->with('candidates')->first();
        if($company){
            return $company;
        }
        throw new Exception("Company Not Found.",404);
    }

    public function getCompaniesByEmployerId()
    {
        $user = auth()->user();
        $companies = Company::where('employer_id', $user->id)
                ->withCount('candidates')
                ->latest()->get();
        return $companies;
    }


    public function store($request) 
    {
        $company = new Company();
        $company->name = $request->name;
        $company->code = $request->code;
        $company->calculation_type = $request->calculation_type;
        // $company->email = $request->email;
        $company->phone = $request->phone;
        $company->network_ip = $request->network_ip;
        $company->address = $request->address;
        // $company->duty_time = $request->duty_time;
        // $company->overtime = $request->overtime;
        // $company->salary_amount = $request->salary_amount;
        // $company->salary_type = $request->salary_type;
        // $company->working_days = $request->working_days;
        $company->office_hour = $request->office_hour;

        $company->leave_duration_type = $request->leave_duration_type;
        // $company->probation_duration_type = $request->probation_duration_type;
        $company->leave_duration = (int)$request->leave_duration;
        $company->probation_period = (int)$request->probation_period;

        $company->employer_id = Auth::id();
        if ($company->save()) {
            $company->businessLeaves()->attach($request->business_leave);
            foreach ($request->government_leavedates as $leavedate) {
                $company->govLeaves()->create([
                    'leave_date' => isset($leavedate['leave_date']) ?  Carbon::parse($leavedate['leave_date']) : null
                ]);
            }
            foreach ($request->special_leavedates as $leavedate) {
                $company->specialLeaves()->create([
                    'leave_date' => isset($leavedate['leave_date']) ?  Carbon::parse($leavedate['leave_date']) : null
                ]);
            }
            return $company;
        }
        throw new Exception("Something went wrong please try again later.",400);
    }


    public function update($request, $id)
    {
        $company = Company::where('id', $id)->first();
        if ($company) {
        $company->name = $request->name;
        $company->code = $request->code;

        $company->calculation_type = $request->calculation_type;
        // $company->email = $request->email;
        $company->phone = $request->phone;
        $company->network_ip = $request->network_ip;
        $company->address = $request->address;
        // $company->duty_time = $request->duty_time;
        // $company->overtime = $request->overtime;
        // $company->salary_amount = $request->salary_amount;
        // $company->salary_type = $request->salary_type;
        // $company->working_days = $request->working_days;
        $company->office_hour = $request->office_hour;
        $company->leave_duration_type = $request->leave_duration_type;
        // $company->probation_duration_type = $request->probation_duration_type;
        $company->leave_duration = (int)$request->leave_duration;
        $company->probation_period = (int)$request->probation_period;

        $company->employer_id = Auth::id();
            if ($company->update()) {
                $company->businessLeaves()->sync($request->business_leave);
                $company->govLeaves()->delete();
                foreach ($request->government_leavedates as $leavedate) {
                    $company->govLeaves()->create([
                        'leave_date' => isset($leavedate['leave_date']) ?  Carbon::parse($leavedate['leave_date']) : null
                    ]);
                }
                $company->specialLeaves()->delete();
                foreach ($request->special_leavedates as $leavedate) {
                    $company->specialLeaves()->create([
                        'leave_date' => isset($leavedate['leave_date']) ?  Carbon::parse($leavedate['leave_date']) : null
                    ]);
                }
                return $company;
            }
            throw new Exception("Something went wrong please try again later",400);
        }
        throw new Exception("Company Not Found.",404);
    }


    public function status($request, $id)
    {
        $company = Company::where('id', $id)->first();
        if ($company) {
            $company->status = $request->status;
            if ($company->update()) {
                return $company;
            }
            throw new Exception("Something went wrong please try again later.",400);
        }
        throw new Exception("Company Not Found.",404);
    }



    public function storeWebEmployer($request) 
    {
        // dd($request->all());
        $company = new Company();
        $company->name = $request->name;
        $company->code = $request->code;
        $company->calculation_type = $request->calculation_type;
        $company->phone = $request->phone;
        $company->network_ip = $request->network_ip;
        $company->address = $request->address;
        $company->office_hour = $request->office_hour;
        $company->leave_duration_type = $request->leave_duration_type;
        $company->leave_duration = (int)$request->leave_duration;
        $company->probation_period = (int)$request->probation_period;
        $company->employer_id = Auth::guard('web')->id();

        if ($company->save()) {
            $company->businessLeaves()->attach($request->business_leave);
            $government_leavedates = explode(',',$request->government_leavedates);
            $special_leavedates = explode(',',$request->special_leavedates);

            foreach ($government_leavedates as $leavedate) {
                $company->govLeaves()->create([
                    'leave_date' => isset($leavedate) ?  Carbon::parse($leavedate) : null
                ]);
            }

            foreach ($special_leavedates as $leavedate) {
                $company->specialLeaves()->create([
                    'leave_date' => isset($leavedate) ?  Carbon::parse($leavedate) : null
                ]);
            }
            // dd($company);
            return true;
        }
        throw new Exception("Something went wrong please try again later.",400);
    }

    public function updateBySlugWebEmployer($request, $slug)
    {
        // dd($request->all());
        $company = Company::where('slug', $slug)->first();
        if ($company) {
        $company->name = $request->name;
        $company->code = $request->code;

        $company->calculation_type = $request->calculation_type;
        // $company->email = $request->email;
        $company->phone = $request->phone;
        $company->network_ip = $request->network_ip;
        $company->address = $request->address;
        // $company->duty_time = $request->duty_time;
        // $company->overtime = $request->overtime;
        // $company->salary_amount = $request->salary_amount;
        // $company->salary_type = $request->salary_type;
        // $company->working_days = $request->working_days;
        $company->office_hour = $request->office_hour;
        $company->leave_duration_type = $request->leave_duration_type;
        // $company->probation_duration_type = $request->probation_duration_type;
        $company->leave_duration = (int)$request->leave_duration;
        $company->probation_period = (int)$request->probation_period;

        $company->employer_id = Auth::guard('web')->id();
            if ($company->update()) {
                // dd($request->business_leave);
                $company->businessLeaves()->sync($request->business_leave);
                $company->govLeaves()->delete();
                $government_leavedates = explode(',',$request->government_leavedates);
              
                $special_leavedates = explode(',',$request->special_leavedates);
                foreach ($government_leavedates as $leavedate) {
                    
                    $company->govLeaves()->create([
                        'leave_date' => isset($leavedate) ?  Carbon::parse($leavedate) : null
                    ]);
                }
                $company->specialLeaves()->delete();
                foreach ($special_leavedates as $specialLeaveDate) {
                    // @dd($specialLeaveDate);
                    $company->specialLeaves()->create([
                        'leave_date' => isset($leavedate) ? Carbon::parse($specialLeaveDate) : null
                    ]);
                }
                return true;
            }
            throw new Exception("Something went wrong please try again later",400);
        }
        throw new Exception("Company Not Found.",404);
    }
}
