<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(range(1,8) as $index) {
            DB::table('categories')->insert([
                'name' => 'kategori '. $index
            ]);
        }
    }
}
