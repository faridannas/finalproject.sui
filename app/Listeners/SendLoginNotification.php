<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLoginNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $notificationService = app(NotificationService::class);
        $notificationService->notifySuccessLogin($event->user);
    }
}
