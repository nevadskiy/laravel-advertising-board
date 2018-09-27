<?php

namespace App\Entity\Advert;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 *
 * @property int $depth
 * @property Category $parent
 * @property Category[] $children
 * @property Attribute[] $attributes
 */
class Category extends Model
{
    use NodeTrait;

    /**
     * @var string
     */
    protected $table = 'advert_categories';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'parent_id'];

    /**
     * @return string
     */
    public function getPath(): string
    {
        return implode('/', array_merge($this->ancestors()->defaultOrder()->pluck('slug')->toArray(), [$this->slug]));
    }

    /**
     * @return array
     */
    public function parentAttributes(): array
    {
        return $this->parent ? $this->parent->allAttributes() : [];
    }

    /**
     * @return Attribute[]
     */
    public function allAttributes(): array
    {
        return array_merge($this->parentAttributes(), $this->attributes()->orderBy('sort')->getModels());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }
}
