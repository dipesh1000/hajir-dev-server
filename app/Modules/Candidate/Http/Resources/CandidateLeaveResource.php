<?php

namespace Candidate\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateLeaveResource extends JsonResource
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
            'candidate_id' => $this->candidate_id??'',
            'company_id' => $this->company_id??'',
            'leave_type' => $this->LeaveType->title . ' Leave' ??'' ,
            'type' => $this->type,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'start_date' => $this->start_date->format('d M Y'),
            'end_date' => $this->end_date->format('d M Y'),
            'application_date' => $this->created_at->format('d M Y'),
            'document_url' => $this->document?url(getFileUrlByUploads($this->document)):null,
            'name' => $this->candidate->firstname??'',

        ];
    }
}
