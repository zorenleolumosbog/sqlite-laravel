<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\V1\Option;
use App\Models\V1\TelegramLink;
use App\Models\V1\UserProfile;
use App\Models\V1\UserWeeklyAttachment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'telegram_link_id',
        'name',
        'email',
        'password',
        'email_verified_at',
        'is_admin',
        'telegram_link_url',
        'logged_in_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
        'logged_in_at' => 'datetime',
    ];

    /**
     * Get the profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the latest weekly attachments associated with the user.
     */
    public function latestWeeklyAttachment(): HasOne
    {
        return $this->hasOne(UserWeeklyAttachment::class)->latest();
    }

    /**
     * Get the telegram link associated with the user.
     */
    public function telegramLink(): BelongsTo
    {
        return $this->belongsTo(TelegramLink::class);
    }
}
