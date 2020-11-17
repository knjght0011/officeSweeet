<?php

namespace App\Models;

class Deposit extends CustomBaseModel
{
    protected $table = 'deposit';
    
    protected $dates = ['date'];

    protected $casts = [
        'catagorys' => 'array',
    ];

    public function getcatagorysAttribute($value)
    {
        if($value === null){
            $array = array(
                "Unknown" => $this->amount,
            );
            return $array;
        }else{
            return json_decode($value);
        }
    }
    
    public function depositlinks()
    {
        return $this->hasMany('App\Models\DepositLink', 'deposit_id', 'id');
    }

    public function filestore()
    {
        return $this->belongsTo('App\Models\OS\FileStore', 'filestore_id', 'id');
    }
    
    public function getAmount() 
    {        
        return number_format($this->amount , 2, '.', '');
    }    

    public function formatedAmount() 
    {
        return "$" . number_format($this->amount , 2, '.', '');
    }
    
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    
    public function getUser() 
    {
        if($this->user_id === null){
            return "Automated";
        }else{
            return $this->user->email;
        }
    }
    
    public function getFrom()
    {
        switch (count($this->depositlinks)) {
            case 0:
                return "Misc deposit";
                break;
            case 1:
                return $this->depositlinks[0]->getClientName();
                break;
            default:
                return $this->depositlinks[0]->getClientName();
        }
    }
    
    public function getInvoiceNumbers()
    {
        switch (count($this->depositlinks)) {
            case 0:
                return "";
                break;
            case 1:
                return "Invoice number(s): " . $this->depositlinks[0]->getQuoteNumber();
                break;
            default:
                $string = "Invoice number(s): ";
                foreach($this->depositlinks as $link){
                    $string = $string . $link->getQuoteNumber() . ", ";
                }
                return $string;
        }
    }
    
    public function getClientID()
    {
        if(count($this->depositlinks) > 0){
            return $this->depositlinks[0]->getClientID();
        }else{
            return "";
        }
    }

    public function getClientIDnull()
    {
        if(count($this->depositlinks) > 0){
            return $this->depositlinks[0]->getClientID();
        }else{
            return null;
        }
    }
    
    public function getClientname()
    {
        if(count($this->depositlinks) > 0){
            return $this->depositlinks[0]->getClientname();
        }else{
            return "Misc";
        }
    }
    
    public function FormatDate() 
    {       
        return $this->date->format('jS M Y');
    }
    
    public function dateforinput()
    {
        return $this->date->format('Y-m-d');
    }
    
    public function DateString() 
    {       
        return $this->date->toDateString();
    }
    
    public function CantEdit() 
    {
        $lastmonthend = MonthEnd::get()->last();
        if(count($lastmonthend) === 1){
            return $lastmonthend->IsBeforeThis($this->date);
        }else{
            return false;
        }

    }

    public function HasAttachment()
    {
        if($this->filestore_id === null){
            return false;
        }else{
            return true;
        }
    }

    public function getFile(){
        if($this->filestore_id === null){
            return "";
        }else{
            return $this->filestore->file;
        }
    }

}
