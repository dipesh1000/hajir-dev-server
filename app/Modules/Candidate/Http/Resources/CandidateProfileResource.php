<?php

namespace Candidate\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CandidateProfileResource extends JsonResource
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
            'name' => $this->firstname,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'dob' => $this->dob != null ? $this->dob->format('Y-m-d') : '',
            'profile_image' => returnProfileUrl($this->profileImage),

        ];
    }
}
