<?php

namespace App\Models;

class ClientReport extends CustomBaseModel
{
    protected $table = 'clientreports';
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
    
    public function getUser() 
    {
        $user_data = $this->user;
        return $user_data->email;
    }
    
}
