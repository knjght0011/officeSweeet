<?php

namespace App\Models;

class DepositLink extends CustomBaseModel
{
    
    protected $table = 'depositlink';
    
    public function deposit()
    {
        return $this->belongsTo('App\Models\Deposit', 'deposit_id', 'id');
    }
    
    public function invoice()
    {
        return $this->belongsTo('App\Models\Quote', 'quote_id', 'id')->withTrashed();
    }
    
    public function getAmount() 
    {        
        return number_format($this->amount , 2, '.', '');
    }    
    
    public function formatedAmount() 
    {
        return "$" . number_format($this->amount , 2, '.', '');
    }
    
    public function getClientName() 
    {
        if ($this->relationLoaded('invoice')){
            return $this->invoice->getClientName();
        }else{
            $this->load('invoice');
            return $this->invoice->getClientName();
        }
    }

    public function getClientID() 
    {
        if ($this->relationLoaded('invoice')){
            return $this->invoice->client_id;
        }else{
            $this->load('invoice');
            return $this->invoice->client_id;
        }
    }
    
    
    public function getQuoteNumber() 
    {
        if ($this->relationLoaded('invoice')){
            return $this->invoice->quotenumber;
        }else{
            $this->load('invoice');
            return $this->invoice->quotenumber;
        }
    }
}