<?php

namespace Files\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class FilesResource extends JsonResource
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
            'thumbnail' => getOriginalUrl($this->id),
            'title' => $this->title,
        ];
    }
}
