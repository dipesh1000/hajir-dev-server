<?php

namespace Database\Factories;

use Candidate\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

     protected $model = Attendance::class;

    public function definition()
    {
        return [
            'candidate_id' => 3,
            'company_id' => 1,
            'employee_status' => $this->faker->randomElement('Present', 'Absent'),
            'created_at'=> $this->faker->dateTimeBetween(-1, now()),

        ];
    }
}
