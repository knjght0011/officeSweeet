<?php

namespace App\Models\OS\Templates;

use App\Mail\Signing\SendDocument;
use App\Mail\Signing\SigningRequest;

use App\Models\OS\Email\Email;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Signing extends Model
{

    protected $connection = 'subdomain';
    protected $table = 'signing';

    protected $casts = [
        'positions' => 'array',
    ];

    public function pages()
    {
        return $this->hasMany('App\Models\OS\Templates\SigningPage', 'signing_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function setTokenAttribute($value)
    {
        if($value === null){
            $this->attributes['token']  = null;
        }else{
            $length = 16;

            $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            $str = '';
            $max = strlen($chars) - 1;

            for ($i=0; $i < $length; $i++){
                $str .= $chars[random_int(0, $max)];
            }

            $this->attributes['token']  = $str;
        }
    }

    public function File(){
        return base64_decode($this->file);
    }

    public function SendMeToNew($contact){

        $email = new Email;
        $email->email = $contact->email;
        $email->subject = "Document Signing Complete";
        $email->body = "Document Signing is complete, Here is a copy for your records.";
        $email->type = "SignedDocument";
        $email->attachment = $this->file;
        $email->linked_id = $this->id;

        if ($contact instanceof User) {
            $email->contact_type = "User";
        }else{
            $email->contact_type = "Client";
        }

        $email->contact_id = $contact->id;
        $email->save();
        $email->Send();
    }

    public function SendMeTo($contact){

    $email = new Email;
    $email->email = $contact->email;
    $email->subject = "Document Signing Complete";
    $email->body = "Document Signing is complete, Here is a copy for your records.";
    $email->type = "SendDocument";
    $email->linked_id = $this->id;

    if ($contact instanceof User) {
        $email->contact_type = "User";
    }else{
        $email->contact_type = "Client";
    }

    $email->contact_id = $contact->id;
    $email->save();
    $email->Send();
}

    public function SendSigningRequest($contact){

        $email = new Email;
        $email->email = $contact->email;
        $email->subject = "Document Signing Request";
        $email->body = "";
        $email->type = "SigningRequest";
        $email->linked_id = $this->id;

        if ($contact instanceof User) {
            $email->contact_type = "User";
        }else{
            $email->contact_type = "Client";
        }

        $email->contact_id = $contact->id;
        $email->save();
        $email->Send();

    }

    public function GetUserSentBy(){
        return $this->createdby->name;
    }

    public function Contacts(){
        if($this->client_id != null){
            return $this->client->contacts;
        }else{
            if($this->vendor_id != null){
                return $this->vendor->contacts;
            }else{
                return [$this->user];
            }
        }
    }

    public function AllSignees(){

        $ids = [];
        $contacts = [];

        foreach($this->pages as $page){
            foreach($page->signatures as $signature){

                $signee = $signature->Signee();
                if($signee != null){
                    if(!in_array($signee->id, $ids)) {
                        $ids[] = $signee->id;
                        $contacts[] = $signee;
                    }
                }
            }
        }

        return $contacts;
    }

    public function HasContact($token, $markseen = false){

        $contact = null;
        foreach($this->pages as $page){
            foreach($page->signatures as $signature){
                $signee = $signature->Signee();
                if($signee != null){
                    if($signee->token === $token){
                        if($markseen){
                            $signature->seen = Carbon::now();
                            $signature->save();
                        }

                        $contact = $signee;
                        break;
                    }
                }
            }
        }

        return $contact;

    }

    public function AllApproved(){
        $ready = true;
        foreach($this->pages as $page){
            foreach($page->signatures as $signature){
                if($signature->image === null){
                    $ready = false;
                }
            }
        }

        return $ready;
    }

    public function LinkToLink(){
        if($this->client_id != null){
            return "/Clients/View/" . $this->client_id . "/file";
        }else{
            if($this->vendor_id != null){
                return "/Vendors/View/" . $this->vendor_id . "/file";
            }else{
                return "/Employees/View/" . $this->user_id . "/email-docs";
            }
        }
    }

    public function Status(){
        switch ($this->sign) {
            case 0:
            return "Sent";
        break;
            case 1:
            return "<button class='OS-Button btn'  data-toggle='modal' data-target='#signing-status-". $this->id . "' >View Status</button>";
        break;
            case 2:
            return "Finalized";
        break;
            default:
            return "Unknown";
        }
    }

}
