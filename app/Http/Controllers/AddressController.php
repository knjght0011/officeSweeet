<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Input;



use App\Helpers\OS\Address\AddressHelper;
use App\Models\Address;

class AddressController extends Controller {
    

    
    #Route::post('Address/Lookup', array('uses' => 'AddressController@LookupAddress'));
    function LookupAddress()
    {
        if (Input::has('zip'))
        {
            
            $zip = urlencode(Input::get('zip'));
            
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $zip . "&sensor=true&key=AIzaSyA5KSV5I5BXr1WlNDVmh0uv6awtHkTbSsQ";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $address_info = curl_exec($ch);
            
            $json = json_decode($address_info);

            if ($json->status === 'OK')
            {
                //break up the components
                $arrComponents = $json->results[0]->address_components;

                foreach($arrComponents as $index=>$component) 
                {
                    $type = $component->types[0];
                    
                    if (!isset($postal_code) && $type=="postal_code") 
                    {
                        $postal_code = array(
                            "long_name"  => trim($component->long_name),
                            "short_name" => trim($component->short_name),
                        );
                    } 
                    
                    if (!isset($country) && $type=="country")
                    {
                        $country = array(
                            "long_name"  => $component->long_name,
                            "short_name" => $component->short_name,
                        );
                    }
                    
                    if (!isset($administrative_area_level_2) && $type=="administrative_area_level_2") 
                    {
                        $administrative_area_level_2 = array(
                            "long_name"  => $component->long_name,
                            "short_name" => $component->short_name,
                        );
                    }
                    
                    if (!isset($administrative_area_level_1) && $type=="administrative_area_level_1") 
                    {
                        $administrative_area_level_1 = array(
                            "long_name"  => $component->long_name,
                            "short_name" => $component->short_name,
                        );
                    }
                    
                    if (!isset($postal_town) && $type=="postal_town") 
                    {
                        $postal_town = array(
                            "long_name"  => $component->long_name,
                            "short_name" => $component->short_name,
                        );
                    }                    

                    if (!isset($locality) && ($type == "sublocality_level_1" || $type == "locality") ) 
                    {
                        $locality = array(
                            "long_name"  => $component->long_name,
                            "short_name" => $component->short_name,
                        );
                    }
                    
                    if (!isset($route) && $type=="route") 
                    {
                        $route = array(
                            "long_name"  => trim($component->long_name),
                            "short_name" => trim($component->short_name),
                        );
                    } 

                }

                #set any information we didnt get to blank
                if (!isset($postal_code))
                {
                    $postal_code = array(
                        "long_name"  => "",
                        "short_name" => "",
                    );
                }

                if (!isset($country))
                {
                    $country = array(
                        "long_name"  => "",
                        "short_name" => "",
                    );
                }

                if (!isset($administrative_area_level_2))
                {
                    $administrative_area_level_2 = array(
                        "long_name"  => "",
                        "short_name" => "",
                    );
                }

                if (!isset($administrative_area_level_1))
                {
                    $administrative_area_level_1 = array(
                        "long_name"  => "",
                        "short_name" => "",
                    );
                }

                if (!isset($postal_town))
                {
                    $postal_town = array(
                        "long_name"  => "",
                        "short_name" => "",
                    );
                }

                if (!isset($locality))
                {
                    $locality = array(
                        "long_name"  => "",
                        "short_name" => "",
                    );
                }

                if (!isset($route))
                {
                    $route = array(
                        "long_name"  => "",
                        "short_name" => "",
                    );
                }



                switch ($country["short_name"])
                {
                    case "US":
                        $address = array(
                            "country" => $country["long_name"],
                            "postal_code"  => $postal_code["long_name"],
                            "state_province" => $administrative_area_level_1["long_name"],
                            "region" => $administrative_area_level_2["long_name"],
                            "city" => $locality["long_name"],
                            "address2" => "",
                            "address1" => $route["long_name"],
                        );
                        break;
                    case "GB":
                        $address = array(
                            "country" => $country["long_name"],
                            "postal_code"  => $postal_code["long_name"],
                            "state_province" => $administrative_area_level_2["long_name"],
                            "region" => $postal_town["long_name"],
                            "city" => $locality["long_name"],
                            "address2" => "",
                            "address1" => $route["long_name"],
                        );
                        break;
                    default:
                        $address = array(
                            "country" => $country["long_name"],
                            "postal_code"  => $postal_code["long_name"],
                            "state_province" => $administrative_area_level_1["long_name"],
                            "region" => $administrative_area_level_2["long_name"],
                            "city" => $locality["long_name"],
                            "address2" => "",
                            "address1" => $route["long_name"],
                        );
                }

                if ($address["city"] == "" & $address["region"] !== ""){
                    $address["city"] = $address["region"];
                    $address["region"] = "";
                }

                return ['status' => 'OK', 'data' => $address];
            }else{
                return ['status' => 'error', 'reason' => $json->status];
            }
        } else {
            
            return ['status' => 'error', 'reason' => 'nozip'];

        }
    }
    
    #Route::post('Address/Add', array('uses' => 'AddressController@doAdd'));
    public function doAdd()
    {
        $addressdata = array(
            'number' => Input::get('number'),
            'address1' => Input::get('address1'),
            'address2' => Input::get('address2'),
            'city' => Input::get('city'),
            'region' => Input::get('region'),
            'state' => Input::get('state'),
            'zip' => Input::get('zip'),
            'type' => "",
        );
        
        $validator = AddressHelper::ValidateAddressInput($addressdata);

        if ($validator->fails()){
            
            return $validator->errors()->toArray();
            
        } else {
            return AddressHelper::SaveAddress($addressdata)->id;
        }
    }

}