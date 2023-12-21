<?php

namespace Employer\Models;


use App\Models\CompanyGovernmentleave;
use App\Models\CompanySpecialleave;
use App\Models\User;
use Candidate\Models\Attendance;
use Candidate\Models\BusinessLeaveday;
use Candidate\Models\Candidate;
use Candidate\Models\CompanyBusinessleave;
use Candidate\Models\CompanyCandidate;
use Candidate\Models\Leave;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, Sluggable, SoftDeletes;



    protected $fillable = [
        'name',
        'code',
        'employer_id',
        'address',
        'phone',
        'email',
        'working_days',
        'office_hour_start',
        'office_hour_end',
        'office_hour',
        'leave_duration_type',
        'leave_duration',
        'probation_period',
        'status'
    ];

    protected $time =[
        'office_hour_start',
        'office_hour_end'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    protected $dates = ['deleted_at'];


    // Scopes
    public function scopeActive($q){
        return $q->where('status',"Active");
    }

    public function scopeInactive($q){
        return $q->where('status',"Inactive");
    }

    // Relationships
    public function employer(){
        return $this->belongsTo(User::class, 'employer_id');
    }

    

    public function activeCandidates(){
        return  $this->belongsToMany(User::class, 'company_candidates', 'company_id', 'candidate_id')

        ->withPivot('verified_status','status', 'office_hour_start',
        'office_hour_end',
        'salary_type',
        'salary_amount',
        'duty_time')
        ->wherePivot('status','=','Active');
    }


    public function inactiveCandidates(){
        return  $this->belongsToMany(User::class, 'company_candidates', 'company_id', 'candidate_id')

        ->withPivot('verified_status','status', 'office_hour_start',
        'office_hour_end',
        'salary_type',
        'salary_amount',
        'duty_time')
        ->wherePivot('status','=','Inactive');
    }

    public function candidates(){
        return $this->belongsToMany(User::class, 'company_candidates', 'company_id', 'candidate_id')
        ->withPivot('verified_status','status',  'office_hour_start',
        'office_hour_end',
        'salary_type',
        'salary_amount',
        'duty_time');;
    }


    public function candidatesByCompanyID($companyid = null){
        // dd($this->id,$this->company_id);
        // dd($companyid);
        return $this->belongsToMany(User::class, 'company_candidates', 'company_id', 'candidate_id')
        ->withPivot('code','office_hour_start',
        'office_hour_end', 'status','duty_time', 'salary_amount',
        'salary_type','overtime');
    }


    public function activecandidatesByCompanyID(){
        return $this->belongsToMany(User::class, 'company_candidates', 'company_id', 'candidate_id')
        ->withPivot('code','office_hour_start',
        'office_hour_end', 'status','duty_time', 'salary_amount',
        'salary_type','overtime')->wherePivot('status', 'Active');
    }

    public function companyCandidates(){
        return $this->hasMany(CompanyCandidate::class, 'company_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'company_candidates','company_id', 'user_id');
    }

    public function attendances(){
        return $this->hasMany(Attendance::class, 'company_id');
    }

    //company users

    public function govLeaves(){
        return $this->hasMany(CompanyGovernmentleave::class, 'company_id');
    }


    public function specialLeaves(){
        return $this->hasMany(CompanySpecialleave::class, 'company_id');
    }

    public function companyBusinessLeave(){
        return $this->hasMany(CompanyBusinessleave::class,  'company_id');
    }

    public function businessLeaves(){
        return $this->belongsToMany(BusinessLeaveday::class, 'company_businessleaves', 'company_id', 'business_leave_id');
    }

    public function leaveTypes(){
        return $this->hasMany(LeaveType::class, 'company_id');
    }


    public function companyCandidateLeaves(){
        return $this->hasMany(Leave::class, 'company_id')->with('LeaveType');
    }



}
