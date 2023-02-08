<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            'id' => 1,
            'role_name' => 'Admin'
        ]);
        Role::insert([
            'id' => 2,
            'role_name' => 'Customer'
        ]);
    }
}
