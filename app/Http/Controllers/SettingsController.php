<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use \App\Providers\EventLog;

use App\Models\Setting;
use App\Models\Branch;
use App\Models\User;
use App\Models\TemplateGroup;

class SettingsController extends Controller {
    
    public function ValidateAddressInput($data){
        $rules = array(
            'number'    => 'required',
            'address1'    => 'required', 
            'address2'    => '', 
            'city'    => 'required',
            'region'    => '',
            'state'    => 'required',
            'zip'    => 'required',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);
        
        Return $validator; // send back all errors
  
    }
        
    public function showGeneralSettings()
    {
        $templategroup = TemplateGroup::all();
        $settings = Setting::all();
        $branches = Branch::all();
        return View::make('Admin.Settings.all')
                ->with('templategroup',$templategroup)
                ->with('settings',$settings)
                ->with('branches',$branches);

    }

    public function saveGeneralSettings()
    {
        $settings = Input::all();
        unset($settings["_token"]);

        foreach ($settings as $key => $value){
            #return $key;
            $data = Setting::where('name', $key)
                ->first();
            $data->value = $value;
            $data->save();
        }

        return "ok";

    }
    
    public function showBranchNew()
    {
        return View::make('Admin.Settings.Branches.add');
    }
    
    public function showBranchEdit()
    {
        
    }

    public function saveBranch()
    {
        $addressdata = array(
            'number' => Input::get('number'),
            'address1' => Input::get('address1'),
            'address2' => Input::get('address2'),
            'city' => Input::get('city'),
            'region' => Input::get('region'),
            'state' => Input::get('state'),
            'zip' => Input::get('zip'),
        );
        
        $validator = $this->ValidateAddressInput($addressdata);

        if ($validator->fails()){
            
            return $validator->errors()->toArray();
            
        } else {
            
            $address_data = new Branch;
            $address_data->number = $addressdata['number'];
            $address_data->address1 = $addressdata['address1'];
            $address_data->address2 = $addressdata['address2'];
            $address_data->city = $addressdata['city'];
            $address_data->region = $addressdata['region'];
            $address_data->state = $addressdata['state'];
            $address_data->zip = $addressdata['zip'];
            $address_data->save();

            EventLog::add('New branch ID:'.$address_data->id.' Address:'.$address_data->number.' '.$address_data->address1.' '.$address_data->state);
            
            return $address_data->id;

        }

    }
    
    public function saveTemplateSubGroup()
    {
        $group = Input::get('group');
        $subgroup = Input::get('subgroup');
        
        $data = new TemplateGroup;
        $data->group = $group;
        $data->subgroup = $subgroup;
        $data->save();
        
        return $data->id;

    }
    public function deleteTemplateSubGroup()
    {
        $group = Input::get('group');
        $subgroup = Input::get('subgroup');
        
        $data = TemplateGroup::where('subgroup', $subgroup)
                            ->get();
        
        $deleted = false;
        foreach($data as $d){
            if($d->group == $group){
                $d->delete();
                $deleted = true;
            }
        }
        
        if ($deleted == true){
            return $subgroup;
        }else{
            return "fail";
        }
    }
}