<?php

namespace Employer\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyCandidateResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->candidate->phone ?? '',
            'email' => $this->candidate->email ?? '',
            'code' => $this->code,
            'status' =>  $this->checkAttendanceToday($this),

            
            'office_hour_start' => $this->office_hour_start ?? '',
            'office_hour_end' => $this->office_hour_end ?? '',

        ];
    }


    private function checkAttendanceToday($data)
    {

        $attendance = $data->candidate->attendances->where('created_at', '>=', Carbon::today())->first();
        
        if ($attendance) {
            return $attendance->employee_status;
        }
        return "absent";
    }
}
