<?php

namespace Candidate\Http\Resources;

use Carbon\Carbon;
use Employer\Http\Resources\CompanyResource;
use Employer\Http\Resources\EmployerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateInvitationResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'company'=> new CompanyResource($this->whenLoaded('company')),
            'employer'=> new EmployerResource($this->whenLoaded('employer')),
        ];
    }
}
