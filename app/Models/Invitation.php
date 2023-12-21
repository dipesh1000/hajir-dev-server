<?php

namespace App\Models;

use Employer\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'candidate_id',
        'company_id',
        'status',
    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }

    public function candidate(){
        return $this->belongsTo(User::class,'company_id');
    }

    public function employer(){
        return $this->belongsTo(User::class,'employer_id');
    }


    public function users(){
        return $this->belongsTo(User::class,'company_id');
    }


}
