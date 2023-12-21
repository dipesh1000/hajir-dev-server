<?php

namespace Candidate\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attendance extends Model
{
    use HasFactory;

    protected $appends = ['break_duration'];


    public function getBreakDurationAttribute()
    {
        $datas = $this->breaks;
        if($datas->count() > 0){
            $result = Carbon::parse("00:00:00");
            foreach($datas as $data){
                $to = Carbon::parse($data->end_time);
                $from = Carbon::parse($data->start_time);
                $diff_in_hours = $to->diff($from)->format('%h:%i:%s');
                $hms = explode(':',$diff_in_hours);
                $result = $result->copy()->addHours($hms[0])->addMinutes($hms[1])->addSeconds($hms[2]);
            }
            return $result->format('h:i:s');
        }
        return 0;
    }


    public function getAttendanceDurationAttribute(){
        $result = Carbon::parse("00:00:00");
        $to = Carbon::parse($this->end_time);
        $from = Carbon::parse($this->start_time);
        $diff_in_hours = $to->diff($from)->format('%h:%i:%s');
        $hms = explode(':',$diff_in_hours);
        $result = $result->copy()->addHours($hms[0])->addMinutes($hms[1])->addSeconds($hms[2]);
        return $result->format('h:i:s');
    }
    protected $fillable = [
        'candidate_id',
        'leave_type_id',
        'start_time',
        'end_time',
        'employee_status',
        'company_id',
        'earning',
        'created_at',
        'overtime_earning',
        'approve_status',
        'approver_id'
    ];


    protected $time = [
        'start_time',
        'end_time'
    ];

    public function candidate(){
        return $this->belongsTo(User::class,'candidate_id');
    }

    public function breaks(){
        return $this->hasMany(AttendanceBreak::class);
    }

    public function currentBreak(){
        return $this->hasOne(AttendanceBreak::class,'attendance_id')
                ->where('start_time','!=',null)->where('end_time',null);
    }

   

}
