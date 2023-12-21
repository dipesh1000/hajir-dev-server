<?php

namespace App\Http\Resources;

use Candidate\Http\Resources\CandidateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AllCandidateInvitationResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'candidatedetails' => new CandidateResource($this->whenLoaded('candidate'))

        ];
    }
}
