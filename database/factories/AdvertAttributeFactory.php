<?php

use App\Entity\Advert\Attribute;
use App\Entity\Advert\Category;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->afterCreating(Category::class, function (Category $category, Faker $faker) {
    $category->attributes()->saveMany(
        factory(Attribute::class, rand(5, 10))->make()
    );
});

$factory->define(Attribute::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'type' => $type = $faker->randomElement([Attribute::TYPE_FLOAT, Attribute::TYPE_INTEGER, Attribute::TYPE_STRING]),
        'required' => $faker->boolean,
        'variants' => [],
        'sort' => 0
    ];
});
