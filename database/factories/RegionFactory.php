<?php

use App\Entity\Region;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Region::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->city,
        'slug' => $faker->unique()->slug(2),
        'parent_id' => null,
    ];
});
