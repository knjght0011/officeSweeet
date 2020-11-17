<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $body, $type = "Notification", $email_id = 0, $token = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->type = $type;
        $this->email_id = $email_id;
        $this->token = $token;
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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "' . $this->email_id . '", "message_type": "' . $this->type . '"}');

        });

        return $this->from('noreply@officesweeet.com')
            ->view('Emails.Customer.file' , ['body' => $this->body, 'token' => $this->token])
            ->subject($this->subject);
    }
}
