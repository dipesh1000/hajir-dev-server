<?php

namespace Employer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'phone',
        'country_id',
        'email',
        'dob',
        'profile_id'


    ];

    protected $appends = [
        'profile_id'
    ];

    protected $dates = [
        'dob'
    ];




}
