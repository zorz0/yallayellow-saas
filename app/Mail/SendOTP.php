<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOTP extends Mailable
{
    use Queueable, SerializesModels;
    public $OTP;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($OTP)
    {
        $this->OTP = $OTP;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $otp = $this->OTP;
        return $this->view('email.sendOTP', compact('otp'));
    }
}
