<?php

namespace App\Services\Adverts;

use App\Entity\Advert\Advert;
use App\Entity\User;

class FavoriteService
{
    /**
     * @param int $userId
     * @param int $advertId
     */
    public function add(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->addToFavorites($advert);
    }

    /**
     * @param int $userId
     * @param int $advertId
     */
    public function remove(int $userId, int $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->removeFromFavorites($advert->id);
    }

    /**
     * @param int $userId
     * @return User
     */
    private function getUser(int $userId): User
    {
        return User::findOrFail($userId);
    }

    /**
     * @param int $advertId
     * @return Advert
     */
    private function getAdvert(int $advertId): Advert
    {
        return Advert::findOrFaild($advertId);
    }
}
