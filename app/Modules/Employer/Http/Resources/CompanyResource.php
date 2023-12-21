<?php

namespace Employer\Http\Resources;

use Candidate\Http\Resources\CompanyCandidateResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'generate_code' => $this->code==1?true:false,
            'company_code' => rand(999, 9999),
            'phone' => $this->phone,
            'address' => $this->address,
            'working_hours' => $this->working_hours,
            'office_hour' => $this->office_hour ?? null,
            'salary_type' => $this->pivot->salary_type ?? $this->salary_type ?? null,
            // 'duty_time' => $this->pivot->duty_time ?? $this->duty_time ?? null,
            // 'overtime' => $this->pivot->overtime ?? $this->overtime ?? null,
            // 'salary_amount' => $this->pivot->salary_amount ?? $this->salary_amount ?? null,
            // 'verified_status' => $this->pivot->verified_status ?? null,
            // 'status' => $this->pivot->status ?? null,
            'sick_leave_type' => $this->leave_duration_type ?? null,
            'sick_leave_days' => $this->leave_duration ?? null,
            'probation_period' => $this->probation_period ?? null,
            'employee_count' => $this->candidates_count ?? 0,
            'approver_count' => 0,
            'created_at' => $this->created_at,
            'company_business_leaves' => CompanyBusinessLeavesResource::collection($this->whenLoaded('companyBusinessLeave')),
            'company_goverment_leaves' => CompanyGovermentLeavesResource::collection($this->whenLoaded('govLeaves')),
            'company_special_leaves' => CompanySpecialLeavesResource::collection($this->whenLoaded('specialLeaves'))

        ];
    }
}
