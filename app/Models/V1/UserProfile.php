<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'gender',
        'age',
        'height',
        'current_weight',
        'desired_weight_goal',
        'how_active_are_you',
        'hours_of_sleep_at_night',
        'stress_level_out_of_10',
        'medications_supplements',
        'injuries_illnesses'
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'age' => 'string',
        'current_weight' => 'string',
        'desired_weight_goal' => 'string',
        'hours_of_sleep_at_night' => 'string',
        'stress_level_out_of_10' => 'string',
    ];
}
