<?php

namespace Employer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployerResource extends JsonResource
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
            'name' => $this->name ?? '',
            'code' => $this->code ?? '',
            'phone' => $this->phone ?? '',
            'email' => $this->email ?? '',
            'dob' => $this->dob != null ? $this->dob->format('Y-m-d') : '',
        ];
    }
}
