<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserWeeklyAttachment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'weight',
        'description',
        'week_number'
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'weight' => 'string',
        'week_number' => 'string',
    ];

    /**
     * Get the weekly attachment details associated with the weekly attachment.
     */
    public function weeklyAttachmentDetails(): HasMany
    {
        return $this->hasMany(UserWeeklyAttachmentDetail::class);
    }
}
