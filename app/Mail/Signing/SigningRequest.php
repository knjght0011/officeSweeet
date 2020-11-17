<?php

namespace App\Mail\Signing;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SigningRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($signing, $contact, $email_id = 0)
    {
        $this->signing = $signing;
        $this->contact = $contact;
        $this->email_id = $email_id;
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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "' . $this->email_id . '", "message_type": "SigningRequest"}');

        });

        return $this->from('noreply@officesweeet.com')
                    ->view('Emails.Customer.signingrequest' , ['contact' => $this->contact, 'signing' => $this->signing])
                    ->subject("Document Signing Request");
    }
}
