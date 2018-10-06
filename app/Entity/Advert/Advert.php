<?php

namespace App\Entity\Advert;

use App\Entity\Region;
use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int $region_id
 * @property string $title
 * @property string $content
 * @property int $price
 * @property string $address
 * @property string $status
 * @property string $reject_reason
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $published_at
 * @property Carbon $expires_at
 *
 * @property User $user
 * @property Region $region
 * @property Category $category
 * @property Value[] $values
 * @property Photo[] $photos
 * @method Builder active()
 * @method Builder forUser(User $user)
 */
class Advert extends Model
{
    /**
     *
     */
    public const STATUS_DRAFT = 'draft';
    /**
     *
     */
    public const STATUS_MODERATION = 'moderation';
    /**
     *
     */
    public const STATUS_ACTIVE = 'active';
    /**
     *
     */
    public const STATUS_CLOSED = 'closed';

    /**
     * @var string
     */
    protected $table = 'adverts';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * @return array
     */
    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_MODERATION => 'On Moderation',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    /**
     *
     */
    public function sendToModeration(): void
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Advert is not draft.');
        }
        if (!\count($this->photos)) {
            throw new \DomainException('Upload photos.');
        }
        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

    /**
     * @param Carbon $date
     */
    public function moderate(Carbon $date): void
    {
        if ($this->status !== self::STATUS_MODERATION) {
            throw new \DomainException('Advert is not sent to moderation.');
        }
        $this->update([
            'published_at' => $date,
            'expires_at' => $date->copy()->addDays(15),
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @param $reason
     */
    public function reject($reason): void
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    /**
     *
     */
    public function expire(): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }

    /**
     *
     */
    public function close(): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function getValue($id)
    {
        foreach ($this->values as $value) {
            if ($value->attribute_id === $id) {
                return $value->value;
            }
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * @return bool
     */
    public function isOnModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(Value::class, 'advert_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Photo::class, 'advert_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'advert_favorites', 'advert_id', 'user_id');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeForUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * @param Builder $query
     * @param Category $category
     * @return Builder
     */
    public function scopeForCategory(Builder $query, Category $category)
    {
        return $query->whereIn('category_id', array_merge(
            [$category->id],
            $category->descendants()->pluck('id')->toArray()
        ));
    }

    /**
     * @param Builder $query
     * @param Region $region
     * @return Builder
     */
    public function scopeForRegion(Builder $query, Region $region)
    {
        $ids = [$region->id];
        $childrenIds = $ids;
        while ($childrenIds = Region::where(['parent_id' => $childrenIds])->pluck('id')->toArray()) {
            $ids = array_merge($ids, $childrenIds);
        }
        return $query->whereIn('region_id', $ids);
    }

    /**
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeFavoredByUser(Builder $query, User $user)
    {
        return $query->whereHas('favorites', function(Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }
}
