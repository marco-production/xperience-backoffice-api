<?php

namespace App\Listeners;

use App\Mail\EticketMail;
use App\Events\EticketCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEticketToMail
{
    /**
     * Handle the event.
     *
     * @param  EticketCreated  $event
     * @return void
     */
    public function handle(EticketCreated $event)
    {
        Mail::to($event->email)->send(new EticketMail($event->eticket, $event->locale));
    }
}
