<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'firstname' => 'admin',
                'lastname' => 'admin',
                'email' => 'admin@gmail.com',
                'email_verified_at'  => Carbon::now(),
                'phone' => '9856456897',
                'password' => bcrypt('testing@1234'),
                'type' => 'superadmin'
            ],
        ];


        // foreach($admins as $admin){
        //     $admin = User::create($admin);
        //     $admin->assignRole('superadmin');
        // }

    }
}
