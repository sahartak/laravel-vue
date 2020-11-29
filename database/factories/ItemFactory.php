<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;

use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    $price =  $faker->numberBetween(1, 1000);
    return [
        'name' => rtrim($faker->sentence(rand(1, 3)), '.'),
        'description' => $faker->sentence(),
        'price' => $price,
        'start_price' => $price,
        'image' => $faker->imageUrl($width = 200, $height = 200),
        'active_until' => $faker->dateTimeBetween('-5 days','+15 days'),
    ];
});
