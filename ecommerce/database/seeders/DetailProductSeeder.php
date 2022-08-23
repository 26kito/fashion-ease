<?php

namespace Database\Seeders;

use App\Models\DetailProduct;
use Illuminate\Database\Seeder;

class DetailProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetailProduct::factory(5)->create();
    }
}
