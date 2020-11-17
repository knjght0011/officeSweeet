<?php

namespace App\Http\Controllers\OS\Users;

use App\Helpers\OS\Users\UserHelper;
use App\Models\Check;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


//Models
use App\Models\Address;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\Clock;

//mail
use App\Mail\LiveDemoValidation;

//helper
use App\Helpers\OS\Financial\PayrollHelper;

class LoginController extends Controller {

    public function ShowVideo()
    {

        Session::push('helphub', 'video');

        return Redirect::to('/Home');

    }

    
    public function showLogin($subdomain)
    {
        if($subdomain === "livedemo"){
            return Redirect::to('DemoSignup');
        }else{
            $seting = Setting::where("name", "companyname")->first();
            if($seting === null){
                $companyname = $subdomain;
            }else{
                $companyname = $seting->value;
            }

            $uri = Session::get('uri');
            if($uri === null){
                $uri = "";
            }

            return View::make('Login.login')
                ->with('uri',$uri)
                ->with('companyname', $companyname);
        }
    }

    public function doLogin($account)
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|email', // make sure the email is an actual email
            'password' => 'required|string|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        $userdata = array(
            'email'     => trim (Input::get('email')),
            'password'  => Input::get('password'),
            'canlogin'  => 1
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($userdata, $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {
            // create our user data for the authentication

            
            $supportdata = array(
                'email'     => trim (Input::get('email')),
                'password'  => Input::get('password'),
                'os_support_permission'  => 1
            );
            
            #var_dump($userdata);

            // attempt to do the login
            if (Auth::attempt($userdata) or Auth::attempt($supportdata)) {

                 //validation successful!
                 //redirect them to the secure section or whatever
                
                $setting = Setting::all();
                $settings = array();
                
                foreach($setting as $s){

                    $settings[$s->name] = $s->value;
                }
                Session::put('settings', $settings);
                
                $timezoneoffset = Input::get('timezone');
                if($timezoneoffset != null){
                    Auth::user()->timezoneoffset = $timezoneoffset;
                }

                Auth::user()->last_login = Carbon::now();
                Auth::user()->save();

                $uri = Input::get('uri');

                if($uri === null){
                    $uri = "";
                }

                if(Input::get('app') === "yes"){
                    Session::put('app' , 'yes');
                }

                Auth::user()->SetOption('uri-debug', $uri);

                #$unreadmessagecount = Auth::user()->newThreadsCount();
                #if($unreadmessagecount > 0){
                #    session::put('unread' , $unreadmessagecount);
                #}
                

                if($account === "demo"){
                    $clocks = Clock::where("user_id" , Auth::user()->id)->where("in" , '=', Carbon::today()->toDateString())->get();
                    if(count($clocks === 0)){
                        $data = new Clock;
                        $data->status = 0;
                        $data->in = Carbon::now();
                        $data->out = Carbon::now()->addHours(9);
                        $data->user_id = Auth::user()->id;
                        $data->save();
                    }
                }

                $usertokens = UserHelper::GetAllUsersWhere("token", null);
                foreach($usertokens as $addtoken){
                    $length = 16;

                    $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                    $str = '';
                    $max = strlen($chars) - 1;

                    for ($i=0; $i < $length; $i++){
                        $str .= $chars[random_int(0, $max)];
                    }

                    $addtoken->token = $str;
                    $addtoken->save();
                }

                if(app()->make('account')->installstage === 6){
                    return Redirect::to('Setup');
                }else{
                    #return Session::all();
                    if($uri === ""){
                        if(isset(Auth::user()->options['default-view'])){
                            switch (Auth::user()->options['default-view']) {
                                case 'Home':
                                return Redirect::to(url("/Home")); // redirect the user to the home screen
                                case 'Scheduler':
                                    if(Auth::user()->hasPermission('scheduler_permission')){
                                        return Redirect::to(url("/Scheduling/View")); // redirect the user to the Scheduling screen
                                    }else{
                                        return Redirect::to(url("/Home")); // redirect the user to the home screen
                                    }
                                case 'Journal':
                                    if(Auth::user()->hasPermission('journal_permission')){
                                        return Redirect::to(url("/Journal/View")); // redirect the user to the Journal screen
                                    }else{
                                        return Redirect::to(url("/Home")); // redirect the user to the home screen
                                    }
                                case 'Reporting':
                                    if(Auth::user()->hasPermission('reporting_permission')){
                                        return Redirect::to(url("/Reporting")); // redirect the user to the Reporting screen
                                    }else{
                                        return Redirect::to(url("/Home")); // redirect the user to the home screen
                                    }
                                case 'Payroll':
                                    if(Auth::user()->hasPermission('payroll_permission')){
                                        return Redirect::to(url("/Payroll")); // redirect the user to the Payroll screen
                                    }else{
                                        return Redirect::to(url("/Home")); // redirect the user to the home screen
                                    }
                                case 'Templates':
                                    if(Auth::user()->hasPermission('templates_permission')){
                                        return Redirect::to(url("/Templates/List")); // redirect the user to the Templates screen
                                    }else{
                                        return Redirect::to(url("/Home")); // redirect the user to the home screen
                                    }
                                default:
                                return Redirect::to(url("/Home")); // redirect the user to the home screen
                            }
                        }else{
                            return Redirect::to(url("/Home")); // redirect the user to the home screen
                        }
                    }else{
                        return Redirect::to(url($uri)); // redirect the user to the client search screen
                    }

                }
            } else {        
                
                // validation not successful, send back to form 
                return Redirect::to('login')
                    ->withErrors('Email Address and/or Password Incorrect') // send back all errors to the login form
                    ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form   
            }
        }
    }

    public function doLogout()
    {
        Session::flush();
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }

    public function DemoLogin($subdomain, $password){

        $user = User::where('password', '=', $password)->first();
        session::put('demopopup' , 'true');



        if(count($user) === 1){
            Auth::login($user, true);

            return Redirect::to('/Home');
        }else{
            //unable to find account, redirect them to the demo signup.
            return Redirect('/login');
        }

    }

    public function DemoSignup(){
        return View::make('Login.demosignup');
    }

    public function DemoSignupDo(){
        $data = array(
            'firstname' => Input::get('firstname'),
            'lastname' => Input::get('lastname'),
            'email' => Input::get('email'),
        );

        $user = UserHelper::GetOneUserByEmail($data['email']);

        if(count($user) === 1){

            Mail::to($user->email)->send(new LiveDemoValidation($user));

            return Redirect::to('/DemoSignup/')
                ->with('done', 'done');

        }else{

            $rules = array(
                'firstname' => 'string',
                'lastname' => 'string',
                'email' => 'email',
            );

            // run the validation rules on the inputs from the form
            $validator = Validator::make($data, $rules);

            if ($validator->fails()){

                return Redirect::to('DemoSignup')
                    ->withErrors($validator->errors()->toArray()) // send back all errors to the login form
                    ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form

            } else {

                $user = new User;
                $user->employeeid = "";
                $user->firstname = $data['firstname'];
                $user->middlename = "";
                $user->lastname = $data['lastname'];
                $user->ssn = "";
                $user->driverslicense = "";
                $user->department = "";
                $user->type = "2";
                $user->comments = "";
                $user->email = $data['email'];
                $user->password = $this->RandomPassword();
                $user->passwordlastchanged = "";
                $user->locked = "0";
                $user->failedattempts = "0";
                $user->disabled = "0";
                $user->canlogin = "0";
                $user->os_support_permission = "0";
                $user->acp_subscription_permission = "0";
                $user->acp_manage_custom_tables_permission = "0";
                $user->acp_company_info_permission = "0";
                $user->acp_import_export_permission = "0";
                $user->acp_permission = "0";
                $user->client_permission = "1";
                $user->vendor_permission = "1";
                $user->employee_permission = "0";
                $user->login_management_permission = "0";
                $user->reporting_permission = "1";
                $user->journal_permission = "1";
                $user->deposits_permission = "1";
                $user->checks_permission = "1";
                $user->reciepts_permission = "1";
                $user->payroll_permission = "0";
                $user->address_id = Address::All()->First()->id;
                $user->timezone = "";
                $user->timezoneoffset = "";
                $user->branch_id = null;
                $user->name = "";
                $user->rate = "";
                $user->frequency = "";
                $user->phonenumber = "";
                $user->GoogleAccessToken = null;
                $user->save();

                Mail::to($user->email)->send(new LiveDemoValidation($user));

                return Redirect::to('/DemoSignup/')
                    ->with('done', 'done');
            }
        }
    }

    public function RandomPassword(){
        $length = 16;

        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
            '0123456789';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i=0; $i < $length; $i++){
            $str .= $chars[random_int(0, $max)];
        }

        return $str;
    }

    /*
    public function showWelcome()
    {
        if (Auth::user()->hasPermission("client_permission") ){
            $clients = Client::with('primarycontact')->withTrashed()->get();

            $recevables = 0.00;
            $quotevalue = 0.00;

            foreach($clients as $client){
                if($client->getStatus() === "Client"){
                    $recevables += $client->getBalence(false);
                }else{
                    $quotevalue += $client->getOpenQuoteValue(false);
                }
            }
        }else{
            $clients = "";
            $recevables = 0.00;
            $quotevalue = 0.00;
        }

        if (Auth::user()->hasPermission("vendor_permission") ){
            $vendors = Vendor::with('primarycontact')->withTrashed()->with('address')->get();

            $checks = Check::where('printqueue', '1')->get();

            $unprintedchecks = 0.00;
            foreach($checks as $check){
                $unprintedchecks = $unprintedchecks + $check->amount;
            }


        }else{
            $unprintedchecks = 0.00;
            $vendors = "";
        }

        if (Auth::user()->hasPermission("employee_permission") ){
            $account = app()->make('account');
            if($account->subdomain === "livedemo"){
                $employees = UserHelper::GetAllUsersCanLogin();
            }else{
                $employees = UserHelper::GetAllUsers();
            }

            $freqencysetting = Setting::where('name' , 'Payroll-Frequency')->first();
            $optionsetting = Setting::where('name' , 'Payroll-Option')->first();
            $firstsetting = Setting::where('name' , 'Payroll-First')->first();

            if($freqencysetting != null){
                if($firstsetting != null){
                    $payroll =  PayrollHelper::GetNextPayPeriod($freqencysetting->value, $optionsetting->value, $firstsetting->value);
                }else{
                    $payroll = null;
                }
            }else{
                $payroll = null;
            }
        }else{
            $employees = "";

            $payroll = null;
        }

        return View::make('main')
            ->with('recevables', $recevables)
            ->with('quotevalue', $quotevalue)
            ->with('unprintedchecks', $unprintedchecks)
            ->with('payroll', $payroll)
            ->with('clients', $clients)
            ->with('vendors', $vendors)
            ->with('employees', $employees);
    }
    */
    
}
