<?php

namespace App\Models;

class EmployeeNote extends CustomBaseModel
{
    protected $table = 'employeenotes';
    
    public function creator()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public function employee()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public function getUser() 
    {
        $user_data = $this->creator;
        return $user_data->email;
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