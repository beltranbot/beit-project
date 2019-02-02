<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Product::class, function (Faker $faker) {
    $name = $faker->company;
    $product_description = $faker->text($maxNbChars = 191);
    return [
        'name' => $name,
        'product_description' => $product_description,
        'price' => (double)random_int(1000, 10000),
    ];
});
