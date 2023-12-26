<?php

namespace Candidate\Http\Resources;

use Carbon\Carbon;
use Employer\Http\Resources\CompanyResource;
use Employer\Http\Resources\EmployerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
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
            'candidate_id' => $this->candidate_id,
            'name' => $this->name,
            'code' => $this->pivot->code ?? "",

            'contact' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'joining_date' => Carbon::parse($this->joining_date),

            // 'office_hour_start' => $this->pivot->office_hour_start ?? "",
            // 'office_hour_end' => $this->pivot->office_hour_end ?? "",
            // 'status' => $this->pivot->status ?? "",
            // 'salary_type' => $this->pivot->salary_type ?? "",
            // 'salary_amount' => $this->pivot->salary_amount ?? "",
            // 'companies' => CompanyResource::collection($this->whenLoaded('companies')),
            // 'employer' => new EmployerResource($this->whenLoaded('employer'))

        ];
    }
}
