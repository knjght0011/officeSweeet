<?php

namespace App\Models\Management;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $connection = 'management';
    
    protected $table = "email";
    
    protected $casts = [
        'recipient' => 'array',
        #'message_headers' => 'array',
        'to' => 'array',
        'x_mailgun_variables' => 'array',
        'readby' => 'array',
    ];
    
    public function getToAsString()
    {
        return implode(", ",$this->to);
    }
    
    public function getrecipientAsString()
    {
        return implode(", ",$this->recipient);
    }
    
    public function isReadByMe()
    {
       return $this->isReadBy(Auth::user()->email);
    }
    
    public function isReadBy($emailaddress)
    {
        if(isset($this->readby[$emailaddress])){
            if($this->readby[$emailaddress] === 1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    public function MarkReadByMe()
    {
       return $this->MarkReadBy(Auth::user()->email);
    }
    
    public function MarkReadBy($emailaddress)
    {
        $array = $this->readby;
        $array[$emailaddress] = 1;
        $this->readby = $array;
        $this->save();
    }
    
}