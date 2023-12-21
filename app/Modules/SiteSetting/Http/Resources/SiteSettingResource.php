<?php

namespace SiteSetting\Http\Resources;

use GlobalOption\Http\Resources\District\DistrictResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
                $this->key => $this->value,
        ];
    }



}
