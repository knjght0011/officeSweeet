<?php

namespace App\Models;

class Address extends CustomBaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'address';
        
    public function client(){
        return $this->belongsTo('App\Models\Client', 'address_id');
    }

    public function clientcontact(){
        return $this->belongsTo('App\Models\ClientContact', 'address_id');
    }
        
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor', 'address_id');
    }

    public function AddressString(){

        $string = "";

        if($this->number != ""){
            $string = $string . $this->number . " ";
        }
        if($this->address1 != ""){
            $string = $string . $this->address1 . " ";
        }
        if($this->address2 != ""){
            $string = $string . $this->address2 . " ";
        }
        if($this->city != ""){
            $string = $string . $this->city . " ";
        }
        if($this->region != ""){
            $string = $string . $this->region . " ";
        }
        if($this->state != ""){
            $string = $string . $this->state . " ";
        }
        if($this->zip != ""){
            $string = $string . $this->zip;
        }

        return $string;

    }
}