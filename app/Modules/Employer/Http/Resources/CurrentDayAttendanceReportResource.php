<?php

namespace Employer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrentDayAttendanceReportResource extends JsonResource
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
            'total_attendee' => 2,
            'absent' => 1,
            'late' => 3,
            'candidates' => [
                [
                    'id' => 1,
                    'name' => 'ram lal',
                    'status' => 'Present',
                    'code' => 'RT-5605'
                ],
                [
                    'id' => 2,
                    'name' => 'pogo',
                    'status' => 'Absent',
                    'code' => 'RT-5606'
                ],
                [
                    'id' => 3,
                    'name' => 'ramesh',
                    'status' => 'late',
                    'code' => 'RT-5607'
                ],
                [
                    'id' => 3,
                    'name' => 'hira lal',
                    'status' => 'present',
                    'code' => 'RT-5608'
                ],

            ]

        ];





    }
}
