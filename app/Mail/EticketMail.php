<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class EticketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $eticket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($eticket, $locale)
    {
        $this->eticket = $eticket;
        App::setLocale($locale);
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function build()
    {
        return $this->subject(__('eticket_pass'))
               ->view('emails.eticket');
    }
}
