<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use App\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Product::class, function (Faker $faker) {
    return [
        'category_id' => Category::orderByRaw('RAND()')->first()->id,
        'code' => Str::random(3),
        'name' => $faker->firstNameMale,
        'description' => $faker->text,
        'price' => $faker->numerify('#########'),
        'sale_off' => $faker->numerify('#########'),
        'sizes' => $faker->words($nb = 3, $asText = false),
        'status' => '0',
        'published' => 1,
    ];
});
