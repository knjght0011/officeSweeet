<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Hash;

use \App\Providers\EventLog;

use App\Models\User;
use App\Models\Clock;
use App\Models\SchedulerEvents;
use App\Models\Task;

use App\Helpers\OS\Users\UserHelper;
use App\Helpers\FormatingHelper;

class AccountController extends Controller {
    
    public function showAccountPage()
    {
        $events = SchedulerEvents::where("user_id", Auth::user()->id)->get();
        #$tasks = Task::all();
        #$users = User::where('id', '>', 0)->where('os_support_permission', 0)->get();
        
        return View::make('Account.default')
                #->with('users',$users)
                #->with('tasks',$tasks)
                ->with('events',$events);

    }

    public function SetOption(){

        $data = array(
            'key' => Input::get('key'),
            'value' => Input::get('value'),
        );

        $options = Auth::user()->options;
        $options[$data['key']] = $data['value'];
        Auth::user()->options = $options;
        Auth::user()->save();

        return ['status' => 'OK'];
    }

    public function ToggleScheduleEmail(){

        $options = Auth::user()->options;
        $options['ScheduleEmail'] = Input::get('ScheduleEmail');
        Auth::user()->options = $options;
        Auth::user()->save();
        return ['status' => 'OK'];
    }
    

    
    public function ClockIn()
    {
        $data = new Clock;
        $data->status = 0;
        $data->in = Carbon::now();
        $data->user_id = Auth::user()->id;
        $data->save();
        
        return FormatingHelper::DateTimeTimeSheet($data->in);
    }
    
    public function ClockOut()
    {
        $data = Clock::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();
        #return var_dump($data);
        
        if($data->out == null){
            $data->out = Carbon::now();
            $data->save();
            
            return FormatingHelper::DateTimeTimeSheet($data->out);
        }else{
            $data = new Clock;
            $data->status = 0;
            $data->out = Carbon::now();
            $data->user_id = Auth::user()->id;
            $data->save();

            return "You didn't clock in today, clocked out at: " + FormatingHelper::DateTimeTimeSheet($data->out);
        }
    }
    
    public function ClockUpdate()
    {
        $data = array(
            'id' => Input::get('id'),
            'clockin' => Carbon::createFromFormat('Y-m-d H:i:s', Input::get('clockin'))
        );
        
        $data['clockin'] = Auth::user()->AdjustmentCarbonTimezone($data['clockin']);
        
        
        if(Input::get('clockout') == "Invalid date"){
            $data['clockout'] = null;
        }else{
            $data['clockout'] = Carbon::createFromFormat('Y-m-d H:i:s', Input::get('clockout'));
            $data['clockout'] = Auth::user()->AdjustmentCarbonTimezone($data['clockout']);
        }
        
        $validator = $this->ValidateClockUpdateInput($data);
        
        if ($validator->fails()){
            
            return $validator->errors()->toArray();
            
        } else {
            
            $clock = Clock::where('id',$data['id'])->first();
            $clock->in = $data['clockin'];
            $clock->out = $data['clockout'];
            $clock->save();

            $returnstring = $clock->id;
            $returnstring .= ",";            
            $returnstring .=  $clock->inforjava();
            $returnstring .= ","; 
            $returnstring .=  $clock->outforjava();
            $returnstring .= ","; 
            $returnstring .= $clock->indayofweek();
            $returnstring .= ","; 
            $returnstring .= $clock->indate() . " / " . $clock->intime();
            $returnstring .= ","; 
            $returnstring .= $clock->outdayofweek();
            $returnstring .= ","; 
            $returnstring .= $clock->outdate() . " / " . $clock->outtime();
            $returnstring .= ","; 
            $returnstring .= $clock->timedifference();
          
            return $returnstring;
        }
    }
    
    public function ValidateClockUpdateInput($data)
    {

        $rules = array(
            'id' => 'exists:clock,id', 
            'clockin' => '',
            'clockout' => '',
        );
                

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
    public function saveTimezone()
    {

        //$user = User::find(Auth::user()->id);
        Auth::user()->timezone = Input::get('timezone');
        Auth::user()->save();

        return "success";
    }


    public function SavePassword()
    {

        $data = array(
            'password' => Input::get('password'),
            'confirmpassword' => Input::get('confirmpassword'),
        );

        $validator = UserHelper::ValidatePassword($data);

        if ($validator->fails()) {
            return ["status" => "validation", "errors" => $validator->errors()->toArray()] ; // send back all errors
        } else {

            UserHelper::ChangePassword(Auth::user(), $data['password']);

            return ["status" => "OK"];
        }
    }
}
