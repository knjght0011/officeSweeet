<?php
namespace App\Models;

class VendorNote extends CustomBaseModel
{
    protected $table = 'vendornotes';
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }
    
    public function getUser() 
    {
        if($this->user_id === null){
            return "System";
        }else{
            return $this->user->email;
        }
    }
    
    public function getNote() 
    {
        if(strlen($this->note) > 40)
        {
            $notestring = strval($this->note);
            $note = str_limit($notestring, 40);
            #$note =+ '...';
            $return = $note; 
        }else{
            $return = $this->note;
                    
        }
        
        return $return;
    }
}