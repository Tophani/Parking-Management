<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Promocode extends Mailable
{
    use Queueable, SerializesModels;

    public $title  = "Title";
    public $footer = "Footer";
    public $url    = "";
    public $from;
    public $subject;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($data)
    { 
        $this->title   = $data['title'];
        $this->footer  = $data['footer'];
        $this->url     = $data['url'];
        $this->from    = $data['from'];
        $this->subject = $data['subject'];
        $this->message = $data['message'];
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->from)
            ->markdown('emails.promocode');
    }
}
