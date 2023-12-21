<?php

namespace Candidate\Http\Resources;

use Candidate\Models\AttendanceBreak;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class TodayDetailsResource extends JsonResource
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
            'candidate_id' => $this->candidate_id ?? null,
            'company_id' => $this->company_id ?? null,
            'candidate_code' => $this->code ?? null,
            'attendance_id' => $this['attendace']?$this['attendace']->id : null,
            'start_time' => $this['attendace']?$this['attendace']->start_time : null,
            'end_time' => $this['attendace']?$this['attendace']->end_time : null,
            'today_earning' => $this['attendace']?$this['attendace']->earning : 0,
            'status' => $this['attendace']?$this['attendace']->employee_status : null,
            'today_hour_work' => $this->hourWorks($this['attendace']) ?? 0,
            'per_minute_salary' => $this->salary_in_minute ?? 0,
            'break_limit' => 4 ?? 0,
            'total_earning' => $this->total_earning ?? 0,
            'duty_time' => $this->duty_time??0,
            'breaks' => AttendanceBreakResource::collection($this['attendace']?$this['attendace']->breaks:new Collection()),
            'current_break' => new AttendanceBreakResource($this['attendace']->currentBreak?? null  )
        ];
    }



    public function hourWorks($data){

        if($data){
            if($data->start_time && $data->end_time){
                $minuteWorks = Carbon::parse($data->end_time)->diffInMinutes(Carbon::parse($data->start_time));
                $hourWorks = round($minuteWorks / 60, 2);
                return $hourWorks;
            }
        }
        
        return null;

    }
}
