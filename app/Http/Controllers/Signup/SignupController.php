<?php

namespace App\Http\Controllers\Signup;

use App\Helpers\TransnationalHelper;
use App\Http\Controllers\Controller;
use App\Mail\SignupEmail;
use App\Models\Client;
use App\Models\Management\Account;
use App\Models\Management\Promotion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

use App\Helpers\AccountHelper;

use App\Models\Management\TaskQueue;

class SignupController extends Controller {
    
    public function showSignup(){
        return View::make('SignUp.signup');
    }

    public function ValidateInput($data)
    {
        $rules = array(
            'email' => 'email',
            'subdomain' => 'min:3',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;
    }

    public function DoSoloSignup(){

        $data = Input::all();

        try{

            $data['subdomain'] = AccountHelper::seoUrl($data['company']);
            $data['UserPassword'] = AccountHelper::GeneratePassword();
            $data['DBPassword'] = AccountHelper::GeneratePassword();


            $data['version'] = "47FREE";
            $data['number_of_users'] = 1;
            $data['plan_name'] = "47FREE";

            $data['subscription_id'] = null;
            $data['transaction_id'] = "";

            if(isset($data['source'])){
                if($data['source'] === "pimpmybrandname"){
                    $data['active'] = carbon::now()->addDays(15);
                }else{
                    $data['active'] = carbon::now()->addDays(8);
                }
            }else{
                $data['active'] = carbon::now()->addDays(8);
            }

            $data['address1'] = "Unknown";
            $data['city'] = "Unknown";
            $data['state'] = "Unknown";
            $data['zip'] = "Unknown";
            $data['token'] = $this->GenToken();

            $validator = $this->ValidateInput($data);

            if($data['subdomain'] === "error") {
                return Response::make('Duplicate Subdomain', 500);
            }else{
                if ($validator->fails()) {
                    return $validator->errors()->toArray();
                } else {
                    $account = AccountHelper::AddToAccountTable($data['subdomain'], $data['subdomain'], $data['subdomain'], $data['DBPassword'], $data);

                    $account->SendSignupEmailInstall();
                    $redirectUrl = "https://www.officesweeet.store/thankyou";
                    return Redirect::away($redirectUrl)->with(['firstname'=>$data['firstname']]);
                    //Redirect::to('https://api.officesweeet.com/ProvisioningCheck/' . $account->subdomain);
                    //ProvisioningCheck just returns a value indicating if the acct is there.
                    //Change to be a view

                }
            }
        }catch (\Throwable $t) {
            app('sentry')->captureException($t);
            return Response::make(view('errors.404'), 404);
        }

    }

    public function welcome()
    {
        return View::make('SignUp.welcome');
    }

    public function DoNewSignup(){

        try{
            $data = Input::all();

            $data['subdomain'] = AccountHelper::seoUrl($data['company']);
            $data['UserPassword'] = AccountHelper::GeneratePassword();
            $data['DBPassword'] = AccountHelper::GeneratePassword();

            switch ($data['amount']){
                case "47.00":
                    $data['version'] = "SOLO";
                    $data['number_of_users'] = 1;
                    $data['plan_name'] = "1user47Mar2019";
                    break;
                case "97.00":
                    $data['version'] = "SMALL BUSINESS";
                    $data['number_of_users'] = 3;
                    $data['plan_name'] = "3user97Mar2019";
                break;
                case "197.00":
                    $data['version'] = "LARGE BUSINESS";
                    $data['number_of_users'] = 9;
                    $data['plan_name'] = "9user197Mar2019";
                    break;
                case "297.00":
                    $data['version'] = "ENTERPRISE";
                    $data['number_of_users'] = 19;
                    $data['plan_name'] = "0user297Mar2019";
                    break;
                default:
                    $data['version'] = "SOLO";
                    $data['number_of_users'] = 1;
                    $data['plan_name'] = "TRIAL";
            }

            $data['subscription_id'] = null;
            $data['transaction_id'] = "";

            if(isset($data['source'])){
                if($data['source'] === "pimpmybrandname"){
                    $data['active'] = carbon::now()->addDays(15);
                }else{
                    $data['active'] = carbon::now()->addDays(8);
                }
            }else{
                $data['active'] = carbon::now()->addDays(8);
            }

            $data['address1'] = "Unknown";
            $data['city'] = "Unknown";
            $data['state'] = "Unknown";
            $data['zip'] = "Unknown";
            $data['token'] = $this->GenToken();

            $validator = $this->ValidateInput($data);

            if($data['subdomain'] === "error") {
                return Response::make('Duplicate Subdomain', 500);
            }else{
                if ($validator->fails()) {
                    return $validator->errors()->toArray();
                } else {
                    $account = AccountHelper::AddToAccountTable($data['subdomain'], $data['subdomain'], $data['subdomain'], $data['DBPassword'], $data);

                    $account->SendSignupEmailInstall();
                    $redirectUrl = "https://www.officesweeet.store/thankyou";
                    return Redirect::away($redirectUrl)->with(['firstname'=>$data['firstname']]);
                    //return Redirect::to('https://api.officesweeet.com/ProvisioningCheck/' . $account->subdomain);
                }
            }
        }catch (\Throwable $t) {
            app('sentry')->captureException($t);
        }
    }

    public function VerifySignup($token){

        $account = Account::where('token', '=', $token)->first();

        if(count($account) === 1){

            $newtask = new Taskqueue;
            $newtask->jobname = array("Provision-CreateDB");
            $newtask->account_id = $account->id;
            $newtask->save();

            $account->token = null;
            $account->save();

// GJGJGJ 10.18.2010
// replace the View::Make call with redirect to Wix welcome page
            $redirectUrl = "https://www.officesweeet.store/welcome";
            return Redirect::away($redirectUrl)->with(['firstname'=>$account['firstname']]);

//            return View::make('SignUp.provisioning')
//                ->with('subdomain', $account->subdomain);

        }else{

            return "Token not found";
            //return View::make('SignUp.provisioning');

        }

    }

    public function VerifyTest(){

        return View::make('SignUp.provisioning')
            ->with('subdomain', "uniquecrontestsystem");

    }


    public function ProvisioningCheck($subdomain){

        $account = Account::where('subdomain', '=', $subdomain)->first();

        if(count($account) === 1){
            return $account->installstage;
        }else{
            return "1";
        }

    }

    public function GenToken()
    {
        $length = 255;

        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
            '0123456789';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i=0; $i < $length; $i++){
            $str .= $chars[random_int(0, $max)];
        }

        return $str;

    }

    //lls Specific stuff
    public function showLLSSignup($subdomain, $clientid){

        $client = Client::where('id', $clientid)->first();

        if(count($client) === 1){

            $llsclientinfo = Account::where('client_id' , $client->id)->first();
            if(count($llsclientinfo) === 0){

                $promotions = Promotion::all();

                return View::make('OS.LLSSignup.view')
                    ->with('promotions', $promotions)
                    ->with('sub', AccountHelper::seoUrl($client->getName()))
                    ->with('client', $client);
            }else{
                return "Client allready has system";
            }

        }else{
            return "unknown client";
        }
    }

    public function doLLSSignup()
    {
        $client = Client::where('id', Input::get('clientid'))->first();

        if(count($client) === 1){

            $branch = array(
                'number' => $client->address->number,
                'address1' => $client->address->address1,
                'address2' => $client->address->address1,
                'city' => $client->address->city,
                'region' => $client->address->region,
                'state' => $client->address->state,
                'zip' => $client->address->zip,
                'type' => "",
            );

            $data = array(
                'cardNumber' => Input::get('cardNumber'),
                'cardExpiryMonth' => Input::get('cardExpiryMonth'),
                'cardExpiryYear' => Input::get('cardExpiryYear'),
                'cardCVC' => Input::get('cardCVC'),

                'firstname' => Input::get('firstname'),
                'lastname' => Input::get('lastname'),
                'email' => $client->primarycontact->email,
                'phonenumber' => "",
                'company' => $client->getName(),
                'businessrole' => $client->getName(),


                'address1' => $client->address->number . " " . $client->address->address1,
                'city' => $client->address->city,
                'state' => $client->address->state,
                'zip' => $client->address->zip,
                'country' => "",

                'branch' => $branch,

                'version' => 'PROMO',
                'referred_by' => app()->make('account')->subdomain,
                'referred_name' => Auth::user()->email,
                //'number_of_users' => Input::get('number_of_users'),
                'active' => Carbon::now(),
                'plan_name' => Input::get('plan_name'),

                'subdomain' => AccountHelper::seoUrl(Input::get('subdomain')),
                'DBPassword' => AccountHelper::GeneratePassword(),
                'UserPassword' => AccountHelper::GeneratePassword(),
                'transaction_id' => "",
                'subscription_id' => null,
                'token' => null,

                'clientid' => $client->id
            );

            $promotion = Promotion::where('tn_plan_name', $data['plan_name'] )->first();
            if(count($promotion) === 1){
                $data['number_of_users'] = $promotion->numusers;
            }else{
                return ['status' => 'UnRecognisedPlan'];
            }

            if($data['subdomain'] === "error"){
                return ['status' => 'SubdomainTaken'];
            }else{
                $transaction = TransnationalHelper::StartSubscriptionPromo($data);
                if($transaction->response === "1"){

                    $client->existingclient = 1;
                    $client->save();

                    return ['status' => 'OK', 'response' =>  $transaction->response, 'responsetext' => $transaction->responsetext];
                }else{
                    return ['status' => 'Failed', 'response' =>  $transaction->response, 'responsetext' => $transaction->responsetext];
                }
            }
        }else{
            return ['status' => 'notfound'];
        }
    }

    //Broker Specific Stuff
    public function showBrokerSignup($subdomain, $clientid){

        $client = Client::where('id', $clientid)->first();

        if(count($client) === 1){

            return View::make('OS.BrokerSignup.view')
                ->with('sub', AccountHelper::seoUrl($client->getName()))
                ->with('client', $client);
        }else{
            return "unknown client";
        }
    }

    public function doBrokerSignup()
    {
        $client = Client::where('id', Input::get('clientid'))->first();

        if(count($client) === 1){

            $branch = array(
                'number' => $client->address->number,
                'address1' => $client->address->address1,
                'address2' => $client->address->address1,
                'city' => $client->address->city,
                'region' => $client->address->region,
                'state' => $client->address->state,
                'zip' => $client->address->zip,
                'type' => "",
            );

            $data = array(
                'cardNumber' => Input::get('cardNumber'),
                'cardExpiryMonth' => Input::get('cardExpiryMonth'),
                'cardExpiryYear' => Input::get('cardExpiryYear'),
                'cardCVC' => Input::get('cardCVC'),

                'firstname' => Input::get('firstname'),
                'lastname' => Input::get('lastname'),
                'email' => $client->primarycontact->email,
                'phonenumber' => "",
                'company' => $client->getName(),
                'businessrole' => $client->getName(),


                'address1' => $client->address->number . " " . $client->address->address1,
                'city' => $client->address->city,
                'state' => $client->address->state,
                'zip' => $client->address->zip,
                'country' => "",

                'branch' => $branch,

                'version' => 'BROKER-SIGNUP',
                'referred_by' => app()->make('account')->subdomain,
                'referred_name' => Auth::user()->email,
                //'number_of_users' => Input::get('number_of_users'),
                'active' => Carbon::now(),
                'plan_name' => Input::get('plan_name'),

                'subdomain' => AccountHelper::seoUrl(Input::get('subdomain')),
                'DBPassword' => AccountHelper::GeneratePassword(),
                'UserPassword' => AccountHelper::GeneratePassword(),
                'transaction_id' => "",
                'subscription_id' => null,
                'token' => null,

                'broker_id' => app()->make('account')->BrokerID(),
                'broker_user_id' => Auth::user()->id,
            );

            switch ($data['plan_name']) {
                case "Sept2018_Upto3":
                    $data['number_of_users'] = 3;
                    break;
                case "Sept2018_Upto9":
                    $data['number_of_users'] = 9;
                    break;
                case "Sept2018_Unlimited":
                    $data['number_of_users'] = 0;
                    break;
                default:
                    return ['status' => 'UnRecognisedPlan'];
            }

            if($data['subdomain'] === "error"){
                return ['status' => 'SubdomainTaken'];
            }else{
                $transaction = TransnationalHelper::StartSubscriptionPromo($data);
                if($transaction->response === "1"){

                    $client->existingclient = 1;
                    $client->save();

                    return ['status' => 'OK', 'response' =>  $transaction->response, 'responsetext' => $transaction->responsetext];
                }else{
                    return ['status' => 'Failed', 'response' =>  $transaction->response, 'responsetext' => $transaction->responsetext];
                }
            }
        }else{
            return ['status' => 'notfound'];
        }
    }

    public function SubdomainCheck(){
        return ['status' => 'OK', 'subdomain' => AccountHelper::seoUrl(Input::get('subdomain'))];
    }


}
