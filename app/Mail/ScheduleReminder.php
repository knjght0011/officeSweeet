<?php

namespace App\Mail;

use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScheduleReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "0", "message_type": "ScheduleReminder"}');

        });

        $mainbranch = Branch::where('default', "1")->first();

        return $this->view('Emails.System.ScheduleReminder' , ['event' => $this->event, 'phonenumber' => $mainbranch->phonenumber])
                        ->subject("Appointment Reminder")
                        ->from('noreply@officesweeet.com');
    }
}
