<?php

namespace Employer\Http\Resources;

use Candidate\Http\Resources\CandidateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'attendance_time' => $this->attendance_time,
            'employee_status' => $this->employee_status,
            'candidate' => new CandidateResource($this->whenLoaded('candidate')),
         ];
    }
}
