<?php

namespace App\Providers;

use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Record logout time when user logs out
        $this->app['events']->listen(Logout::class, function ($event) {
            if ($event->user) {
                $latestActivity = $event->user->loginActivities()
                    ->whereNull('logout_at')
                    ->latest('login_at')
                    ->first();

                if ($latestActivity) {
                    $latestActivity->update(['logout_at' => now()]);
                }
            }
        });
    }
}
