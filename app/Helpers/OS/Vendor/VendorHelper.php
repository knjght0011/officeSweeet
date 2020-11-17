<?php
namespace App\Helpers\OS\Vendor;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use \App\Providers\EventLog;

use App\Models\Vendor;

class VendorHelper
{
    #Vendor Validation
    public static function ValidateVendorInput($data, $namerequired = true)
    {
        $rules = array(
            'notes' => '',
        );

        if($data['name'] !== null){
            if($namerequired){
                $rules['name'] = 'required|unique:vendors,name,' . $data['id'];
            }else{
                $rules['name'] = 'unique:vendors,name,' . $data['id'];
            }
        }

        if(isset($data['tax_id_number'])){
            if(isset($data['1099'])){
                if($data['1099'] === "1"){
                    $rules['tax_id_number'] = 'required|string';
                }
            }
        }

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
    public static function SaveVendorToDB($data)
    {
        $vendor = new Vendor;

        if($data['name'] === ""){
            $vendor->name = null;
        }else{
            $vendor->name = $data['name'];
        }

        $vendor->category = $data['category'];
        $vendor->address_id = $data['address_id'];
        if(isset($data['1099'])){
            $vendor->track_1099 = $data['1099'];
            $vendor->tax_id_number = $data['tax_id_number'];
        }
        $vendor->phonenumber = $data['phonenumber'];
        $vendor->custom = $data['custom'];
        $vendor->email = $data['email'];

        $vendor->save();

        EventLog::add('New vendor created ID:'.$vendor->id.' Name:'.$vendor->name);

        return $vendor;
    }
    

    public static function AllVendorCategorys(){

        $clients = Vendor::where('category', '!=', '')->get();

        $array = array();

        foreach ($clients as $client){
            if(!in_array($client->category, $array)){
                $array[] = $client->category;
            }
        }

        return $array;
    }

}