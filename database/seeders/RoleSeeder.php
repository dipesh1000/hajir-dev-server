<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $roles = [
            [
                'name' => 'superadmin',
                'guard_name' => 'web'
             ],
            [
                'name' => 'employer',
                'guard_name' => 'web'
             ],
            [

                'name' => 'candidate',
                'guard_name' => 'web'
            ]
        ];

        foreach($roles as $role){
            Role::create($role);
        }
    }
}
