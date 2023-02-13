<?php

namespace Database\Seeders;

use App\Models\DetailProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DetailProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetailProduct::factory(700)->create();
    }
}
