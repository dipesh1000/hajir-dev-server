<?php

namespace Candidate\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Employer\Models\Company;
use Employer\Models\LeaveType;
use Files\Models\UploadFile;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'leave_type_id',
        'document_id',
        'remarks',
        'status',
        'company_id',
        'user_id',
        'start_date',
        'end_date',
        'type',
        'pay_status',
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];


    public function scopeApproved($q){
        return $q->where('status', 'Approved');
    }

    public function scopePending($q){
        return $q->where('status', 'Pending');
    }

    public function scopeRejected($q){
        return $q->where('status', 'Rejected');
    }


    public function LeaveType(){
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }


    public function candidate(){
        return $this->belongsTo(User::class,'candidate_id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function document(){
        return $this->belongsTo(UploadFile::class, 'document_id');
    }






}
