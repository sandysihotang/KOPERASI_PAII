<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sub,$id;
    public function __construct($subject,$id)
    {
        $this->sub=$subject;
        $this->id=$id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $e_sub=$this->sub;
        $id=$this->id;
        return $this->view('mail.ForgotPassword',compact('id'))->subject($e_sub);
    }
}
