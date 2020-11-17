<?php

namespace App\Models;

use App\Helpers\OS\EventHelper;

class CalendarEvent extends CustomBaseModel {
    
    
    protected $table = 'calendar_events';
    
    protected $dates = ['start', 'end'];
    
    protected $appends = ['linkname','useremail'];
    
    public function getUserIdAttribute($value)
    {
        if($value === null){
            return "0";
        }else{
            return $value;
        }
    }
    
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
    
    function getlinknameAttribute() {
        if($this->client_id !== null){
            return $this->client->getName();
        }else if($this->vendor_id !== null){
            return $this->vendor->getName();
        }else{
            return "None";
        }
    } 
    
    function getuseremailAttribute() {
       
        if($this->user_id === null | $this->user_id === "0"){
            return "None"; 
        }else{
            return $this->user->email;
        }
    }
}