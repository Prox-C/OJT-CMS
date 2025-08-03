<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoordinatorSetupMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setupLink; // Define the public property

    /**
     * Create a new message instance.
     *
     * @param string $setupLink
     */
    public function __construct(string $setupLink)
    {
        $this->setupLink = $setupLink; // Assign the value
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Coordinator Account Setup')
                   ->markdown('emails.coordinator_setup');
    }
}