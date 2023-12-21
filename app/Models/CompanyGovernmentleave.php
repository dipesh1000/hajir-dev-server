<?php

namespace App\Models;

use Candidate\Models\BusinessLeaveday;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyGovernmentleave extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_date'
    ];


    public function leaveDay(){
        return $this->belongsTo(BusinessLeaveday::class, 'business_leave_id');
    }

}
