<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserWeeklyAttachmentDetail extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_weekly_attachment_id',
        'name',
        'file_name',
        'mime_type',
        'path',
        'url',
        'size',
        'description'
    ];

    protected $casts = [
        'id' => 'string',
        'user_weekly_attachment_id' => 'string',
        'size' => 'string',
    ];
}
