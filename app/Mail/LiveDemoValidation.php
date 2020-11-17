<?php

namespace App\Mail;

use App\Models\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LiveDemoValidation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "0", "message_type": "LiveDemoValidation"}');

        });

        return $this->view('Emails.System.livedemoaccess' , ['user' => $this->user])
            ->subject("OfficeSweeet Live Demo Access")
            ->from('noreply@officesweeet.com');
    }
}
