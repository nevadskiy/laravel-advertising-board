<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 */
class Region extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];

    public $timestamps = false;

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }
}
