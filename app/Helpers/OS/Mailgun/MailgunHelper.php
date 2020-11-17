<?php
namespace App\Helpers\OS\Mailgun;

//use Bogardo\Mailgun;
use App\Mail\AudioBook;
use Bogardo\Mailgun\Facades\Mailgun;
use Illuminate\Support\Facades\Mail;

class MailgunHelper
{
    public static function SendEmail()
    {
        $data = array(
            'token' => null,
            'body' => 'test123',
            'type' => 'test321'
        );


        Mail::to("staylor@officesweeet.com")
            ->send(new AudioBook());

    }


}