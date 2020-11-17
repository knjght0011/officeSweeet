<?php
namespace App\Helpers\OS\Address;

use Illuminate\Support\Facades\Validator;

use \App\Providers\EventLog;

use App\Models\Address;

class AddressHelper
{

    public static function ValidateAddressInput($data){
        if($data['address1'] === "NOADDRESS"){
            $rules = array();
        }else{
            $rules = array(
                'number'    => 'required',
                'address1'    => 'required',
                'address2'    => '',
                'city'    => 'required',
                'region'    => '',
                'state'    => 'required',
                'zip'    => 'required',
            );
        }

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }

    public static function SaveAddress($addressdata){

        $address_data = Address::where('number', $addressdata['number'])
                                ->where('address1', $addressdata['address1'])
                                ->where('address2', $addressdata['address2'])
                                ->where('city', $addressdata['city'])
                                ->where('region', $addressdata['region'])
                                ->where('state', $addressdata['state'])
                                ->where('zip', $addressdata['zip'])
                                ->where('type', $addressdata['type'])
                                ->first();

        if(count($address_data) === 1){
            return $address_data;
        }else{
            $address_data = new Address;
            $address_data->number = $addressdata['number'];
            $address_data->address1 = $addressdata['address1'];
            $address_data->address2 = $addressdata['address2'];
            $address_data->city = $addressdata['city'];
            $address_data->region = $addressdata['region'];
            $address_data->state = $addressdata['state'];
            $address_data->zip = $addressdata['zip'];
            $address_data->type = $addressdata['type'];
            $address_data->save();

            EventLog::add('New address ID:'.$address_data->id.' Address:'.$address_data->number.' '.$address_data->address1.' '.$address_data->state);

            return $address_data;
        }


    }


}