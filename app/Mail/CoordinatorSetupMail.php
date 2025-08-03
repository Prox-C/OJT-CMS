<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoordinatorSetupMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setupLink;
    public $coordinatorName;
    public $tempPassword;

    public function __construct($setupLink, $coordinatorName, $tempPassword = null)
    {
        $this->setupLink = $setupLink;
        $this->coordinatorName = $coordinatorName;
        $this->tempPassword = $tempPassword;
    }

    public function build()
    {
        return $this->subject('Your Coordinator Account Activation')
                   ->markdown('emails.coordinator_activation');
    }
}