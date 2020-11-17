<?php

namespace App\Models;

use App\Models\Address;

class ClientContact extends CustomBaseModel
{

    protected $table = 'clientcontacts';

    public $with = 'address';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $length = 16;

            $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            $str = '';
            $max = strlen($chars) - 1;

            for ($i=0; $i < $length; $i++){
                $str .= $chars[random_int(0, $max)];
            }

            $instance->token = $str;
        });

        static::retrieved(function ($instance) {
            if($instance->token === null){
                $length = 16;

                $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                $str = '';
                $max = strlen($chars) - 1;

                for ($i=0; $i < $length; $i++){
                    $str .= $chars[random_int(0, $max)];
                }

                $instance->token = $str;

                $instance->save();
            }
        });
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function getaddressAttribute($value)
    {
        if($this->address_id === null){
            $address = $this->client->address;
            return $address;
        }else{
            return $this->getRelation('address');
        }
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client' , 'client_id', 'id' )->withTrashed();
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
