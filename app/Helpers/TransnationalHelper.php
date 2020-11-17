<?php
namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Management\Account;
use App\Models\OS\Financial\TransnationalTransaction;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Deposit;
use App\Models\DepositLink;
use App\Models\User;

use App\Classes\tngwapi;

use App\Helpers\Management\AlertHelper;
use App\Models\Management\TaskQueue;

class TransnationalHelper
{
    const username = "officesweet";
    const password = "sweet5200";

    public static function QueryAPI($id)
    {
        $url = 'https://secure.tnbcigateway.com/api/query.php';
        $data = array("username" => self::username, "password" => self::password, "transaction_id" => $id); 

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        $testXmlSimple= new \SimpleXMLElement($result);

        return $testXmlSimple;
        
    }

    public static function GetTransationID($id)
    {
        $result = self::QueryAPI($id);
        $transaction = get_object_vars ( $result->transaction );
        $transaction['action'] = get_object_vars ( $result->transaction->action );

        return $transaction;
    }

    public static function GetSubscriptionID($id){
        $result = self::QueryAPI($id);

        $transaction = array();
        foreach($result->transaction as $subtransaction){
            $temp = get_object_vars ( $subtransaction );
            $temp['action'] = get_object_vars ( $subtransaction->action );

            array_push($transaction,$temp);
        }

        #var_dump($transaction);
        return $transaction;

    }


    public static function SetDeploymentToLLS()
    {
        $account = Account::where('subdomain', '=', "lls")->first();
        config(['database.connections.deployment.username' => $account->username]);
        config(['database.connections.deployment.password' => $account->password]);
        config(['database.connections.deployment.port' => $account->port]);
        config(['database.connections.deployment.database' => $account->database]);
        \DB::connection('deployment')->reconnect();
    }

    public static function StartSubscription($data)
    {

        if(isset($data['plan'])){
            $planname = $data['plan'];
        }else{
            if($data['numusers'] > 10){
                $planname = self::CheckPlan($data['numusers']);
            }else{
                $planname = $data['numusers'] . "user_Jan2017";
            }
        }

        $account = app()->make('account');

        $data['start_date'] = Carbon::now()->addDays(1)->format('Ymd');

        $SubQueryString = "username=" . self::username;
        $SubQueryString .= "&password=" . self::password;
        $SubQueryString .= "&recurring=add_subscription";
        $SubQueryString .= "&start_date=" . $data['start_date'];
        $SubQueryString .= "&plan_id=" . $planname;
        $SubQueryString .= "&ccnumber=". $data['cardNumber'];
        $SubQueryString .= "&ccexp=". $data['cardExpiryMonth'] . $data['cardExpiryYear'];
        $SubQueryString .= "&cvv=". $data['cardCVC'];
        $SubQueryString .= "&payment=creditcard";
        $SubQueryString .= "&first_name=" . urlencode($data['firstname']);
        $SubQueryString .= "&last_name=" . urlencode($data['lastname']);
        $SubQueryString .= "&address1=" . urlencode($data['address1'] . " " . $data['address2']);
        $SubQueryString .= "&city=" . urlencode($data['city']);
        $SubQueryString .= "&state=" . urlencode($data['state']);
        $SubQueryString .= "&zip=" . $data['zip'];
        $SubQueryString .= "&country=" . urlencode($data['country']);
        $SubQueryString .= "&email=" . $data['email'];
        $SubQueryString .= "&orderid=" . $account->id;

        $gw = new tngwapi;
        $gw->setLogin(self::username, self::password);
        $r = $gw->_doPost($SubQueryString);

        self::SetDeploymentToLLS();

        if ($gw->responses['response'] === "1"){
            #$account = app()->make('account');
            $client = $account->FindLLSClient();
            if ($client != false) {
                #$amount = Self::PlanAmount($data['numusers']);

                #$transaction = Self::UpdateLLS($account, $client, "OfficeSweeet subscription for " . $data['numusers'] . " users started.", "Subscription Started", $amount, $gw->responses, $data['numusers']);
                $transaction = self::StoreResponceInLLS($gw->responses, null);

                $account->active = Carbon::now()->addDays(3);

                if($data['numusers'] === "unlimited"){
                    $account->licensedusers = 9999;
                }else{
                    $account->licensedusers = $data['numusers'];
                }

                #$account->subscription_id = $transaction->transactionid;

                $sub = $account->subscriptions()->create([
                    'subscription_id' => $transaction->transactionid,
                ]);

                $account->plan_name = $planname;

                $account->save();

                AlertHelper::NewAlert($account->subdomain, "Subscription Started", "New subscription for " . $data['numusers'] . " users.");

                return $transaction;
            } else {
                $transaction = self::StoreResponceInLLS($gw->responses, null);
                return $transaction;
            }
        }else{
            $transaction = self::StoreResponceInLLS($gw->responses, null);
            return $transaction;
        }

    }


    public static function StartSubscriptionPromo($data)
    {

        $data['start_date'] = Carbon::now()->addDays(1)->format('Ymd');

        $SubQueryString = "username=" . self::username;
        $SubQueryString .= "&password=" . self::password;
        $SubQueryString .= "&recurring=add_subscription";
        $SubQueryString .= "&start_date=" . $data['start_date'];
        $SubQueryString .= "&plan_id=" . $data['plan_name'];
        $SubQueryString .= "&ccnumber=". $data['cardNumber'];
        $SubQueryString .= "&ccexp=". $data['cardExpiryMonth'] . $data['cardExpiryYear'];
        $SubQueryString .= "&cvv=". $data['cardCVC'];
        $SubQueryString .= "&payment=creditcard";
        $SubQueryString .= "&first_name=" . urlencode($data['firstname']);
        $SubQueryString .= "&last_name=" . urlencode($data['lastname']);
        $SubQueryString .= "&address1=" . urlencode($data['address1']);
        $SubQueryString .= "&city=" . urlencode($data['city']);
        $SubQueryString .= "&state=" . urlencode($data['state']);
        $SubQueryString .= "&zip=" . $data['zip'];
        $SubQueryString .= "&country=" . urlencode($data['country']);
        $SubQueryString .= "&email=" . $data['email'];
        $SubQueryString .= "&orderid=" . "1234";

        $gw = new tngwapi;
        $gw->setLogin(self::username, self::password);
        $r = $gw->_doPost($SubQueryString);

        #return $gw->responses;


        self::SetDeploymentToLLS();

        if ($gw->responses['response'] === "1"){

            if(isset($data['clientid'])){
                $account = AccountHelper::AddToAccountTable($data['subdomain'], $data['subdomain'], $data['subdomain'], $data['DBPassword'], $data, $data['clientid']);
            }else{
                $account = AccountHelper::AddToAccountTable($data['subdomain'], $data['subdomain'], $data['subdomain'], $data['DBPassword'], $data);
            }


            $transaction = self::StoreResponceInLLS($gw->responses, null);

            $account->active = Carbon::now()->addDays(3);
            $account->licensedusers = $data['number_of_users'];
            #$account->subscription_id = $transaction->transactionid;

            $sub = $account->subscriptions()->create([
                'subscription_id' => $transaction->transactionid,
            ]);

            $account->save();

            $account->SendSignupEmailInstall();

            AlertHelper::NewAlert($account->subdomain, "Subscription Started", "New subscription for " . $data['number_of_users'] . " users.");

            $newtask = new Taskqueue;
            $newtask->jobname = array("Provision-CreateDB");
            $newtask->account_id = $account->id;
            $newtask->save();

            return $transaction;

        }else{
            $transaction = self::StoreResponceInLLS($gw->responses, null);
            return $transaction;
        }

    }

    public static function ProcessTrialExtentionPayment($data)
    {
        $gw = new tngwapi;

        $gw->setLogin(self::username, self::password);

        $gw->setOrder("1234", "30 day trial extension", "0", $data['ipaddress']);

        $gw->setBilling($data['firstname'], $data['lastname'], "None", $data['address1'], $data['address2'], $data['city'], $data['state'], $data['zip'], $data['country'], "none", $data['email']);

        $r = $gw->doSale($data['amount'], $data['cardNumber'], $data['cardExpiryMonth'] . "/" . $data['cardExpiryYear'], $data['cardCVC']);

        self::SetDeploymentToLLS();

        if ($gw->responses['response'] === "1"){
            $account = app()->make('account');
            $client = $account->FindLLSClient();
            if ($client != false) {

                $transaction = Self::UpdateLLS($account, $client, "$10 Trial extension purchased", "Account Upgraded", 10, $gw->responses, 1);

                return $transaction;
            } else {
                $transaction = self::StoreResponceInLLS($gw->responses, null);
                return $transaction;
            }
        }else{
            $transaction = self::StoreResponceInLLS($gw->responses, null);
            return $transaction;
        }

    }

    public static function UpdateLLS($account, $client, $description, $alerttype, $amount, $responses, $numberofusers = null, $deployementset = true){

        if($deployementset === false){
            self::SetDeploymentToLLS();
        }

        $quote = self::MakeQuoteOnLLS($client, $description, $amount);
        $deposit = self::MakeDepositOnLLS($quote, $description, $amount);
        $transaction = self::StoreResponceInLLS($responses, $deposit->id);

        $account->active = Carbon::now()->addDays(30);
        $account->transaction_id = $transaction->transactionid;

        if($numberofusers != null){
            if(intval($numberofusers) < 3){
                self::ReduceUsersToOne();
            }
        }

        $account->save();

        AlertHelper::NewAlert($account->subdomain, $alerttype, $description);

        return $transaction;
    }

    public static function StoreResponceInLLS($responce, $depositid){
        $transaction = new TransnationalTransaction;
        $transaction->setConnection('deployment');
        #$transaction->update($responce);
        $transaction->response = $responce['response'];
        $transaction->responsetext = $responce['responsetext'];
        $transaction->authcode = $responce['authcode'];
        $transaction->transactionid = $responce['transactionid'];
        $transaction->avsresponse = $responce['avsresponse'];
        $transaction->cvvresponse = $responce['cvvresponse'];
        $transaction->orderid = $responce['orderid'];
        $transaction->response_code = $responce['response_code'];
        $transaction->deposit_id= $depositid;
        $transaction->save();
        return $transaction;
    }

    public static function MakeQuoteOnLLS($client, $description, $amount, $date = null){

        if($date === null){
            $date = Carbon::now();
        }

        $quote = new Quote;
        $quote->setConnection('deployment');
        $quote->client_id = $client->id;
        $quote->contact_id = $client->primarycontact_id;
        $quote->branch_id = 2;
        $quote->quotenumber = "";
        $quote->comments = "We appreciate your business!";
        $quote->createdbyuser = 1;
        $quote->finalized = 1;
        $quote->finalizedbyuser = 1;
        $quote->finalizeddate = $date;
        $quote->save();

        $quote->quotenumber = $quote->id + 100000;
        $quote->save();

        $quoteitem = new QuoteItem;
        $quoteitem->setConnection('deployment');
        $quoteitem->description = $description;
        $quoteitem->SKU = "";
        $quoteitem->quote_id = $quote->id;
        $quoteitem->costperunit = $amount;
        $quoteitem->units = 1;
        $quoteitem->tax = 0;
        $quoteitem->user_id = 1;
        $quoteitem->save();

        return $quote;
    }

    public static function MakeDepositOnLLS($quote, $description, $amount, $date = null){

        if($date === null){
            $date = Carbon::now();
        }

        $payment = new Deposit;
        $payment->setConnection('deployment');
        $payment->user_id = null;
        $payment->date = $date;
        $payment->amount = $amount;
        $payment->type = "invoice";
        $payment->method = "Credit/Debit Card";
        $payment->comments = $description;
        $payment->catagorys = array('Sales Income' => $amount);
        $payment->save();

        $link = new DepositLink;
        $link->setConnection('deployment');
        $link->amount = $amount;
        $link->deposit_id = $payment->id;
        $link->quote_id = $quote->id;
        $link->save();

        return $payment;
    }

    public static function ReduceUsersToOne(){
        $users =  User::where('canlogin' , 1)->get();
        foreach($users as $user){
            if($user->id != Auth::user()->id){
                $user->canlogin = 0;
                $user->save();
            }
        }
    }

    public static function CheckPlan($numusers){

        $planamount = 9.95 * intval($numusers);
        $planname = $numusers . "user_Jan2017";

        $PlanQueryString = "username=" . self::username;
        $PlanQueryString .= "&password=" . self::password;
        $PlanQueryString .= "&recurring=add_plan";
        $PlanQueryString .= "&plan_payments=0";
        $PlanQueryString .= "&plan_amount=" . $planamount;
        $PlanQueryString .= "&plan_name=" . $planname;
        $PlanQueryString .= "&plan_id=" .$planname;
        $PlanQueryString .= "&day_frequency=30";

        $gw = new tngwapi;

        $r = $gw->_doPost($PlanQueryString);

        return $planname;

    }

    public static function PlanAmount($numberofusers){

        if ($numberofusers == 1) {
            $costperuser = 29;
        }

        if ($numberofusers == 2) {
            $costperuser = 19;
        }
        if ($numberofusers == 3) {
            $costperuser = 19;
        }

        if ($numberofusers == 4) {
            $costperuser = 16;
        }
        if ($numberofusers == 5) {
            $costperuser = 16;
        }
        if ($numberofusers == 6) {
            $costperuser = 16;
        }

        if ($numberofusers == 7) {
            $costperuser = 14;
        }
        if ($numberofusers == 8) {
            $costperuser = 14;
        }
        if ($numberofusers == 9) {
            $costperuser = 14;
        }
        if ($numberofusers >= 10) {
            $costperuser = 9.95;
        }

        $totalcost = $costperuser * $numberofusers;

        return $totalcost;

    }

    public static function AddPlan($planname, $planamount){

        $PlanQueryString = "username=" . self::username;
        $PlanQueryString .= "&password=" . self::password;
        $PlanQueryString .= "&recurring=add_plan";
        $PlanQueryString .= "&plan_payments=0";
        $PlanQueryString .= "&plan_amount=" . $planamount;
        $PlanQueryString .= "&plan_name=" . $planname;
        $PlanQueryString .= "&plan_id=" .$planname;
        $PlanQueryString .= "&day_frequency=30";

        $gw = new tngwapi;

        $r = $gw->_doPost($PlanQueryString);

        return $r;

    }
}
