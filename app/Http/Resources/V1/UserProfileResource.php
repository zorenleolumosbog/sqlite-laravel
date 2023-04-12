<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'gender' => $this->gender,
            'age' => $this->age,
            'height' => $this->height,
            'current_weight' => $this->current_weight,
            'desired_weight_goal' => $this->desired_weight_goal,
            'how_active_are_you' => $this->how_active_are_you,
            'hours_of_sleep_at_night' => $this->hours_of_sleep_at_night,
            'stress_level_out_of_10' => $this->stress_level_out_of_10,
            'medications_supplements' => $this->medications_supplements,
            'injuries_illnesses' => $this->injuries_illnesses,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }
}
