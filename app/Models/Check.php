<?php

namespace App\Models;

class Check extends CustomBaseModel
{
    
    protected $table = 'checks';
    
    protected $casts = [
        'catagorys' => 'array',
        'receptIDs' => 'array',
    ];
    
    protected $dates = ['date'];
    
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
    
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public function type()
    {
        if($this->client_id !== null){
            return "client";
        }else if($this->vendor_id !== null){
            return "vendor";
        }else if($this->user_id !== null){
            return "employee";
        }
    }
    
    public function data()
    {
        switch ($this->type()) {
            case "client":
                return $this->client;
            case "vendor":
                return $this->vendor;
            case "employee":
                return $this->user;
            default:
                return "invalid type";
        }
    }

    public function LinkedAccountName()
    {
        switch ($this->type()) {
            case "client":
                return $this->client->getName();
            case "vendor":
                return $this->vendor->getName();
            case "employee":
                return "Employee"; #$this->user->getName();
            default:
                return "Miscellaneous Expense";
        }
    }
    
    #public function GetNewCheckNumber()
    #{
    #    return 1000;
    #}
    
    public function CheckDate()
    {
        $createDate = new \DateTime($this->date);
        return $createDate->format('m/d/Y');
    }
    
    public function GetAmount()
    {
        return number_format($this->amount , 2, '.', '');
    }
    
    public function AmountInWords()
    {

        $text = $this->CurrencyToText(number_format($this->amount , 2, '.', ''));
        return $text;
    }
    
    private function CurrencyToText($Amount)
    {

        $formatter = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        if (strpos($Amount, ".") === false)
        {
            $ReturnString = $formatter->format($Amount)." and 0/100 ";
        }else
        {
            $NumberArray = explode(".", $Amount);
            $value = str_replace(',', '',  $NumberArray[0]);

            $ReturnString = $formatter->format($value) . " and ";
            $ReturnString .=  $NumberArray[1]."/100 ";
        }
        
        $StarAdd = 61 - strlen($ReturnString);
        if ($StarAdd > 0)
        {
            for ($x = 0; $x <= $StarAdd; $x++) {
                $ReturnString .=  "*";
            } 
        }
        return ucfirst($ReturnString);
    }
    
    public function formatedAmount() 
    {
        return "$" . number_format($this->amount , 2, '.', '');
    }
    
    public function formatDate() 
    {
        return $this->date->format('jS M Y');
    }
    
    public function dateforinput()
    {
        return $this->date->format('Y-m-d');
    }
    
    public function getchecknumberAttribute($value){
        if($value === "0"){
            return "Pending";
        }else{
            return $value;
        }
    }
}
