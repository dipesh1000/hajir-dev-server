<?php

namespace Employer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyCandidateEnrollReportResource extends JsonResource
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
            'id' => $this->id ?? null,
            'company_id' => $this->company_id,
            'candidate_id' => $this->candidate_id,
            'attendance_id' => $this->companyEnrollCandidateAttendaces->id??null,
            'name' => $this->candidate->name ?? null,
            'phone' => $this->candidate->phone ?? null,
            'start_time' => $this->companyEnrollCandidateAttendaces->start_time ?? null,
            'end_time' => $this->companyEnrollCandidateAttendaces->end_time ?? null,
        ];
    }
}
