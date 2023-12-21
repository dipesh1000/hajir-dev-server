<?php

namespace Candidate\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CandidateYearlyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'year' => $this->year,
            'month' => $this->month,
            'total_earning' => $this->total_earning,
        ];
    }
}
