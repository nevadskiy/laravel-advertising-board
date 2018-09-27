<?php

namespace App\Entity\Advert;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    /**
     * @var string
     */
    protected $table = 'advert_values';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['attribute_id', 'values'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}
