<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table = 'advert_values';

    public $timestamps = false;

    protected $fillable = ['attribute_id', 'values'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}
