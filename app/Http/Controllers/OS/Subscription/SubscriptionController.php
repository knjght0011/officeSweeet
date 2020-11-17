<?php
namespace App\Http\Controllers\OS\Subscription;

use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;

use App\Models\Management\Promotion;
use Doctrine\DBAL\Platforms\Keywords\OracleKeywords;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
#use App\Models\User;
use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
#use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use \App\Providers\EventLog;

#use App\Helpers\TransnationalHelper;

use App\Models\User;

#use App\Mail\SubscriptionSurvey;
#use App\Mail\SubscriptionCancelConfirmation;

use App\Helpers\AccountHelper;
use App\Helpers\Management\AlertHelper;
use App\Helpers\OS\Financial\CardPaymentHelper;
use App\Helpers\TransnationalHelper;

class SubscriptionController extends Controller
{
    protected $namespace = 'App\Http\Controller\OS\Subscription';

    public function showSummery($subdomain)
    {
        if (Auth::user()->hasPermission('acp_subscription_permission') ) {

            $account = app()->make('account');

            $plans = Promotion::where('showinsubs', 1)->orderBy('cost', 'asc')->get();

            if (count($account) === 1) {

                $TNdata = $account->TNSubscription();

                if (count($TNdata) === 0) {
                    $date = "None";
                    $lastbilledamount = "Unknown";
                } else {
                    $date = Carbon::parse($TNdata[count($TNdata) - 1]['action']['date'])->toDateString();
                    $lastbilledamount = "$" . $TNdata[count($TNdata) - 1]['action']['amount'];
                }

                if (count($account->subscriptions) > 0) {
                    $summeryarray = array(
                        'Licensed Users' => $account->licensedusers,
                        'Current Number of Users' => count(UserHelper::GetAllUsersCanLogin()),
                        'Number of times billed' => count($TNdata),
                        'Last time billed' => $date,
                        'Last billed amount' => $lastbilledamount,
                    );
                } else {
                    $summeryarray = array(
                        'Licensed Users' => $account->licensedusers,
                        'Current Number of Users' => count(UserHelper::GetAllUsersCanLogin()),
                        'Number of times billed' => 'No subscription',
                        'Last time billed' => 'No subscription',
                        'Last billed amount' => 'No subscription',
                    );
                }


                return View::make('OS.Subscription.summery')
                    ->with('plans', $plans)
                    ->with('account', $account)
                    ->with('summeryarray', $summeryarray);

            } else {
                //should never get here but just incase something weird happens
                return "Error";
            }
        }else{
            return View::make('OS.Subscription.nopermission');
        }
    }


    public static function SignUp(){

        if (Auth::user()->hasPermission('acp_subscription_permission') ) {
            $account = app()->make('account');

            $plans = Promotion::where('showinsubs', 1)->orderBy('cost', 'asc')->get();


            $now = Carbon::now();
            $years = array();

            array_push($years, $now->year);
            $x = 1;

            while($x <= 10) {
                $now->addYear();
                array_push($years, $now->year);
                $x++;
            }

            return View::make('OS.Subscription.signup')
                ->with('plans', $plans)
                ->with('years', $years)
                ->with('account', $account);

        }else{
            return View::make('OS.Subscription.nopermission');
        }
    }

    public static function AccountDisabled(){


            $account = app()->make('account');

            $now = Carbon::now();
            $years = array();

            array_push($years, $now->year);
            $x = 1;

            while($x <= 10) {
                $now->addYear();
                array_push($years, $now->year);
                $x++;
            }

            return View::make('OS.Subscription.disabled')
                ->with('years', $years)
                ->with('account', $account);

    }

    public static function doSubscription($subdomain)
    {
        if (Auth::user()->hasPermission('acp_subscription_permission') ) {
            $data = array(
                'cardNumber' => Input::get('cardNumber'),
                'cardExpiryMonth' => Input::get('cardExpiryMonth'),
                'cardExpiryYear' => Input::get('cardExpiryYear'),
                'cardCVC' => Input::get('cardCVC'),
                'amount' => Input::get('amount'),
                'mode' => Input::get('mode'),
                'ipaddress' => Request::ip(),
                'tax' => '0',
                'numusers' => Input::get('numusers'),
                'plan' => Input::get('plan'),


                'firstname' => Input::get('firstname'),
                'lastname' => Input::get('lastname'),
                'email' => Input::get('email'),

                'address1' => Input::get('address1'),
                'address2' => Input::get('address2'),
                'city' => Input::get('city'),
                'state' => Input::get('state'),
                'zip' => Input::get('zip'),
                'country' => Input::get('country'),
            );

            $valid = CardPaymentHelper::ValidateCardData($data);

            $valid = "valid";

            if($valid === "valid"){
                if($data['mode'] === "subscribe"){
                    $transaction = TransnationalHelper::StartSubscription($data);
                    return $transaction->response . ":" . $transaction->responsetext;
                }
                if($data['mode'] === "time"){
                    $data['amount'] = 10;
                    $transaction = TransnationalHelper::ProcessTrialExtentionPayment($data);
                    return $transaction->response . ":" . $transaction->responsetext;
                }
            }else{
                return $valid;
            }

        }else{
            return "";
        }
    }

    public static function doCancel($subdomain)
    {
        if (Auth::user()->hasPermission('acp_subscription_permission') ) {
            $data = array(
                'reason' => Input::get('reason'),
                'text' => Input::get('text'),
            );

            AlertHelper::NewAlert($subdomain, "Subscription Cancellation", "Subscription Cancellation", true, $data);

            return "sucess";
        }else{
            return "";
        }
    }    

    public static function doUpdate($subdomain)
    {
        if (Auth::user()->hasPermission('acp_subscription_permission') ) {
            $data = array(
                'number_of_users' => Input::get('numberofusers'),
                'new_price' => Input::get('price'),
                'new_plan' => Input::get('plan'),
                //'time_downgrade' => Input::get('timedowngrade'),
            );



            AlertHelper::NewAlert($subdomain, "Subscription Update", "User: " . Auth::user()->firstname . " " . Auth::user()->lastname . " wants to update their subscription to " . $data['new_plan'], true, $data);
            //EventLog::add("Subscription updated to " . $data['number_of_users'] . " users at $" . $data['new_price'] . " per month." );

            /*
            if($data['time_downgrade'] === "true"){
                $users =  UserHelper::GetAllUsersCanLogin();
                foreach($users as $user){
                    if($user->id != Auth::user()->id){
                        $user->canlogin = 0;
                        $user->save();
                    }
                }
            }
            */

            return ['status' => 'OK'];
        }else{
            return ['status' => 'Not Authorised'];
        }
    }    
    /*
    public static function viewSummery($subdomain)
    {

        $account = Account::where('subdomain', $subdomain)->first();

        if (count($account) === 1) {

            $users = User::where('canlogin', 1)->get();

            $numofusers = count($users);

            $tansnationaldata = TransnationalHelper::GetTransationID($account->subscription_id);

            return View::make('OS.Subscription.summery')
                ->with('account', $account)
                ->with('numofusers', $numofusers)
                ->with('tansnationaldata', $tansnationaldata);

        } else {

            return EventLog::Error('Unable to find account.');

        }
    }

    public static function viewUpdate($subdomain)
    {

        $account = Account::where('subdomain', $subdomain)->first();

        if (count($account) === 1) {

            return View::make('OS.Subscription.update')
                ->with('account', $account);

        } else {

            return EventLog::Error('Unable to find account.');

        }
    }

    public static function viewCancel($subdomain)
    {

        $account = Account::where('subdomain', $subdomain)->first();

        if (count($account) === 1) {

            return View::make('OS.Subscription.cancel')
                ->with('account', $account);

        } else {

            return EventLog::Error('Unable to find account.');

        }

    }

    */
}
