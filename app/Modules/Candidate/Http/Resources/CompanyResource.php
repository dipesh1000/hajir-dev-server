<?php

namespace Candidate\Http\Resources;

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
            'id' => $this->company->id ?? '',
            'name' => $this->company->name ?? '',
            'generate_code' => $this->company->code == 1 ? true : false,
            'phone' => $this->company->phone,
            'address' => $this->company->address,
            'working_hours' => $this->company->working_hours,
            'office_hour_start' => $this->office_hour_start ?? null,
            'office_hour_end' => $this->office_hour_end ?? null,
            'verified_status' => $this->verified_status ?? null,
            'is_approver' => $this->is_approver ?? false,
        ];
    }

    
}
