<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;
use App\Models\OS\PasswordReset;

class SchedulerDailyMail extends Mailable
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
    public function __construct(User $user, $events)
    {
        $this->user = $user;
        $this->events = $events;

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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "0", "message_type": "SchedulerDailyMail"}');

        });

        return $this->view('Emails.System.SchedulerDaily' , ['user' => $this->user, 'events' => $this->events])
                ->subject("OfficeSweeet Daily Events")
                ->from('noreply@officesweeet.com');
    }
}
