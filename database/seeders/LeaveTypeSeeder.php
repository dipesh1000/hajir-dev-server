<?php

namespace Database\Seeders;

use Employer\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leavetypes = [
            [
                'title' => 'Sick',
                'status' => 'Active'
            ],

            [
                'title' => 'Festival',
                'status' => 'Active'
            ],
            [
                'title' => 'Half Day',
                'status' => 'Active'
            ],
            [
                'title' => 'Rest Day',
                'status' => 'Active'
            ],
        ];




        foreach($leavetypes as $leave){
            LeaveType::create($leave);
        }






    }
}
