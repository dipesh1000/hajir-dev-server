<?php

namespace SuperAdmin\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'title',
        'status',
        'remarks',
        'price',
        'slug',
        'feature'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $casts = [
        'feature' => 'array'
    ];

    // Scopes
    public function scopeActive($q){
        return $q->where('status',"Active");
    }

    public function scopeInative($q){
        return $q->where('status',"Inactive");
    }

}
