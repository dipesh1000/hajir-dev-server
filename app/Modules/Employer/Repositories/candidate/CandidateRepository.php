<?php

namespace Employer\Repositories\candidate;

use App\GlobalServices\ResponseService;
use Candidate\Models\Candidate;
use App\Models\User;
use Candidate\Mail\CandidateCreatedMail;
use Candidate\Models\CompanyCandidate;
use Employer\Repositories\candidate\CandidateInterface;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use CMS\Models\NagarikWadaPatra;
use Employer\Models\Company;
use Exception;
use Files\Repositories\FileInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CandidateRepository implements CandidateInterface
{

    protected $file = null, $response;
    public function __construct(FileInterface $file, ResponseService $response)
    {
        $this->file = $file;
        $this->response = $response;
    }


    public function store($request, $company_id)
    {
        dd('aksdhj');
        $user = User::where('phone', $request->contact)->candidateCheck()->first();
        if (!$user) {
            $user = new User();
            // $user->name = $request->name;
            // $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->phone = $request->contact;
            $user->email_verified_at = Carbon::now();
            $user->password = bcrypt($user->phone);
            $user->address = $request->address;
            $user->dob = $request->dob;
            $user->type = 'candidate';
            if (!$user->save()) {
                throw new Exception("Something went wrong while creating user",400);
            }
            $user->assignRole('candidate');
        }
        $companycandidate = new CompanyCandidate();
        if ($request->duty_time && $request->working_hours) {
            $dutyTime = $this->officeHourCalc($request->duty_time, $request->working_hours);
            $companycandidate->office_hour_start = $dutyTime['start'] ?? null;
            $companycandidate->office_hour_end = $dutyTime['end'] ?? null;
        }

        if ($request->code) {
            $companycandidate->code = $request->code;
        } 

        $name_parts = explode(" ", $request->name, 2);

        $companycandidate->name = $name_parts[0];

        // Check if there is a last name
        if (isset($name_parts[1])) {
            $companycandidate->lastname = $name_parts[1];
        }

        $companycandidate->candidate_id = $user->id;
        $companycandidate->company_id =$company_id;
        $companycandidate->salary_type = $request->salary_type;
        $companycandidate->salary_amount = $request->salary_amount;
        $companycandidate->duty_time = $request->duty_time;
        $companycandidate->working_hours = $request->working_hours;
        $companycandidate->designation = $request->designation;
        $companycandidate->joining_date = Carbon::parse($request->joining_date);
        $companycandidate->allow_late_attendance = $request->allow_late_attendance;
        $companycandidate->verified_status = 'not_verified';
        $companycandidate->status = 'Inactive';
        $companycandidate->overtime = $request->over_time;
        $companycandidate->allowance_amount = $request->allowance_amount;
        $companycandidate->allowance_type = $request->allowance_type;
        $companycandidate->casual_leave = $request->casual_leave;
        $companycandidate->casual_leave_type = $request->casual_leave_type;
        if (!$companycandidate->save()) {
            throw new Exception("Something went wrong while storing ccompany candiate");
        }
        return true;

    }



    public function update($request, $company_id, $candidate_id)
    {
        // dd($request->all());
        $company = Company::where('id', $company_id)->first();
        if ($company) {
            $user = User::where('id', $candidate_id)->first();
            if ($user) {
                // $user->name = $request->name;
                // $user->lastname = $request->lastname;
                $user->email = $request->email;
                $user->phone = $request->contact;
                $user->dob = $request->dob;
                if (!$user->update()) {
                    throw new Exception("Something went wrong while updating candidate details",200);
                }
            }
            $companycandidate = CompanyCandidate::where('candidate_id', $candidate_id)
                                ->where('company_id', $company->id)->first();

            if ($companycandidate) {
                if ($request->duty_time && $request->working_hours) {
                    $dutyTime =  $this->officeHourCalc($request->duty_time, $request->working_hours);
                    $companycandidate->office_hour_start = $dutyTime['start'] ?? null;
                    $companycandidate->office_hour_end = $dutyTime['end'] ?? null;
                }


                $name_parts = explode(" ", $request->name, 2);

                $companycandidate->name = $name_parts[0];

                // Check if there is a last name
                if (isset($name_parts[1])) {
                    $companycandidate->lastname = $name_parts[1];
                }
               
                $companycandidate->candidate_id = $candidate_id;
                $companycandidate->company_id = $company->id;
                $companycandidate->salary_type = $request->salary_type;
                $companycandidate->salary_amount = $request->salary_amount;
                $companycandidate->duty_time = $request->duty_time;
                $companycandidate->working_hours = $request->working_hours;
                $companycandidate->designation = $request->designation;
                $companycandidate->joining_date = Carbon::parse($request->joining_date);
                $companycandidate->allow_late_attendance = $request->allow_late_attendance;
                $companycandidate->verified_status = 'not_verified';
                $companycandidate->status = 'Inactive';
                $companycandidate->overtime = $request->over_time;
                $companycandidate->allowance_amount = $request->allowance_amount;
                $companycandidate->allowance_type = $request->allowance_type;
                $companycandidate->casual_leave = $request->casual_leave;
                $companycandidate->casual_leave_type = $request->casual_leave_type;
                if (!$companycandidate->update()) {
                    throw new Exception("Something went wrong while saving company candiate",200);
                }

                return true;
            }
            throw new Exception("Candidate not found", 404);
        }
        throw new Exception("Company not found",404);
    }

    public function getCandidatesByCompany($id)
    {
        $candidates = Candidate::where('company_id', $id)->get();
        return $candidates;
    }


    public function officeHourCalc($duty_time = null, $working_hours = null)
    {
        $dutyTime = Carbon::createFromFormat('H:i', $duty_time); // create Carbon instance from duty_time

        list($hours, $minutes) = explode(':', $working_hours);
        $workingHours = CarbonInterval::hours($hours)->minutes($minutes); // create CarbonInterval instance from working_hours
        $officeHourEnd = $dutyTime->copy()->add($workingHours); // add working_hours to duty_time to get office hour end time

        // Format the start and end times as string
        $officeHourStartString = $dutyTime->format('H:i:s');
        $officeHourEndString = $officeHourEnd->format('H:i:s');
        return [
            'start' => $officeHourStartString,
            'end' => $officeHourEndString
        ];
    }
}
