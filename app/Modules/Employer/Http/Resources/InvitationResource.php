<?php

namespace Employer\Http\Resources;

use Candidate\Http\Resources\CandidateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
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
            'id' => $this->id,
            // 'company' => new CompanyResource($this->whenLoaded('company')),
            'candidate' => new CandidateResource($this->whenLoaded('candidate')),
            'status' => $this->status,
         ];
    }
}
