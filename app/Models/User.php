<?php

namespace App\Models;

use Candidate\Models\Attendance;
use Candidate\Models\Candidate;
use Candidate\Models\CompanyCandidate;
use Candidate\Models\Leave;
use Carbon\Carbon;
use Employer\Models\Company;
use Employer\Models\Employer;
use Files\Models\UploadFile;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    Protected $guard_name ='api';

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'phone',
        'address',
        'type',
        'dob',
        'image_id',
        'device_token'
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',

    ];

     // Scopes
    public function scopeEmployers($q){
        return $q->where('type',"employer");
    }

    public function scopeCandidateCheck($q){
        return $q->where('type', 'candidate');
    }


    public function profileImage(){
        return $this->belongsTo(UploadFile::class, 'image_id');
    }

    // Relationships
    public function employer(){
        return $this->hasOne(Employer::class, 'user_id');
    }

    public function employerCompany(){
        return $this->hasMany(Company::class, 'employer_id');
    }


    public function candidateCompany(){
        return $this->hasOne(Company::class, 'candidate_id');
    }


    public function companyCandidate(){
        return $this->hasMany(CompanyCandidate::class, 'candidate_id');
    }

    public function otp(){
        return $this->hasOne(UserOtp::class, 'user_id');
    }

    public function leaves(){
        return $this->hasMany(Leave::class);
    }

    public function candidate(){
        return $this->hasOne(Candidate::class, 'user_id');
    }

    public function receivedInvitation(){
        return $this->belongsTo(Invitation::class,'candidate_id','id');
    }

    public function receivedCompanyInvitation(){
        $user = Auth::user();

        // dd($user->employerCompany);
        return $this->hasMany(Invitation::class,'candidate_id','id')->where('company_id', 1)
        ->where('employer_id', $user->id);
    }



    public function sendInvitation(){
        return $this->hasMany(Invitation::class,'employer_id','id');
    }

    public function candidateCompanies(){
        return $this->belongsToMany(Company::class,'company_candidates','candidate_id','company_id')
        ->withPivot('code','office_hour_start',
        'office_hour_end', 'status','duty_time', 'salary_amount',
        'salary_type','overtime');
    }


    public function activecandidateCompanies(){
        return $this->belongsToMany(Company::class,'company_candidates','candidate_id','company_id')
        ->withPivot('code','office_hour_start',
        'office_hour_end', 'status','duty_time', 'salary_amount',
        'salary_type','overtime')->wherePivot('status', 'Active');
    }


    public function userCompanies(){
        return $this->belongsToMany(Company::class,'company_candidates','user_id','company_id')
        ->withPivot('verified_status','status',  'office_hour_start',
        'office_hour_end',
        'salary_type',
        'salary_amount',
        'duty_time');
    }


    public function companiesByCandidateID(){
        $user = auth()->user();
        return $this->belongsToMany(Company::class,'company_candidates','candidate_id','company_id')->withPivot('verified_status','status',  'office_hour_start',
        'office_hour_end',
        'salary_type',
        'salary_amount',
        'duty_time')->where('candidate_id', '=', $user->id);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class,'candidate_id','id');
    }


    public function todayattendances(){
        return $this->hasMany(Attendance::class,'candidate_id','id')
                        ->whereDate('created_at', '=', today());
    }



    public function weeklyattendances(){
        $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);
        return $this->hasMany(Attendance::class,'candidate_id','id')
        ->whereBetween('created_at', [$weekStart, $weekEnd]);
    }


    public function monthlyattendances(){
        $month = today()->format('m');
        return $this->hasMany(Attendance::class,'candidate_id','id')
        ->whereMonth('created_at', $month);
    }


    public function yearlyattendances(){
        return $this->hasMany(Attendance::class,'candidate_id','id')
        ->whereYear('created_at', today()->format('y'));
    }



}
