<?php

namespace App\Models\OS\Clients;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function ReturnData(){

        $return = array(
            'id' => $this->id,
            'name' => "{$this->firstname} {$this->lastname}",
            'scheduled' => $this->scheduled,
            'mobilenumber' => $this->mobilenumber,
            'homenumber' => $this->homenumber,
            'email' => $this->email,
            'comments' => $this->comments,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
        );

        if($this->address_id == null){
            $return['number'] = "";
            $return['address1'] = "";
            $return['address2'] = "";
            $return['city'] = "";
            $return['region'] = "";
            $return['state'] = "";
            $return['zip'] = "";
        }else{
            $return['number'] = $this->address->number;
            $return['address1'] = $this->address->address1;
            $return['address2'] = $this->address->address2;
            $return['city'] = $this->address->city;
            $return['region'] = $this->address->region;
            $return['state'] = $this->address->state;
            $return['zip'] = $this->address->zip;
        }

        return $return;

    }

    public function GetMobile()
    {
        $num = strip_tags($this->mobilenumber);
        return $num;
    }

    public function GetHome()
    {
        $num = strip_tags($this->homenumber);
        return $num;
    }

    public function getName(){

        return "{$this->firstname} {$this->lastname}";

    }

}
