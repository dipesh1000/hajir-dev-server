<?php

namespace Files\Models;

use Illuminate\Database\Eloquent\Model;
use Propertydetails\Models\PropertyDetails;

class UploadFile extends Model
{
    protected $fillable = ['title', 'path', 'user_id', 'file_size', 'extension', 'type'];



    public function propertyDetails(){
        return $this->belongsToMany(PropertyDetails::class, 'propertydetail_images', 'image_id','propertydetails_id');
    }


}
