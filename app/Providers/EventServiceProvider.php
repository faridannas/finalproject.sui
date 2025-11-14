<?php

namespace App\Providers;

use App\Events\OrderStatusUpdated;
use App\Listeners\SendOrderStatusNotification;
use Illuminate\Auth\Events\Login;
use App\Listeners\SendLoginNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderStatusUpdated::class => [
            SendOrderStatusNotification::class,
        ],
        Login::class => [
            SendLoginNotification::class,
        ],
    ];

    public function boot()
    {
        //
    }
}