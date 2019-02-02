<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Customer::class, function (Faker $faker) {
    $name = $faker->name;
    $email = $faker->email;

    return [
        'name' => $name,
        'email' => $email,
    ];
});
