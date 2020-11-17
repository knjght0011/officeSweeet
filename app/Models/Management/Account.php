<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Helpers\AccountHelper;
use App\Helpers\TransnationalHelper;

use App\Jobs\Provisioning\CreateDB;
use App\Jobs\Provisioning\MigrateDB;
use App\Jobs\Provisioning\SeedDB;

use App\Models\Client;

use App\Mail\WelcomeEmail;
use App\Mail\SignupEmail;

class Account extends Model
{
    protected $connection = 'management';
    
    protected $table = "account";

    protected $casts = [
        'installinfo' => 'array',
        'features' => 'array',
    ];

    protected $dates = ['active'];

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Management\Subscription', 'account_id', 'id');
    }

    public function chatthread()
    {
        return $this->hasMany('App\Models\Management\Chat\TicketThread', 'account_id', 'id');
    }

    public function FindLLSClient(){

        $client = new Client();
        $client->setConnection('deployment');
        $something = $client->find($this->client_id);

        if(count($something) === 1){
            return $something;
        }else{
            return false;
        }
    }

    public function InstallInfo($key){
        $array = $this->installinfo;
        if(isset($array[$key])){
            return $array[$key];
        }else{
            return "";
        }
    }


    public function statusWords()
    {
        if($this->active === 1){
            return "Active";
        }else{
            return "Disabled";
        }
           
    }

    public function DatabaseExists(){

        AccountHelper::Elevate();
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?";
        $db = DB::connection('management')->select($query, [$this->database]);
        AccountHelper::Deelevate();

        if(empty($db)) {
            return false;
        }else {
            return true;
        }
    }

    public function CreateBackEnd()
    {
        if(!$this->DatabaseExists()){

            CreateDB::withChain([
                new MigrateDB($this, true),
                new SeedDB($this, true)
            ])->dispatch($this, true);

            return true;

        }else{
            return "DB allready Exists";
        }

    }

    public function InstallStageWord()
    {
        var_dump($this->installstage);
        switch ($this->installstage) {
            case null:
                return "Waiting to begin";
            case "1":
                return "Creating DB";
            case "2":
                return "Waiting to migrate";
            case "3":
                return "Migrating DB";
            case "4":
                return "Waiting to Seed Data";
            case "5":
                return "Seeding Data";
            case "6":
                return "Done";
            default;
                return "unknown";
        }
    }
 
    public function LLSclient(){
        $client = Client::where('id' , $this->client_id)->first();
        if(count($client) === 1){
            return $client;
        }else{
            return null;
        }
    }
    
    public function LLSclientLink(){
        $client = $this->LLSclient();
        if($client === null){
            return null;
        }else{
            return "<a id='link' href='https://lls.officesweeet.com/Clients/View/" . $this->client_id . "'>" . $client->getName() . "</a>";
        }
    }

    public function TNSubscription(){
        #if($this->subscription_id != null){
        #    return TransnationalHelper::GetSubscriptionID($this->subscription_id);
        #}else{
        #    return false;
        #}

        if(count($this->subscriptions) > 0){
            return $this->subscriptions->last()->TNSubscription();
        }else{
            return false;
        }

    }

    public function SendWelcomeEmailInstall(){
        $this->SendWelcomeEmail($this->InstallInfo('email'));
    }

    public function SendWelcomeEmail($email){
        Mail::to($email)->send(new WelcomeEmail($this));
    }

    public function SendSignupEmailInstall(){
        $this->SendSignupEmail($this->InstallInfo('email'));
    }

    public function SendSignupEmail($email){
        Mail::to($email)->send(new SignupEmail($this));
    }
                    
    public function DaysLeft(){
        if($this->active === null){
            return 999;
        }else{
            $now = Carbon::now();
            return $this->active->diffInDays($now);
        }
    }

    public function HoursLeft(){
        if($this->active === null){
            return 999;
        }else{
            $now = Carbon::now();
            return $this->active->diffInHours($now);
        }
    }

    public function IsActive(){
        if($this->active === null){
            return true;
        }else{
            $now = Carbon::now();
            if($this->active->gt($now)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function GetLogins(){
        config(['database.connections.subdomain.username' => $this->username]);
        config(['database.connections.subdomain.password' => $this->password]);
        config(['database.connections.subdomain.port' => $this->port]);
        config(['database.connections.subdomain.database' => $this->database]);
        \DB::connection('subdomain')->reconnect();

        $users = User::all();

        config(['database.connections.subdomain.username' => app()->make('account')->username]);
        config(['database.connections.subdomain.password' => app()->make('account')->password]);
        config(['database.connections.subdomain.port' => app()->make('account')->port]);
        config(['database.connections.subdomain.database' => app()->make('account')->database]);
        \DB::connection('subdomain')->reconnect();

        return $users;

    }

    public function Remove(){

        #\DB::connection('management')->statement('drop database '. $this->database);

        #$this->subdomain = "disabled";
        #$this->port = "disabled";
        #$this->database = "disabled";
        #$this->username = "disabled";
        #$this->password = "disabled";
        #$this->save();
    }

    public function BrokerID(){

        $broker = Broker::where('account_id', $this->id)->first();
        if(count($broker) === 1){
            return $broker->id;
        }else{
            return null;
        }

    }

    public function HasFeature($name){

        if(isset($this->features[$name])){
            if($this->features[$name] == 1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
}
