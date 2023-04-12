<?php

namespace App\Observers;

use App\Models\V1\UserWeeklyAttachment;
use Illuminate\Support\Facades\Auth;

class UserWeeklyAttachmentObserver
{
    /**
     * Handle the User "saving" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function saving(UserWeeklyAttachment $user_weekly_attachment)
    {
        $user_weekly_attachment->user_id = Auth::user()->id;
    }

    
    /**
     * Handle the UserWeeklyAttachment "created" event.
     */
    public function created(UserWeeklyAttachment $user_weekly_attachment): void
    {
        //
    }

    /**
     * Handle the UserWeeklyAttachment "updated" event.
     */
    public function updated(UserWeeklyAttachment $user_weekly_attachment): void
    {
        //
    }

    /**
     * Handle the UserWeeklyAttachment "deleted" event.
     */
    public function deleted(UserWeeklyAttachment $user_weekly_attachment): void
    {
        //
    }

    /**
     * Handle the UserWeeklyAttachment "restored" event.
     */
    public function restored(UserWeeklyAttachment $user_weekly_attachment): void
    {
        //
    }

    /**
     * Handle the UserWeeklyAttachment "force deleted" event.
     */
    public function forceDeleted(UserWeeklyAttachment $user_weekly_attachment): void
    {
        //
    }
}
