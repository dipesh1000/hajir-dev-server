<?php

namespace Employer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyCandidateDailyAttendanceReport extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->candidate->phone ?? null,
            'start_time' => $this->companyCandidateAttendaces->start_time ?? null,
            'end_time' => $this->companyCandidateAttendaces->end_time ?? null,
            'status' => $this->checkAttendanceToday($this)

        ];
    }


    private function checkAttendanceToday($data)
    {
        $attendance = $data->companyCandidateAttendaces;
        if ($attendance) {
            return $attendance->employee_status;
        }
        return "absent";
    }
}
