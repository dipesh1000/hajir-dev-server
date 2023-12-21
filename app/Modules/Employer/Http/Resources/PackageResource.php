<?php

namespace Employer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'feature' => $this->feature
         ];
    }
}
