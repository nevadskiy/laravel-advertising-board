<?php

namespace App\Entity\Advert;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * @var string
     */
    protected $table = 'advert_photos';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['file'];
}
