<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\EticketCreated;

class SendEticket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eticket, $email, $locale;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $eticket, $locale)
    {
        $this->email = $email;
        $this->eticket = $eticket;
        $this->locale = $locale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new EticketCreated($this->email, $this->eticket, $this->locale));
    }
}
