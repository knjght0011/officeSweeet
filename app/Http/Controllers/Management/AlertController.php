<?php

namespace App\Http\Controllers\Management;

#use Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
#use Illuminate\Support\Facades\Validator;
#use Illuminate\Support\Facades\Input;
#use Illuminate\Support\Facades\Auth;
#use Cache;
#use Config;

use App\Models\Management\Alert;

use App\Http\Controllers\Controller;
 
class AlertController extends Controller
{
    public function viewAlerts()
    {
       $alerts = Alert::all();
       
        return View::make('Management.Alerts.index')
            ->with('alerts', $alerts);
    }
    
    public function viewAlert($subdomain, $id)
    {
       $alert = Alert::where('id', $id)->first();
       
       if(count($alert) === 1){
            return View::make('Management.Alerts.view')
                ->with('alert', $alert);
       }else{
           return "unknown alert";
       }
    }
    
    public function mark($subdomain, $id)
    {
       $alert = Alert::where('id', $id)->where('action_stage', 1)->first();
       
       if(count($alert) === 1){
           
            $alert->action_stage = 2;
            $alert->save();
           
           
            return Redirect::to('/Alerts/' . $alert->id);
            
       }else{
           return "unknown alert";
       }
    }    
    
}
