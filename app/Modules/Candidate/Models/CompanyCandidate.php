<?php

namespace Candidate\Models;

use App\Models\Invitation;
use App\Models\User;
use Carbon\Carbon;
use Employer\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'candidate_id',
        'verified_status',
        'status',
        'office_hour_start',
        'office_hour_end',
        'code',
        'salary_amount',
        'salary_type',
        'overtime',
        'designation',
        'joining_date',
        'duty_time',
        'allow_late_attendance',
        'invitation_id',
        'allowance_amount',
        'allowance_type',
        'casual_leave_type',
        'casual_leave',
        'working_hours',
        'is_approver',
        // 'lastname',
        'name'

    ];

    protected $dates = [
        'joining_date'
    ];

    protected $time = [
        'office_hour_start',
        'office_hour_end',
        
    ];



    public function scopeVerified($q){
        return $q->where('verified_status', 'verified');
    }

    public function scopeNotVerified($q){
        return $q->where('verified_status', 'not_verified');
    }


    public function scopeActive($q){
        return $q->where('status', 'Active');
    }

    public function scopeInactive($q){
        return $q->where('status', 'Inactive');
    }


    public function candidate(){
        return $this->belongsTo(User::class, 'candidate_id');
    }


    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function attendaces(){
        return $this->hasMany(Attendance::class, 'candidate_id', 'candidate_id');
    }

    public function todayattendances(){
        return $this->hasMany(Attendance::class, 'candidate_id', 'candidate_id')->where('created_at',today());
    }


    public function companyCandidateAttendaces(){
        return $this->belongsTo(Attendance::class, 'candidate_id', 'candidate_id')
        ->where('created_at', '=', Carbon::today());
    }


    public function companyEnrollCandidateAttendaces(){
        return $this->belongsTo(Attendance::class, 'candidate_id', 'candidate_id');
    }

    public function companyEnrollTodayCandidateAttendacesCheckIn(){
        return $this->belongsTo(Attendance::class, 'candidate_id', 'candidate_id')
                        ->where('created_at', '=', Carbon::today())
                        ->where('end_time',null)
                        ->where('start_time','!=',null);
    }

    public function companyEnrollTodayCandidateAttendacesCheckOut(){
        return $this->belongsTo(Attendance::class, 'candidate_id', 'candidate_id')
                        ->where('created_at', Carbon::today())
                        ->where('end_time','!=',null)
                        ->where('start_time','!=',null);
    }




    public function activecompanyCandidateAttendaces(){
        return $this->belongsTo(Attendance::class, 'candidate_id', 'candidate_id')
                    ->where('created_at', Carbon::parse(today()))->whereNull('end_time');
    }

    public function invitation(){
        return $this->belongsTo(Invitation::class, 'invitation_id');
    }


}
