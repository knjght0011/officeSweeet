<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;
use App\Models\OS\PasswordReset;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reset;
    public $subdomain;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, PasswordReset $reset, $subdomain)
    {
        $this->user = $user;
        $this->reset = $reset;
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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "0", "message_type": "PasswordResetMail"}');

        });

        return $this->view('Emails.passwordreset' , ['user' => $this->user, 'reset' => $this->reset, 'subdomain' => $this->subdomain])
                ->subject("OfficeSweeet Password Reset")
                ->from('noreply@officesweeet.com');
    }
}
