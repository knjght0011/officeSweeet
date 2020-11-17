<?php

namespace App\Mail\Signing;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDocument extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($signing, $subject, $body, $email_id = 0)
    {
        $this->signing = $signing;
        $this->subject = $subject;
        $this->body = $body;
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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "' . $this->email_id . '", "message_type": "SendDocument"}');

        });

        return $this->from('noreply@officesweeet.com')
            ->view('Emails.Customer.document' , ['body' => $this->body, 'signing' => $this->signing])
            ->subject($this->subject)
            ->attachData($this->signing->File() ,$this->signing->name . ".pdf" , [
                'mime' => 'application/pdf',
            ]);

    }
}
