<?php

namespace App\Models;

use App\Models\Address;

class VendorContact extends CustomBaseModel
{
    protected $table = 'vendorcontacts';
        
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function getaddressAttribute($value)
    {
        if($this->address_id === null){
            $address = $this->vendor->address;
            return $address;
        }else{
            $address_data = Address::where('id', $this->address_id)->first();
            return $address_data;
        }
    }
    
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }
    
    public function officenumberRAW()
    {
        return preg_replace("/[^0-9]/","",$this->officenumber);
    }
    
    public function mobilenumberRAW()
    {
        return preg_replace("/[^0-9]/","",$this->mobilenumber);
    }
    
    public function homenumberRAW()
    {
        return preg_replace("/[^0-9]/","",$this->homenumber);
    }
    
    public function Getprimaryphonenumber()
    {
        switch ($this->primaryphonenumber) {
            case "1":
                return $this->officenumber;
            case "2":
                return $this->mobilenumber;
            case "3":
                return $this->homenumber;
            default:
                return $this->officenumber;
        }
    }
    
    public function GetprimaryphonenumberRAW()
    {
        switch ($this->primaryphonenumber) {
            case "1":
                return $this->officenumberRAW();
            case "2":
                return $this->mobilenumberRAW();
            case "3":
                return $this->homenumberRAW();
            default:
                return $this->officenumberRAW();
        }
    }
}
