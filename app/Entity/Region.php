<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 *
 * @method Builder root()
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

    public function getAddress(): string
    {
        return ($this->parent ? $this->parent->getAddress() . ', ' : '') . $this->name;
    }

    public function scopeRoot(Builder $query)
    {
        return $query->where('parent_id', null);
    }

    public function getPath()
    {
        return ($this->parent ? $this->parent->getPath() . '/' : '') . $this->slug;
    }
}
