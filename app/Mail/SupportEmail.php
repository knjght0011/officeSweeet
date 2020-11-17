<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupportEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $body, $subdomain)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->body = $body;
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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "0", "message_type": "SupportEmail"}');

        });

        return $this->from('support@officesweeet.com')
                    ->view('Emails.support' , ['body' => $this->body, 'email' => $this->email, 'subdomain' => $this->subdomain])
                    ->replyTo($this->email, $this->email)
                    ->subject($this->subject);
    }
}
