<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AudioBook extends Mailable
{
    use Queueable, SerializesModels;

    public $account;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        #$this->account = $account;
        #$this->email = $email;
        #$this->password = $password;
        #$this->subdomain = $subdomain;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        $this->withSwiftMessage(function ($message) {

            if(app()->bound('account')){
                $subdomain = app()->make('account')->subdomain;
            }else{
                $subdomain = "lls";
            }

            $message->getHeaders()
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "0", "message_type": "AudioBook"}');

        });

        return $this->view('Emails.blank')
                ->subject("Your Free Audio Book")
                ->from('support@officesweeet.com');
    }
}
