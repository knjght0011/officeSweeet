<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $content;
    public $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content, $email_id)
    {

        $fontarray = array(
            'Alegreya Sans' => 'alegreya-sans',
            'Alegreya' => 'alegreya',
            'Ariel' => 'ariel',
            'BioRhyme' => 'bioRhyme',
            'Black Ops One' => 'black-ops-one',
            'Bungee Shade' => 'bungee-shade',
            'Bungee' => 'bungee',
            'Cabin' => 'cabin',
            'Calligraffitti' => 'calligraffitti',
            'Charmonman' => 'charmonman',
            'Courier New' => 'courier-new',
            'Creepster' => 'creepster',
            'Dancing Script' => 'dancing-script',
            'Ewert' => 'ewert',
            'Fredericka the Great' => 'fredericka-the-great',
            'Fruktur' => 'fruktur',
            'Georgia' => 'georgia',
            'Gravitas One' => 'gravitas-one',
            'Homemade Apple' => 'homemade-apple',
            'IBM Plex Mono' => 'ibm-plex-mono',
            'IBM Plex Sans Condensed' => 'ibm-plex-sans-condensed',
            'IBM Plex Sans' => 'ibm-plex-sans',
            'IBM Plex Serif' => 'ibm-plex-serif',
            'Inconsolata' => 'inconsolata',
            'Indie Flower' => 'indie-flower',
            'Italianno' => 'italianno',
            'Loved by the King' => 'loved-by-the-king',
            'Lucida Sans Unicode' => 'lucida-sans-unicode',
            'Merriweather Sans' => 'merriweather-sans',
            'Merriweather' => 'merriweather',
            'Monoton' => 'monoton',
            'Nanum Brush Script' => 'nanum-brush-script',
            'Nanum Pen Script' => 'nanum-pen-script',
            'Nunito Sans' => 'nunito-sans',
            'Nunito' => 'nunito',
            'Pacifico' => 'pacifico',
            'Quattrocento Sans' => 'quattrocento-sans',
            'Quattrocento' => 'quattrocento',
            'Quicksand' => 'quicksand',
            'Roboto Mono' => 'roboto-mono',
            'Roboto Slab' => 'roboto-slab',
            'Roboto' => 'roboto',
            'Rubik' => 'rubik',
            'Satisfy' => 'satisfy',
            'Tahoma' => 'tahoma',
            'Times New Roman' => 'times-new-roman',
            'Trebuchet MS' => 'trebuchet-ms',
            'Ubuntu' => 'vbuntu',
            'Verdana' => 'verdana',
            'VT323' => 'vt323',
        );

        $css = "";

        foreach ($fontarray as $key => $value) {
            $css = $css . ".ql-font-" .  $value . " {font-family: '" . $key . "';} ";
        }

        $emogrifier = new \Pelago\Emogrifier();
        $emogrifier->setHtml($content);
        $emogrifier->setCss($css);

        $this->subject = $subject;
        $this->content = $emogrifier->emogrify();
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
                ->addTextHeader('X-Mailgun-Variables', '{"subdomain": "'. $subdomain .'", "message_id": "' . $this->email_id . '", "message_type": "EmailTemplate"}');

        });

        return $this->view('Emails.Customer.emailtemplate' , ['content' => $this->content])
                    ->subject($this->subject)
                    ->from('noreply@officesweeet.com');
    }
}
