<?php
namespace App\Models;

class invoice extends CustomBaseModel
{
    protected $table = 'invoice';
    
    public function GetTotalAmount()
    {
        return number_format($this->totalamount ,2, '.', '') ;
    }
    
    public function GetBalance()
    {
        $TotalBalance = $this->totalamount;
      
        $Payments = "";
        foreach ($Payments as $Payment)
        {
            $TotalBalance -= $Payment->amount;
        }
        
        return $TotalBalance;
    }
    
    public function GetInvoiceDate()
    {
        return date("Y-m-d H:i:s", strtotime("$this->created_at"));
    }
    
    public function Client()
    {
        return $this->belongsTo('App\Models\Client');
    }
}
