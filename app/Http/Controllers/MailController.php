<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public static function send($user){
        Mail::to($user)->send(new SendEmail("Administrator SKU PIDEL","Akun anda sudah dikonfirmasi oleh Administrator"));
    }
}
