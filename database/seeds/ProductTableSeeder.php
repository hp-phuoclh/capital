<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Product::class, 200)->create();
    }
}
