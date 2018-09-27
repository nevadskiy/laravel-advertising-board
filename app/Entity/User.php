<?php

namespace App\Entity;

use App\Entity\Advert\Advert;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property bool $phone_auth
 * @property bool $phone_verified
 * @property string $password
 * @property string $verify_token
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 * @property string $role
 * @property string $status
 */
class User extends Authenticatable
{
    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR = 'moderator';

    protected $fillable = [
        'name', 'last_name', 'email', 'phone', 'password', 'status', 'verify_token', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token', 'verify_token', 'status', 'role'
    ];

    protected $casts = [
        'phone_verified' => 'boolean',
        'phone_auth' => 'boolean'
    ];

    protected $dates = [
        'phone_verify_token_expire'
    ];

    public static function rolesList(): array
    {
        return [
            self::ROLE_USER => 'User',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MODERATOR => 'Moderator',
        ];
    }

    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_token' => Str::random(),
            'status' => self::STATUS_WAIT,
            'role' => self::ROLE_USER
        ]);
    }

    public static function generate(string $name, string $email): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random()),
            'status' => self::STATUS_ACTIVE,
            'role' => self::ROLE_USER
        ]);
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already verified.');
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null
        ]);
    }

    public function changeRole($role): void
    {
        if (!\array_key_exists($role, self::rolesList())) {
            throw new \InvalidArgumentException('Undefined role "' . $role . '"');
        }

        if ($this->role === $role) {
            throw new \DomainException('Role is already assigned.');
        }

        $this->update(['role' => $role]);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    public function unverifyPhone(): void
    {
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->save();
    }

    public function requestPhoneVerification(Carbon $time): string
    {
        if (empty($this->phone)) {
            throw new \DomainException('Phone number is empty');
        }

        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire && $this->phone_verify_token_expire->gt($time)) {
            throw new \DomainException('Token is already requested');
        }

        $this->phone_verified = false;
        $this->phone_verify_token = (string) random_int(10000, 99999);
        $this->phone_verify_token_expire = $time->copy()->addSeconds(300);
        $this->save();

        return $this->phone_verify_token;
    }

    public function verifyPhone(string $token, Carbon $time): void
    {
        if ($token !== $this->phone_verify_token) {
            throw new \DomainException('Incorrect verify token');
        }

        if ($this->phone_verify_token_expire->lt($time)) {
            throw new \DomainException('Token is expired');
        }

        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->save();
    }

    public function isPhoneVerified(): bool
    {
        return $this->phone_verified && empty($this->phone_verify_token) && empty($this->phone_verify_token_expire);
    }

    public function isPhoneAuthEnabled(): bool
    {
        return $this->phone_auth;
    }

    public function disablePhoneAuth()
    {
        $this->phone_auth = false;
        $this->save();
    }

    public function enablePhoneAuth()
    {
        $this->phone_auth = true;
        $this->save();
    }

    public function hasFilledProfile()
    {
        return !empty($this->name) && !empty($this->last_name) && $this->isPhoneVerified();
    }

    public function favorites()
    {
        return $this->belongsToMany(Advert::class, 'advert_favorites', 'user_id', 'advert_id');
    }

    public function hasInFavorites($id): bool
    {
        return $this->favorites()->where('id', $id)->exists();
    }

    public function addToFavorites($id)
    {
        if ($this->hasInFavorites($id)) {
            throw new \DomainException('This advert has been already added to favorites');
        }

        $this->favorites()->attach($id);
    }

    public function removeFromFavorites($id): void
    {
        if (!$this->hasInFavorites($id)) {
            throw new \DomainException('This advert has not been added to favorites');
        }

        $this->favorites()->detach($id);
    }
}
