<?php

namespace App\Http\Resources;

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
        // dd($this->candidate->attendances);
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'candidate_id' => $this->candidate_id,
            'name' => $this->candidate->firstname,
            'attendance' =>  $this->checkAttendanceToday($this),
            'break' => $this->checkAttendanceBreak($this),
            'attendanceTime' =>$this->checkAttendanceTime($this),

        ];
    }


    private function checkAttendanceToday($data)
    {

        $attendance = $data->candidate->attendances->first();
        if ($attendance) {
            return $attendance->employee_status;
        }
        return "absent";
    }

    private function checkAttendanceBreak($data)
    {

        $attendance = $data->candidate->attendances->first();
        if( $attendance){
            return $attendance->break;
        }
        return "absent";

    }

    private function checkAttendanceTime($data)
    {
        $attendance = $data->candidate->attendances->where('created_at',Carbon::today())->first();
        if($attendance){
            $result = Carbon::parse("00:00:00");
            $to = Carbon::parse($attendance->end_time);
            $from = Carbon::parse($attendance->start_time);
            $diff_in_hours = $to->diff($from)->format('%h:%i:%s');
            $hms = explode(':',$diff_in_hours);
            $result = $result->copy()->addHours($hms[0])->addMinutes($hms[1])->addSeconds($hms[2]);
            return $result;
        }
        return "absent";

    }
}
