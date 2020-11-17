<?php
namespace App\Helpers\Management;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\Management\Alert;
use App\Models\Management\Account;

use App\Mail\Alert as AlertMail;

class AlertHelper
{
    const writeusername = "managementwrite";
    const writepassword = "s3T4Yaka8A2ixOw3Ba6E41fOWOlac1";
    const readusername = "management";
    const readpassword = "G1YuqoBEmIlIS2bI8AKaY5Go4e2imu";
    
    public static function Elevate()
    {
        config(['database.connections.management.username' => self::writeusername]);
        config(['database.connections.management.password' => self::writepassword]);
        DB::connection('management')->reconnect();
    }
    
    public static function Deelevate()
    {
        config(['database.connections.management.username' => self::readusername]);
        config(['database.connections.management.password' => self::readpassword]);
        DB::connection('management')->reconnect();
    }
    
    public static function NewAlert($subdomain, $type, $description, $needsaction = false, $varibles = null){
        self::Elevate();
        
        $account = Account::where('subdomain' , $subdomain)
            #->with('account')
            ->first();
        
        $alert = new Alert;
        
        if(count($account) === 1){
            $alert->account_id = $account->id;
        }else{
            $alert->account_id = null;
        }
        
        $alert->type = $type;
        $alert->description = $description;
        
        if($needsaction){
            $alert->action_stage = 1;
        }else{
            $alert->action_stage = null;
        }

        $alert->variables = $varibles;
        $alert->save();

        Mail::to("alerts@officesweeet.com")->send(new AlertMail($alert));

        self::Deelevate();
    }
}
