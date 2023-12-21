<?php

namespace Database\Seeders;

use Candidate\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances = [
            [
                'candidate_id' => 3,
                'company_id' => 1,
                'employee_status' => 'Present',
                'start_time' => Carbon::parse('10:00'),
                'end_time' => Carbon::parse('18:00'),
                'earning' => 1000,
                'created_at' => now()
            ],
            [
                'candidate_id' => 4,
                'company_id' => 1,
                'employee_status' => 'Late',
                'start_time' => Carbon::parse('12:00'),
                'end_time' => Carbon::parse('18:00'),
                'earning' => 1000,
                'created_at' => now()
            ]
        ];



        foreach($attendances as $attendance){
            Attendance::create($attendance);
        }



    }
}
