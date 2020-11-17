<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendQuote extends Mailable
{
    use Queueable, SerializesModels;
    public $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body, $subject, $file, $token, $type, $email_id = 0)
    {
        $this->file = $file;
        $this->subject = $subject;
        $this->body = $body;
        $this->token = $token;
        $this->type = $type;
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

            if($this->type === "Statement"){
                $type = "Overview";
            }else{
                $type = "SendQuote";
            }

            $message->getHeaders()
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "' . $this->email_id . '", "message_type": "'. $type .'"}');

        });


        return $this->from('admin@officesweeet.com')
                    ->view('Emails.Customer.quote' , ['body' => $this->body, 'token' => $this->token, 'type' => $this->type])
                    ->subject($this->subject)
                    ->attachData($this->file->output() , 'quote.pdf', [
                            'mime' => 'application/pdf',
                        ]);
    }
}
