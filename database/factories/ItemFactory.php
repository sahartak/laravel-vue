<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;

use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => 'Item '.$faker->numberBetween(1,1000),
        'description' => $faker->sentence(),
        'price' => $faker->numberBetween(100, 90000),
        'image' => $faker->imageUrl($width = 200, $height = 200),
        'active_until' => $faker->dateTimeBetween(now(),'+3 days'),
    ];
});
