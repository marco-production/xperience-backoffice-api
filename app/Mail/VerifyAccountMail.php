<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class VerifyAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code, $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $name, $locale)
    {
        $this->code = $code;
        $this->name = $name;
        App::setLocale($locale);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('account_verification'))->view('emails.email_verification');
    }
}
