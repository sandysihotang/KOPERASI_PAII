<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $sub,$mes;
    public function __construct($subject,$mail)
    {
        $this->sub=$subject;
        $this->mes=$mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $e_sub=$this->sub;
        $e_mes=$this->mes;
        return $this->view('mail.send',compact('e_mes'))->subject($e_sub);
    }
}
