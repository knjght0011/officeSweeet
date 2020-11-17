<?php

namespace App\Models;

class PaymentsAdjustments extends CustomBaseModel
{
    protected $table = 'paymentsadjustments';
    
    
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    
    public function invoice()
    {
        return $this->belongsTo('App\Models\Quote', 'quote_id', 'id');
    }
    
    public function getAmount() 
    {        
        return number_format($this->amount , 2, '.', '');
    }    
    
    public function getUser() 
    {
        $user_data = $this->user;
        return $user_data->email;
    }
    
    public function getClientname() 
    {
        $client_data = $this->client;
        
        if(is_null($client_data->name)){
            return $client_data->primarycontact->firstname . $client_data->primarycontact->lastname;
        }else{
            return $client_data->name;
        }  
    }
    
    public function date() 
    {
        return $this->created_at->toDateString();
    }
    
    public function formatedAmount() 
    {
        return "$" . number_format($this->amount , 2, '.', '');
    }

    public function formatedAmountCredit() 
    {
        if($this->type === "Payment"){
            return "$" . number_format($this->amount , 2, '.', '');
        }else{
            return "";
        }
    }
    
    public function formatedAmountDebit() 
    {
        if($this->type === "Adjustment"){
            return "$" . number_format($this->amount , 2, '.', '');
        }else{
            return "";
        }
        
    }
}

