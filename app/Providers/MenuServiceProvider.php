<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\SuperAdminMenuEvent;
use App\Events\CompanyMenuEvent;
use App\Listeners\SuperAdminMenuListener;
use App\Listeners\CompanyMenuListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;

class MenuServiceProvider extends Provider
{
    /**
     * Register services.
     */
    protected $listen = [
        SuperAdminMenuEvent::class => [
            SuperAdminMenuListener::class
        ],
        CompanyMenuEvent::class => [
            CompanyMenuListener::class
        ],
    ];
}
