<?php

use Illuminate\Database\Seeder;

class AdvertsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Entity\Advert\Advert::class, 1000)->create();
    }
}
