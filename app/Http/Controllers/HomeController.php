<?php

namespace App\Http\Controllers;

use App\Entity\Advert\Category;
use App\Entity\Region;

class HomeController extends Controller
{
    public function index()
    {
        $regions = Region::root()->orderBy('name')->getModels();

        $categories = Category::whereIsRoot()->defaultOrder()->getModels();

        return view('home', compact('regions', 'categories'));
    }
}
