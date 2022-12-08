<?php

namespace App\Providers;

use App\Events\EticketCreated;
use App\Listeners\SendEticketPdf;
use App\Listeners\SendEticketToMail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        EticketCreated::class => [
            SendEticketPdf::class,
            SendEticketToMail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
