<?php

namespace App\Entity\Banner;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public static function formatsList()
    {
        return [
            // TODO:
        ];
    }

    public static function statusesList()
    {
    }

    public function scopeForUser(User $user)
    {
        // TODO:
    }

    public function canBeChanged()
    {

    }
}
