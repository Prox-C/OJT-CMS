<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InternSetupMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setupLink;
    public $internName;
    public $tempPassword;

    /**
     * Create a new message instance.
     */
    public function __construct($setupLink, $internName, $tempPassword)
    {
        $this->setupLink = $setupLink;
        $this->internName = $internName;
        $this->tempPassword = $tempPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Intern Account Activation')
                   ->markdown('emails.intern_setup');
    }
}