<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $account;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password, $sender, $subdomain)
    {
        $this->user = $user;
        $this->password = $password;
        $this->sender = $sender;
        $this->subdomain = $subdomain;
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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "0", "message_type": "UserInvite"}');

        });

        return $this->view('Emails.Customer.userinvite' , ['user' => $this->user, 'sender' => $this->sender, 'password' => $this->password, 'subdomain' => $this->subdomain])
                ->subject("Welcome to OfficeSweeet!")
                ->from('support@officesweeet.com');
    }
}
