<?php

namespace App\Models;

class ClientNote extends CustomBaseModel
{
    protected $table = 'clientnotes';
    
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
    
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
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

    public function getNotePlain()
    {
        if(strlen($this->note) > 40)
        {
            $notestring = strval($this->note);
            $note = str_limit($notestring, 40);
            #$note =+ '...';
            $return = strip_tags($note);
        }else{
            $return = strip_tags($this->note);

        }

        return $return;

    }
}