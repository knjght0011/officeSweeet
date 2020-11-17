<?php
namespace App\Helpers\OS\Client;

use App\Helpers\OS\SettingHelper;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use \App\Providers\EventLog;

use App\Models\Client;

class ClientHelper
{
    public static function ValidateDetailsInput($data)
    {

            $rules = array(
                'id' => 'exists:clients,id',
                'date_of_introduction' => 'date',
                'current_solution' => 'string',
                'budget' => 'string',
                'decision_maker' => 'string',
                'referral_source' => 'string',
                'assigned_to' => 'exists:users,id|nullable',
                'problem_pain' => 'string',
                'resistance_to_change' => 'string',
                'priorities' => 'string',
                'comments' => 'string',
            );


        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);
        
        return $validator;
    }

    #Client Validation
    public static function ValidateClientInput($data)
    {
        $rules = array(
            'notes' => '',
        );

        if($data['name'] !== null){
            $rules['name'] = Rule::unique('clients')->ignore($data['id']);
        }

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }

    
    public static function UpdateDetails($data){

        $client = Client::where('id', $data['id'])->withTrashed()->first();
        $client->date_of_introduction = $data['date_of_introduction'];
        $client->current_solution = $data['current_solution'];
        $client->budget = $data['budget'];
        $client->decision_maker = $data['decision_maker'];
        $client->referral_source = $data['referral_source'];
        $client->assigned_to = $data['assigned_to'];
        $client->problem_pain = $data['problem_pain'];
        $client->resistance_to_change = $data['resistance_to_change'];
        $client->priorities = $data['priorities'];
        $client->comments = $data['comments'];
        $client->phonenumber = $data['mainnumber'];
        $client->email = $data['email'];

        SettingHelper::SetSetting('client-custom-label', $data['customfieldlabel']);
        $client->custom_field_text = $data['customfieldtext'];

        SettingHelper::SetSetting('client-custom-label2', $data['customfieldlabel2']);
        $client->custom_field_label = $data['customfieldtext2'];

        if($data['followupdate'] === ""){
            $client->follow_up_date = null;
        }else{
            $client->follow_up_date = $data['followupdate'];
        }

        $client->save();
        
        return $client->id;
    }

    public static function SaveClientToDB($data)
    {
        $account = app()->make('account');

        $client = new Client;

        if($data['name'] === ""){
            $client->name = null;
        }else{
            $client->name = $data['name'];
        }

        if($data['category'] === null){
            $client->category = "";
        }else{
            $client->category = $data['category'];
        }
        $client->email = $data['email'];
        $client->phonenumber = $data['phonenumber'];
        $client->address_id = $data['address_id'];
        $client->existingclient = $data['existingclient'];

        if($account->plan_name === "SOLO"){
            $client->existingclient = 1;
        }

        $client->save();

        EventLog::add('New client created ID:'.$client->id.' Name:'.$client->name);

        return $client;
    }

    public static function AllCategorys(){

        $clients = Client::where('category', '!=', '')->get();

        $array = array();

        foreach ($clients as $client){
            if(!in_array($client->category, $array)){
                $array[] = $client->category;
            }
        }

        return $array;
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

    public static function GetClientCount()
    {
        return Client::count();
    }

}