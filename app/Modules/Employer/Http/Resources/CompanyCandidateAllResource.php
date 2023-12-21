<?php

namespace Employer\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyCandidateAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [    
            'id' => $this->id,
            'company_id' => $this->company_id,
            'candidate_id' => $this->candidate_id,
            'name' => $this->candidate->firstname,
            'phone' => $this->candidate->phone,
            'email' => $this->candidate->email,
            'dob' => $this->candidate->dob->format('Y-m-d'),
            'joining_date' => $this->joining_date->format('Y-m-d'),
            'designation' => $this->designation,
            'code' => $this->code,
            'duty_time' => $this->duty_time,
            'salary_amount' => $this->salary_amount,
            'salary_type' => $this->salary_type,
            'overtime' => $this->overtime,
            'allow_late_attendance' => $this->allow_late_attendance,
            'working_hours' => $this->working_hours,
            'allowance_amount' => $this->allowance_amount,
            'allowance_type' => $this->allowance_type,
            'casual_leave' => $this->casual_leave,
            'casual_leave_type' => $this->casual_leave_type,

            'office_hour_start' => $this->office_hour_start,
            'office_hour_end' => $this->office_hour_end,

        ];
    }


    
}
