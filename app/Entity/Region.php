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
    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'parent_id'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return ($this->parent ? $this->parent->getAddress() . ', ' : '') . $this->name;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeRoot(Builder $query)
    {
        return $query->where('parent_id', null);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return ($this->parent ? $this->parent->getPath() . '/' : '') . $this->slug;
    }
}
