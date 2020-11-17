<?php

namespace App\Models\OS\Templates;

use Illuminate\Database\Eloquent\Model;

use App\Helpers\OS\FileStoreHelper;

class Signature extends Model
{

    protected $connection = 'subdomain';
    protected $table = 'signature';

    protected $casts = [
        'signeddate' => 'date',
        'seen' => 'datetime',
        'positions' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo('App\Models\OS\Templates\SigningPage', 'signingpage_id', 'id');
    }

    public function clientcontact()
    {
        return $this->belongsTo('App\Models\ClientContact', 'clientcontact_id', 'id');
    }

    public function vendorcontact()
    {
        return $this->belongsTo('App\Models\VendorContact', 'vendorcontact_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function Signee(){
        if($this->clientcontact_id != null){
            return $this->clientcontact;
        }
        if($this->vendorcontact_id != null){
            return $this->vendorcontact;
        }
        if($this->user_id != null){
            return $this->user;
        }
        return null;
    }

    public function SigneeID(){
        if($this->clientcontact_id != null){
            return $this->clientcontact_id;
        }else if($this->vendorcontact_id != null){
            return $this->vendorcontact_id;
        }else if($this->user_id != null){
            return $this->user_id;
        }else{
            return "";
        }
    }


    public function Signature(){
        if($this->signature === null){
            return "";
        }else{
            return $this->signature;
        }
    }

    public function CheckTokens($signtoken, $contacttoken){

        if($this->page->signing->token === $signtoken and $this->CheckContactToken($contacttoken)){
            return true;
        }else{
            return false;
        }

    }

    public function CheckContactToken($contacttoken){

        if($this->clientcontact_id != null){
            if($this->clientcontact->token === $contacttoken){
                return true;
            }else{
                return false;
            }
        }else if($this->vendorcontact_id != null){
            if($this->vendorcontact->token === $contacttoken){
                return true;
            }else{
                return false;
            }
        }else if($this->user_id != null){
            if($this->user->token === $contacttoken){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }

    }

    public function Approved(){
        if($this->image === null){
            return false;
        }else{
            return true;
        }
    }

    public function LeftPosition($pagewidth){
        return $this->left * $pagewidth;
    }

    public function TopPosition($pageheight){
        $return = $this->top * $pageheight;
        return $return;
    }

    public function ElementWidth($pagewidth){
        return $this->width * $pagewidth;
    }

    public function ElementHeight($pageheight){
        return $this->height * $pageheight;
    }




    public function getPrefix(){
        return FileStoreHelper::getPrefix($this->image);
    }

    public function getSuffix(){
        return FileStoreHelper::getSuffix($this->image);
    }

    public function getMime(){
        return FileStoreHelper::getMime($this->image);
    }

    public function getMimeType(){
        return FileStoreHelper::getMimeType($this->image);
    }

    public function getMimeSubType(){
        return FileStoreHelper::getMimeSubType($this->image);
    }

    public function Base64Decode(){
        return FileStoreHelper::Base64Decode($this->image);
    }


}
