<?php

namespace App\Models\OS\Email;

use App\Mail\File;
use App\Mail\Notification;
use App\Mail\Signing\SendDocument;
use App\Mail\Signing\SigningRequest;
use App\Models\ClientContact;
use App\Models\OS\PurchaseOrders\PurchaseOrder;
use App\Models\OS\Report;
use App\Models\OS\Templates\Signing;
use App\Models\User;
use App\Models\VendorContact;
use App\Mail\PopupMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

use App\Mail\SendQuote;
use App\Mail\EmailTemplate;

use App\Models\Client;
use App\Models\Quote;
use App\Models\OS\Email\EmailTemplate as EmailTemplateModal;

use App\Helpers\OS\Financial\ClientOverviewHelper;

class Email extends Model
{
    public $linked_model = null;
    public $linked_contact = null;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $length = 32;

            $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            $str = '';
            $max = strlen($chars) - 1;

            for ($i=0; $i < $length; $i++){
                $str .= $chars[random_int(0, $max)];
            }

            $instance->token = $str;
        });

        static::retrieved(function ($instance) {
            if($instance->token === null){
                $length = 32;

                $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                $str = '';
                $max = strlen($chars) - 1;

                for ($i=0; $i < $length; $i++){
                    $str .= $chars[random_int(0, $max)];
                }

                $instance->token = $str;

                $instance->save();
            }
        });
    }

    public function events()
    {
        return $this->hasMany('App\Models\OS\Email\MailGunEvent', 'emails_id', 'id');
    }

    public function Attachment(){
        return base64_decode($this->attachment);
    }

    public function Status(){

        if(count($this->events) === 0){
            return "Pending";
        }else{
            return $this->events->last()->getDescription();
        }

    }

    public function Send(){

        switch ($this->type) {
            case "Quote":
                $this->SendFile();
                //$this->SendQuote($this->email, $this->body, $this->subject, $this->GetLink());
                break;

            case "Invoice":
                $this->SendQuote($this->email, $this->body, $this->subject, $this->GetLink());
                break;

            case "Overview":
                $this->SendFile();
                break;

            case "EmailTemplate":
                $this->SendEmailTemplate();
                break;

            case "SigningRequest":
                $this->SendSigningRequest();
                break;

            case "Notification":
                $this->SendNotification();
                break;

            case "SignedDocument":
                $this->SendFile();
                break;

            case "Document":
                $this->SendFile();
                break;

            case "PurchaseOrder":
                $this->SendFile();
                break;

            default:
                return "Error";
        }

    }

    public function SendPopup(){

        switch ($this->type) {

            case "ReplyEmail":
                $this->SendPopupMail();
                break;

            case "EmailFromPopupModalToClient":
                $this->SendPopupMail();
                break;

            case "EmailFromPopupModalToEmployee":
                $this->SendPopupMail();
                break;

            case "EmailFromPopupModalToVendor":
                $this->SendPopupMail();
                break;

            default:
                return "Error";
        }

    }

    public function GetLink(){

        if($this->linked_id != null){
            if($this->linked_model === null){
                switch ($this->type) {
                    case "Quote":
                        $this->linked_model = Quote::where('id', $this->linked_id)->where('finalized', 0)->first();
                        return $this->linked_model;

                    case "Invoice":
                        $this->linked_model = Quote::where('id', $this->linked_id)->where('finalized', 1)->first();
                        return $this->linked_model;

                    case "Overview":
                        $this->linked_model = Client::where('id', $this->linked_id)->first();
                        return $this->linked_model;

                    case "EmailTemplate":
                        $this->linked_model = EmailTemplateModal::where('id', $this->linked_id)->first();
                        return $this->linked_model;

                    case "SigningRequest":
                        $this->linked_model = Signing::where('id', $this->linked_id)->first();
                        return $this->linked_model;

                    case "Notification":
                        $this->linked_model = Notification::where('id', $this->linked_id)->first();
                        return $this->linked_model;

                    case "SignedDocument":
                        $this->linked_model = Signing::where('id', $this->linked_id)->first();
                        return $this->linked_model;

                    case "Document":
                        $this->linked_model = Report::where('id', $this->linked_id)->first();
                        return $this->linked_model;

                    case "PurchaseOrder":
                        $this->linked_model = PurchaseOrder::where('id', $this->linked_id)->first();
                        return $this->linked_model;

                    default:
                        return null;
                }
            }else{
               return $this->linked_model;
            }
        }else{
            return null;
        }
    }

    public function GetContact(){

        if($this->contact_id != null){
            if($this->linked_contact === null){
                switch ($this->contact_type) {
                    case "Client":
                        $this->linked_contact = ClientContact::where('id', $this->contact_id)->first();
                        return $this->linked_contact;

                    case "Vendor":
                        $this->linked_contact = VendorContact::where('id', $this->contact_id)->first();
                        return $this->linked_contact;

                    case "User":
                        $this->linked_contact = User::where('id', $this->contact_id)->first();
                        return $this->linked_contact;

                    default:
                        return null;
                }
            }else{
                return $this->linked_contact;
            }
        }else{
            return null;
        }

    }

    //makes sure you save the record before calling else you wont have an ID
    private function SendQuote($email, $body, $subject, $quote){

        if(count($quote) === 1){
            Mail::to($email)->send(new SendQuote($body, $subject, $quote->PDF(), $quote->token, $quote->getType(), $this->id ));
            return true;
        }else{
            return false;
        }

    }

    private function SendOverview($pdf){
        Mail::to($this->email)->send(new SendQuote($this->body, $this->subject, $pdf, null, "Statement", $this->id ));
    }

    private function SendEmailTemplate(){
        Mail::to($this->email)->send(new EmailTemplate($this->subject, $this->body, $this->id));
    }

    private function SendSigningRequest(){
        Mail::to($this->email)->send(new SigningRequest($this->GetLink(), $this->GetContact(), $this->id));
    }

    private function SendFile(){
        Mail::to($this->email)->send(new File($this->subject, $this->body, $this->Attachment(), $this->type, $this->id, $this->token));
    }

    private function SendNotification(){
        Mail::to($this->email)->send(new File($this->subject, $this->body, $this->type, $this->id, $this->token));
    }

    private function SendPopupMail(){
        Mail::to($this->email)->send(new PopupMail($this->sender, $this->subject, $this->body,$this->Attachment(), $this->type, $this->id, $this->token));
    }
}
