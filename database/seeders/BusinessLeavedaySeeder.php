<?php

namespace Database\Seeders;

use Candidate\Models\BusinessLeaveday;
use Illuminate\Database\Seeder;

class BusinessLeavedaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leavedays = [
            [
                'title' => 'Sunday'
            ],
            [
                'title' => 'Monday'
            ],
            [
                'title' => 'Tuesday'
            ],
            [
                'title' => 'Wednesday'
            ],
            [
                'title' => 'Thursday'
            ],
            [
                'title' => 'Friday'
            ],
        ];

        foreach($leavedays as $leaveday){
            BusinessLeaveday::create($leaveday);
        }


    }
}
