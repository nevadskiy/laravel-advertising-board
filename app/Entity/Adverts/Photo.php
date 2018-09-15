<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'advert_photos';

    public $timestamps = false;

    protected $fillable = ['file'];
}
