<?php

use App\Entity\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends NestedTableSeeder
{
    protected $model = Region::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setFirstLevelSize(10)->seed(3, 5, 10);
    }
}
