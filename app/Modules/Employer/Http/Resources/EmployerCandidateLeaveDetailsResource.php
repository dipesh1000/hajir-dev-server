<?php

namespace Employer\Http\Resources;

use Carbon\Carbon;
use Employer\Http\Resources\LeavetypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerCandidateLeaveDetailsResource extends JsonResource
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
            'candidate_id' => $this->candidate->id ?? "",
            'remarks' => $this->remarks,
            'status' => $this->status,
            'pay_status' => $this->pay_status,
            'leave_type' => new LeavetypeResource($this->whenLoaded('LeaveType')),
            'start_date' => Carbon::parse($this->start_date),
            'end_date' => Carbon::parse($this->end_date),
            'type' => $this->type,
            'file' => isset($this->document_id) ? url('/').getOrginalUrl($this->document_id) : null


        ];
    }
}
