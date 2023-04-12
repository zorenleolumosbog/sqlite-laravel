<?php

namespace App\Observers;

use App\Models\V1\UserProfile;
use Illuminate\Support\Facades\Auth;

class UserProfileObserver
{
    /**
     * Handle the User "saving" event.
     *
     * @param  \App\Models\UserProfile  $user_profile
     * @return void
     */
    public function saving(UserProfile $user_profile)
    {
        $user_profile->user_id = Auth::user()->id;
    }

    /**
     * Handle the UserProfile "created" event.
     */
    public function created(UserProfile $user_profile): void
    {
        //
    }

    /**
     * Handle the UserProfile "updated" event.
     */
    public function updated(UserProfile $user_profile): void
    {
        //
    }

    /**
     * Handle the UserProfile "deleted" event.
     */
    public function deleted(UserProfile $user_profile): void
    {
        //
    }

    /**
     * Handle the UserProfile "restored" event.
     */
    public function restored(UserProfile $user_profile): void
    {
        //
    }

    /**
     * Handle the UserProfile "force deleted" event.
     */
    public function forceDeleted(UserProfile $user_profile): void
    {
        //
    }
}
