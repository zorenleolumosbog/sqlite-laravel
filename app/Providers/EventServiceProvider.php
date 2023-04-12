<?php

namespace App\Providers;

use App\Models\User;
use App\Models\V1\UserProfile;
use App\Models\V1\UserWeeklyAttachment;
use App\Observers\UserObserver;
use App\Observers\UserProfileObserver;
use App\Observers\UserWeeklyAttachmentObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        UserProfile::observe(UserProfileObserver::class);
        UserWeeklyAttachment::observe(UserWeeklyAttachmentObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
