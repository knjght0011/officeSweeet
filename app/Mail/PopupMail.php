<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PopupMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $sender
     * @param $subject
     * @param $body
     * @param $fileName
     * @param $attachment
     * @param string $type
     * @param int $email_id
     * @param null $token
     */
    public function __construct($sender, $subject, $body, $attachment, $type = "File", $email_id = 0, $token = null)
    {
        $this->sender = $sender;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachment = $attachment;
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

            if (app()->bound('account')) {
                $subdomain = app()->make('account')->subdomain;
            } else {
                $subdomain = "lls";
            }

            $message->getHeaders()
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "' . $subdomain . '", "message_id": "' . $this->email_id . '", "message_type": "' . $this->type . '"}');

        });
        if ($this->attachment)
        {
            return $this->from($this->sender, 'OfficeSweet: ' . $this->sender)
                ->view('Emails.Popup.send', ['body' => $this->body, 'token' => $this->token])
                ->subject($this->subject)
                ->attachData($this->attachment ,$this->type . ".pdf" , [
                    'mime' => 'application/pdf',
                ]);
        }

    else
        return $this->from($this->sender, 'OfficeSweet: ' . $this->sender)
            ->view('Emails.Popup.send', ['body' => $this->body, 'token' => $this->token])
            ->subject($this->subject);
    }
}
