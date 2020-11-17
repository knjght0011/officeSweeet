<?php

namespace App\Http\Controllers\Promotions;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

use App\Helpers\AccountHelper;

use App\Models\Management\Promotion;

use App\Helpers\OS\Financial\CardPaymentHelper;
use App\Helpers\TransnationalHelper;

class PromotionController extends Controller {

    public function showPromotions($promocode = null){

        if($promocode === null){
            $promotions = Promotion::whereDate('starts_at', '<', date('Y-m-d'))
                                    ->whereDate('expires_at', '>', date('Y-m-d'))
                                    ->where('showonpublic', 1)
                                    ->get();
        }else{
            $promotions = Promotion::where('tn_plan_name', '=', $promocode)->first();
            if(count($promotions) === 0){
                $promotions = Promotion::whereDate('starts_at', '<', date('Y-m-d'))
                                        ->whereDate('expires_at', '>', date('Y-m-d'))
                                        ->where('showonpublic', 1)
                                        ->get();
            }
        }

        $now = Carbon::now();
        $years = array();

        array_push($years, $now->year);
        $x = 1;

        while($x <= 10) {
            $now->addYear();
            array_push($years, $now->year);
            $x++;
        }

        return View::make('Promotions.promotions')
            ->with('years',$years)
            ->with('promotions',$promotions);
    }

    public function showCheckoutPromotions($promocode = null){

        $promotion = Promotion::where('tn_plan_name', '=', $promocode)
                            ->whereDate('expires_at', '>', date('Y-m-d'))->first();
        if(count($promotion) === 1){

            $now = Carbon::now();
            $years = array();

            array_push($years, $now->year);
            $x = 1;

            while($x <= 10) {
                $now->addYear();
                array_push($years, $now->year);
                $x++;
            }

            return View::make('Promotions.justcheckout')
                ->with('years',$years)
                ->with('promotion',$promotion);

        }else{
            return "unknown promo";
        }
    }


    public function doSubscription()
    {
        $data = array(
            'cardNumber' => Input::get('cardNumber'),
            'cardExpiryMonth' => Input::get('cardExpiryMonth'),
            'cardExpiryYear' => Input::get('cardExpiryYear'),
            'cardCVC' => Input::get('cardCVC'),

            'firstname' => Input::get('firstname'),
            'lastname' => Input::get('lastname'),
            'email' => Input::get('email'),
            'phonenumber' => Input::get('phone'),
            'company' => Input::get('company'),
            'businessrole' => Input::get('company'),

            'address1' => Input::get('address1'),
            'city' => Input::get('city'),
            'state' => Input::get('state'),
            'zip' => Input::get('zip'),
            'country' => Input::get('country'),

            'version' => 'PROMO',
            'referred_by' => 'Promotions Page',
            'referred_name' => Input::get('referalcode'),
            'number_of_users' => Input::get('number_of_users'),
            'active' => Carbon::now(),
            'plan_name' => Input::get('referalcode'),

            'subdomain' => AccountHelper::seoUrl(Input::get('company')),
            'DBPassword' => AccountHelper::GeneratePassword(),
            'UserPassword' => AccountHelper::GeneratePassword(),
            'transaction_id' => "",
            'subscription_id' => null,
            'token' => null,
        );

        $validator = $this->ValidateInput($data);

        if ($validator->fails()) {
            return ['status' => 'validation' , 'errors' => $validator->errors()->toArray()];
        }else{
            
            $valid = CardPaymentHelper::ValidateCardData($data);

            if($valid === "valid"){

                $transaction = TransnationalHelper::StartSubscriptionPromo($data);

                return ['status' => 'OK', '' =>  $transaction->response . ":" . $transaction->responsetext];

            }else{
                return ['status' => 'cardvalidation', 'errors' => $valid];
            }
        }


    }

    public function ValidateInput($data)
    {
        $rules = array(
            'firstname' => 'string',
            'lastname' => 'string',
            'email' => 'email',
            'phonenumber' => 'numeric',
            'company' => 'string',
            'businessrole' => 'string|min:3',

            'address1' => 'string',
            'city' => 'string',
            'state' => 'string',
            'zip' => 'string',
            'country' => 'string',

            'number_of_users' => 'numeric',
           # 'plan_name' => 'exists:table,column',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;
    }

    public function addPromotion(){

        $data = Array(
                'name' => Input::get('name'),
                'description' => Input::get('description'),
                'tn_plan_name' => Input::get('tn_plan_name'),
                'numusers' => Input::get('numusers'),
                'cost' => Input::get('cost'),
                'showonpublic' => 0,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now(),
                );


        $validator = $this->ValidatePlan($data);

        if ($validator->fails()) {
            return ['status' => 'validation' , 'errors' => $validator->errors()->toArray()];
        }else{

            $plan = TransnationalHelper::AddPlan($data['tn_plan_name'], $data['cost']);

            switch ($plan) {
                case "1":
                    $promotion = new Promotion;
                    $promotion->name = $data['name'];
                    $promotion->description = $data['description'];
                    $promotion->tn_plan_name = $data['tn_plan_name'];
                    $promotion->numusers = $data['numusers'];
                    $promotion->cost = $data['cost'];
                    $promotion->showonpublic = $data['showonpublic'];
                    $promotion->starts_at = $data['starts_at'];
                    $promotion->expires_at = $data['expires_at'];
                    $promotion->save();

                    return ['status' => 'OK'];
                    break;
                case "2":
                    return ['status' => 'unknowntnresponce', 'responce' => $plan];
                    break;
                case "3":
                    return ['status' => 'plannameallreadyexists'];
                    break;
                default:
                    return ['status' => 'unknowntnresponce', 'responce' => $plan];
            }

        }
    }


    public function ValidatePlan($data)
    {
        $rules = array(
            'name' => 'string',
            'description' => 'string',
            'tn_plan_name' => 'string',
            'numusers' => 'numeric',
            'cost' => 'numeric',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;
    }




/*






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

    public function DoSignup(){

        $data = Input::all();

        $data['subdomain'] = AccountHelper::seoUrl($data['company']);
        $data['UserPassword'] = AccountHelper::GeneratePassword();
        $data['DBPassword'] = AccountHelper::GeneratePassword();

        $data['version'] = "2";
        $data['number_of_users'] = 3;
        $data['plan_name'] = "";
        $data['subscription_id'] = null;
        $data['transaction_id'] = "";
        $data['active'] = carbon::now()->addDays(8);

        $validator = $this->ValidateInput($data);

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }else{
            $account = AccountHelper::AddToAccountTable($data['subdomain'], $data['subdomain'], $data['subdomain'], $data['DBPassword'], $data);

            $newtask = new Taskqueue;
            $newtask->jobname = "Provision-CreateDB";
            $newtask->account_id = $account->id;
            $newtask->save();

            return "";
        }
    }

    public function DoSignupTest(){

        $data = Input::all();

        $debug = var_export($data, true);

        Cache::put('signup-info', $debug, 10);

        return "";

    }

    public function GetSignupInfo(){

        return Cache::get('signup-info');

    }
*/
}
