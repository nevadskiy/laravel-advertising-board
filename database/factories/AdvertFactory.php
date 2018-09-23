<?php

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Adverts\Value;
use App\Entity\Region;
use App\Entity\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Advert::class, function (Faker $faker) {
    $status = $faker->randomElement([
        Advert::STATUS_ACTIVE,
        Advert::STATUS_MODERATION,
        Advert::STATUS_CLOSED,
        Advert::STATUS_DRAFT
    ]);

    return [
        'user_id' => User::inRandomOrder()->value('id'),
        'category_id' => Category::inRandomOrder()->value('id'),
        'region_id' => Region::inRandomOrder()->value('id'),

        'title' => $faker->sentence(rand(4, 8)),
        'price' => rand(20, 50),
        'address' => $faker->address,
        'content' => $faker->text,

        'status' => $status,
        'reject_reason' => ($status === Advert::STATUS_DRAFT && rand(0, 5) === 5) ? $faker->sentence(3) : '',

        'published_at' => $published = now(),
        'expires_at' => Carbon::parse($published->format('Y-m-d H:i:s'))->addMonths(rand(2, 10)),
    ];
});

$factory->afterCreating(Advert::class, function (Advert $advert, Faker $faker) {

    /** @var \App\Entity\Adverts\Attribute $attribute $attribute */
    foreach ($advert->category->allAttributes() as $attribute) {
        if ($faker->boolean) {
            continue;
        }

        /** @var Value $value */
        $value = $advert->values()->make([
            'value' => $attribute->isString() ? $faker->word
                : $attribute->isFloat() ? $faker->randomFloat(2, 0, 10)
                : rand(0, 30)
        ]);
        $value->attribute()->associate($attribute);
        $value->save();
    }
});
