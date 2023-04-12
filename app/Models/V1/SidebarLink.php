<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SidebarLink extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'link',
        'bg_color',
        'name',
        'file_name',
        'mime_type',
        'path',
        'url',
        'size'
    ];

    protected $casts = [
        'id' => 'string',
        'size' => 'string',
    ];
}
