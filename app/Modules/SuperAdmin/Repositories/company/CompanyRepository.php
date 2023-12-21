<?php

namespace SuperAdmin\Repositories\company;

use Carbon\Carbon;
use Employer\Models\Company;
use SuperAdmin\Repositories\company\CompanyInterface;
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


    public function getCompanyById($id)
    {
        $company = Company::where('id', $id)->first();
        return $company;
    }


    public function getCompanyByIdWithCandidates($id)
    {
        $company = Company::where('id', $id)->with('candidates')->first();
        return $company;
    }

    public function getCompaniesByEmployerId($id)
    {
        $companies = Company::where('employer_id', $id)->latest()->get();
        return $companies;
    }


    public function store($request)
    {


        $company = new Company();
        $company->name = $request->name;
        if ($request->code) {
            $company->code = $request->code;
        } else {
            $company->code = 'C-' . rand(0, 9999);
        }
        $company->calculation_type = $request->calculation_type;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->network_ip = $request->network_ip;
        $company->address = $request->address;
        $company->salary_type = $request->salary_type;
        $company->working_days = $request->working_days;
        $company->office_hour_start = Carbon::parse($request->office_hour_start)->format('H:i');
        $company->office_hour_end = Carbon::parse($request->office_hour_start)->format('H:i');
        $company->employer_id = Auth::id();
        if ($company->save()) {
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
        throw new Exception("Something went wrong please try again later");
    }


    public function update($request, $id)
    {

        $company = Company::where('id', $id)->first();
        if ($company) {
            $company->name = $request->name;
            if ($request->code) {
                $company->code = $request->code;
            } else {
                $company->code = 'C-' . rand(0, 9999);
            }
            $company->calculation_type = $request->calculation_type;
            $company->email = $request->email;
            $company->phone = $request->phone;
            $company->network_ip = $request->network_ip;
            $company->address = $request->address;
            $company->salary_type = $request->salary_type;
            $company->working_days = $request->working_days;
            $company->office_hour_start = Carbon::parse($request->office_hour_start)->format('H:i');
            $company->office_hour_end = Carbon::parse($request->office_hour_start)->format('H:i');
            $company->employer_id = Auth::id();
            if ($company->update()) {
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
            throw new Exception("Something went wrong please try again later");
        }
        throw new Exception("Comapny not found!!!");
    }
}
