<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'first_name' => 'kito',
            'email' => 'kito@example.com',
            'password' => Hash::make('12345678'),
            'role_id' => 1,
        ]);

        User::factory(3000)->create();
    }
}
