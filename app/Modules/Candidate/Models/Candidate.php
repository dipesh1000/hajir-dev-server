<?php

namespace Candidate\Models;

use Employer\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'contact',
        'email',
        'address',
        'dob',
        'joining_date'
    ];

    protected $dates = [
        'joining_date'
    ];



    public function companies(){
       return $this->belongsToMany(Company::class, 'company_candidates')
        ->withPivot('verified_status','status',  'office_hour_start',
        'office_hour_end',
        'salary_type',
        'salary_amount',
        'duty_time');

    }


    public function companyCandidate(){
        return $this->hasMany(CompanyCandidate::class, 'candidate_id');
    }




    public function employer(){
        return $this->belongsTo(User::class, 'employer_id');
    }


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function attendances(){
        return $this->hasMany(Attendance::class, 'candidate_id');
    }


    //has today attendace

    public function todayAttendance(){
        return $this->hasMany(Attendance::class, 'candidate_id')->whereDate('created_at', today());
    }


}
