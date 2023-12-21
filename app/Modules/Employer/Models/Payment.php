<?php

namespace Employer\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable =[
        'status',
        'paid_amount',
        'payment_date',
        'payment_for_month',
        'company_id',
        'candidate_id',
        'employer_id',
        'deduction',
        'bonus',
        'allowance_type'
    ];

    protected $dates = ['payment_date','payment_for_month',];

    public function candidate()
    {
        return $this->belongsTo(User::class,'candidate_id');
    }
}
