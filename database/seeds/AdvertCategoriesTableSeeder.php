<?php

use App\Entity\Advert\Category;

class AdvertCategoriesTableSeeder extends NestedTableSeeder
{
    protected $model = Category::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setFirstLevelSize(10)->seed(3, 3, 7);
    }
}
