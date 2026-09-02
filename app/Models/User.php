<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'status',
        'last_active_at',
        'last_login_at',
        'profile_completed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active_at' => 'datetime',
            'last_login_at' => 'datetime',
            'profile_completed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function preferences(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProfilePhoto::class)->orderBy('sort_order', 'asc');
    }

    public function primaryPhoto(): HasOne
    {
        return $this->hasOne(ProfilePhoto::class)->where('is_primary', true);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function sentLikes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function receivedLikes(): HasMany
    {
        return $this->hasMany(Like::class, 'liked_user_id');
    }

    public function passes(): HasMany
    {
        return $this->hasMany(Pass::class, 'user_id');
    }

    public function matchesAsOne(): HasMany
    {
        return $this->hasMany(MatchModel::class, 'user_one_id');
    }

    public function matchesAsTwo(): HasMany
    {
        return $this->hasMany(MatchModel::class, 'user_two_id');
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['active', 'grace_period'])
            ->where('ends_at', '>', Carbon::now())
            ->latestOfMany();
    }

    public function activeBoost(): HasOne
    {
        return $this->hasOne(Boost::class)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->latestOfMany();
    }

    public function blocksInitiated(): HasMany
    {
        return $this->hasMany(Block::class, 'blocker_id');
    }

    public function blocksReceived(): HasMany
    {
        return $this->hasMany(Block::class, 'blocked_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationModel::class, 'user_id')->latest();
    }

    public function followers(): HasMany
    {
        return $this->hasMany(UserFollow::class, 'following_id');
    }

    public function following(): HasMany
    {
        return $this->hasMany(UserFollow::class, 'follower_id');
    }

    public function isFollowedBy(User|int $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;
        return $this->followers()->where('follower_id', $userId)->exists();
    }

    public function isFollowingUser(User|int $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;
        return $this->following()->where('following_id', $userId)->exists();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isDeleted(): bool
    {
        return $this->status === 'deleted';
    }
}
