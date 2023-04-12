<?php

namespace App\Http\Resources\V1;

use App\Models\V1\Option;
use App\Models\V1\UserWeeklyAttachment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserWeeklyAttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user_profile = Auth::user()->profile()->first();

        $percentage = 0;
        if($user_profile) {
            $percentage = (floatval($user_profile->desired_weight_goal) / floatval($this->weight)) * 100;
        }

        $start_datetime = Option::where('name', 'start_datetime')->first()?->value;
        $end_datetime = Option::where('name', 'end_datetime')->first()?->value;

        $status = UserWeeklyAttachment::when($start_datetime && $end_datetime, function ($query) use($start_datetime, $end_datetime) {
                    $query->whereDate('created_at', '>=', $start_datetime)
                            ->whereDate('created_at', '<=', $end_datetime);
                })
                ->where('id', $this->id)
                ->first();

        return [
            'id' => $this->id,
            'lock' => $status ? false : true,
            'weight' => $this->weight,
            'description' => $this->description,
            'week_number' => $this->week_number,
            'desired_weight_goal_percentage' => (string) $percentage,
            'weekly_attachment_details' => UserWeeklyAttachmentDetailResource::collection($this->weeklyAttachmentDetails),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }
}
