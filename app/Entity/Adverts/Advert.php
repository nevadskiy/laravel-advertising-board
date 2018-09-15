<?php

namespace App\Entity\Adverts;

use App\Entity\Region;
use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

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
 * @property Carbon $expires_at
 * @property Carbon $published_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Advert extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';

    protected $table = 'adverts';

    protected $guarded = ['id'];

    protected $dates = [
        'published_at',
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function region()
    {
        $this->belongsTo(Region::class, 'region_id ', 'id');
    }

    public function values()
    {
        return $this->hasMany(Value::class, 'advert_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'advert_id', 'id');
    }

    public function sendToModeration(): void
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Advert is not draft.');
        }

        if (!$this->photos()->count()) {
            throw new \DomainException('Upload photos');
        }

        $this->update([
            'status' => self::STATUS_MODERATION
        ]);
    }

    public function moderate(Carbon $date): void
    {
        if (!$this->status === self::STATUS_MODERATION) {
            throw new \DomainException('Advert is not sent to moderation');
        }

        $this->update([
            'published_at' => $date,
            'expires_at' => $date->copy()->addDays(15),
            'status' => self::STATUS_MODERATION,
        ]);
    }

    public function reject($reason): void
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
            'reject_reason' => $reason
        ]);
    }

    public function scopeForUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeForRegion(Builder $query, Region $region)
    {
        $ids = [$region->id];
        $childrenIds = $ids;
        while ($childrenIds = Region::where(['parent_id' => $childrenIds])->pluck('id')->toArray()) {
            $ids = array_merge($ids, $childrenIds);
        }

        return $query->where('region_id', $ids);
    }

    public function scopeForCategory(Builder $query, Category $category)
    {
        return $query->where(
            'category_id',
            array_merge([$category->id], $category->descendants()->pluck('id')->toArray())
        );
    }
}
