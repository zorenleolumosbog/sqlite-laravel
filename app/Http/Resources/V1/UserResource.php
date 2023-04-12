<?php

namespace App\Http\Resources\V1;

use App\Models\V1\UserWeeklyAttachment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $weekly_attachment = UserWeeklyAttachment::where('user_id', $this->id)->latest()->first();

        $percentage = 0;
        if($this->profile()->first() && $weekly_attachment) {
            $percentage = (floatval($this->profile()->first()->desired_weight_goal) / floatval($weekly_attachment->weight)) * 100;
        } elseif($this->profile()->first()) {
            $percentage = (floatval($this->profile()->first()->desired_weight_goal) / floatval($this->profile()->first()->current_weight)) * 100;
        }

        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'desired_weight_goal_percentage' => (string) $percentage,
            'is_admin' => (string) $this->is_admin,
            'logged_in_at' => $this->logged_in_at,
            'telegram_link_url' => $this->telegram_link_url,
            'profile' => $this->profile()->first(),
            'latest_weekly_attachments' => $this->latestWeeklyAttachment()->with('weeklyAttachmentDetails')->first(),
            'telegram_link' => $this->telegramLink()->first(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }
}
