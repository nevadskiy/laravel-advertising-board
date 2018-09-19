<?php

namespace App\Services\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Requests\Adverts\AttributeRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Entity\User;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use Carbon\Carbon;
use DB;

class FavoriteService
{
    public function add(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->addToFavorites($advert);
    }

    public function remove(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->removeFromFavorites($advert->id);
    }

    private function getUser(int $userId): User
    {
        return User::findOrFail($userId);
    }

    private function getAdvert(int $advertId): Advert
    {
        return Advert::findOrFaild($advertId);
    }
}
