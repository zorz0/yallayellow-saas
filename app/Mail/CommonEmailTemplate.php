<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommonEmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $settings;
    public $store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($template, $settings, $store)
    {
        $this->template = $template;
        $this->settings = $settings;
        $this->store = $store;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    // public function build()
    // {
    //     $from = !empty($this->settings['MAIL_FROM_ADDRESS']) ? $this->settings['MAIL_FROM_ADDRESS'] : $this->template->from;
    //     return  $this->from($this->store['email'], $from)->markdown('email.common_email_template')->subject($this->template->subject)->with(
    //         [
    //             'content' => $this->template->content,
    //             'mail_header' => $this->store['name'],
    //         ]
    //     );

    // }

    public function build()
    {
        $from = !empty($this->settings['MAIL_FROM_ADDRESS']) ? $this->settings['MAIL_FROM_ADDRESS'] : $this->template->from;
        return
        $this
            // ->from($this->store['email'], $from)
            ->from($this->settings['MAIL_FROM_ADDRESS'])
            ->subject($this->template->subject)
            ->markdown('email.common_email_template')
            ->with(
                [
                    'content' => $this->template->content,
                    'mail_header' => $this->store['name'],
                ]
            );
    }

}
