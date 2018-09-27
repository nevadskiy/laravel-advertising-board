<?php

namespace App\Services\Banner;

class CostCalculator
{
    /**
     * @var int
     */
    private $price;

    /**
     * CostCalculator constructor.
     * @param int $price
     */
    public function __construct(int $price)
    {
        $this->price = $price;
    }

    /**
     * @param int $views
     * @return int
     */
    public function calc(int $views): int
    {
        return floor($this->price * ($views / 1000));
    }
}
