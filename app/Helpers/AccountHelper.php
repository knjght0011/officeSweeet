<?php
namespace App\Helpers;

use App\Models\Address;
use App\Models\Client;
use App\Models\ClientContact;
use Cache;
use Carbon\Carbon;

use App\Models\Management\Account;

use Illuminate\Support\Facades\DB;
use Artisan;
use Hash;

use App\Helpers\Management\AlertHelper;
use App\Helpers\Management\SeedHelper;
use App\Helpers\Management\SeedHelper2;

class AccountHelper
{
    const writeusername = "managementwrite";
    const writepassword = "s3T4Yaka8A2ixOw3Ba6E41fOWOlac1";
    const readusername = "management";
    const readpassword = "G1YuqoBEmIlIS2bI8AKaY5Go4e2imu";
    const apikey = "Kwo8gkWaH15NNmSGDerZZKiBHVO4m486";

    public static function ProvisionCreateDB($account){
        $account->installstage = 1;
        $account->save();

        self::Elevate();
        self::CreateDB($account->database, $account->username, $account->password);
        self::Deelevate();

        $account->installstage = 2;
        $account->save();
    }

    public static function ProvisionMigrateDB($account){
        $account->installstage = 3;
        $account->save();

        self::Elevate();
        self::SwitchConnection($account->database, $account->username, $account->password);
        self::MigrateDB();
        self::SwitchConnectionBack();
        self::Deelevate();

        $account->installstage = 4;
        $account->save();
    }

    public static function ProvisionSeedData($account){
        $account->installstage = 5;
        $account->save();

        self::Elevate();
        self::SwitchConnection($account->database, $account->username, $account->password);

        switch ($account->InstallInfo('version')) {
            case "TRIAL":
                self::SeedDataTRIAL($account);
                AlertHelper::NewAlert($account->subdomain, "New TRIAL Client", "New TRIAL Client");
            break;

            case "SOLO":
                self::SeedDataSOLO($account);
                AlertHelper::NewAlert($account->subdomain, "New SOLO Client", "New SOLO Client");
            break;

            case "SMALL BUSINESS":
                self::SeedDataSOLO($account);
                AlertHelper::NewAlert($account->subdomain, "New SMALL BUSINESS Client", "New SMALL BUSINESS Client");
                break;

            case "LARGE BUSINESS":
                self::SeedDataSOLO($account);
                AlertHelper::NewAlert($account->subdomain, "New LARGE BUSINESS Created", "New LARGE BUSINESS Created");
                break;

            case "ENTERPRISE":
                self::SeedDataSOLO($account);
                AlertHelper::NewAlert($account->subdomain, "New ENTERPRISE Client", "New ENTERPRISE Client");
                break;

            default:
                self::SeedDataSOLO($account);
                AlertHelper::NewAlert($account->subdomain, "Client may not have seeded correctly", "Client may not have seeded correctly");
            break;

        }

        self::SwitchConnectionBack();
        self::Deelevate();


        $account->SendWelcomeEmailInstall();

        if($account->InstallInfo('version') === "BROKER"){
            $account->installstage = 7;
        }else{
            $account->installstage = 6;
        }

        $account->save();

    }
    
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
    
    public static function SwitchConnection($database, $username, $password, $port = "3306")
    {
        config(['database.connections.deployment.username' => $username]);
        config(['database.connections.deployment.password' => $password]);
        config(['database.connections.deployment.port' => $port]);
        config(['database.connections.deployment.database' => $database]);
        \DB::connection('deployment')->reconnect();
        Cache::forever('migration-test', 'SwitchConnection-Done');
    }
    
    public static function SwitchConnectionBack()
    {
        $account = Account::where('subdomain', '=', "lls")->first();
        config(['database.connections.deployment.username' => $account->username]);
        config(['database.connections.deployment.password' => $account->password]);
        config(['database.connections.deployment.port' => $account->port]);
        config(['database.connections.deployment.database' => $account->database]);
        \DB::connection('deployment')->reconnect();
    }

    public static function seoUrl($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //remove whitespaces and underscore
        $string = preg_replace("/[\s_]/", "", $string);

        if(strlen($string) > 29){
            $string = substr (  $string , 0 , 29 );
        }

        $account = Account::where('subdomain', '=', $string)->get();
        
        if(count($account) === 0){
            return $string;
        }else{
            return "error";
        }
    }    
    
    public static function GeneratePassword($length = 40)
    {
        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
                  '0123456789';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i=0; $i < $length; $i++){
          $str .= $chars[random_int(0, $max)];
        }
        return $str;
    }
    
    public static function CreateDB($database, $username, $password, $port = "3306")
    {

        $createdbstatement = "CREATE DATABASE " . $database . ";";
        $createuserstatement = "CREATE USER " . $username . " IDENTIFIED BY '" . $password . "';";
        $grantstatement = "GRANT SELECT, INSERT, UPDATE, DELETE, ALTER, CREATE, DROP, INDEX, REFERENCES ON " . $database . ".* TO " . $username . ";";
        
        DB::connection('management')->statement($createdbstatement);
        DB::connection('management')->statement($createuserstatement);
        DB::connection('management')->statement($grantstatement);
        
    }

    public static function MigrateDB()
    {
        Cache::forever('migration-test', 'MigrateDB-Start');
        set_time_limit(6000);
        Artisan::call('migrate', array('--path' => 'database/migrations', '--force' => true, '--database' => 'deployment'));
        Cache::forever('migration-test', 'MigrateDB-Done');
    }

    public static function SeedDataTRIAL(Account $account)
    {

        SeedHelper2::SeedSupportUser();
        SeedHelper2::SeedCheckSettings();
        SeedHelper2::SeedCatagorys();
        SeedHelper2::SeedSchedulerEvents();

        SeedHelper2::SeedSettings($account);

        $branch = $account->InstallInfo('branch');
        if($branch != ""){
            $addressid = SeedHelper2::SeedAddressPassed($branch['number'], $branch['address1'], $branch['address2'], $branch['city'], $branch['region'], $branch['state'], $branch['zip'], $branch['type']);
            $branchid = SeedHelper2::SeedBranchData($branch['number'], $branch['address1'], $branch['address2'], $branch['city'], $branch['region'], $branch['state'], $branch['zip'], "");
            $userid = SeedHelper2::SeedOwnerUser($account, $addressid, $branchid);
        }else{
            $addressid = SeedHelper2::SeedAddress($account);
            $userid = SeedHelper2::SeedOwnerUser($account, $addressid, null);
        }

        //SeedHelper2::SeedWelcomeMessage($userid);
        SeedHelper2::SeedTrialWelcomeNote();
    }

    public static function SeedDataBroker(Account $account)
    {
        SeedHelper2::SeedSupportUser();
        SeedHelper2::SeedCheckSettings();
        SeedHelper2::SeedCatagorys();
        SeedHelper2::SeedSchedulerEvents();

        SeedHelper2::SeedSettings($account);
        
        $branch = $account->InstallInfo('branch');
        if($branch != ""){
            $addressid = SeedHelper2::SeedAddressPassed($branch['number'], $branch['address1'], $branch['address2'], $branch['city'], $branch['region'], $branch['state'], $branch['zip'], $branch['type']);
            $branchid = SeedHelper2::SeedBranchData($branch['number'], $branch['address1'], $branch['address2'], $branch['city'], $branch['region'], $branch['state'], $branch['zip'], "");    
            $userid = SeedHelper2::SeedOwnerUser($account, $addressid, $branchid);    
        }else{
            $userid = SeedHelper2::SeedOwnerUser($account, null, null);
        }
        

        //SeedHelper2::SeedWelcomeMessage($userid);
        SeedHelper2::SeedTrialWelcomeNote();
    }

    public static function SeedDataSOLO(Account $account)
    {

        SeedHelper2::SeedSupportUser();
        SeedHelper2::SeedCheckSettings();
        SeedHelper2::SeedCatagorys();
        SeedHelper2::SeedSchedulerEvents();

        SeedHelper2::SeedSettings($account);
        $addressid = SeedHelper2::SeedAddress($account);
        $userid = SeedHelper2::SeedOwnerUser($account, $addressid, null);

        //SeedHelper2::SeedWelcomeMessage($userid);
        SeedHelper2::SeedSoloWelcomeNote();
    }

    public static function AddToAccountTable($subdomain, $database, $username, $password, $infoarray, $client_id = 0, $port = "3306")
    {
        unset($infoarray['cardNumber']);
        unset($infoarray['cardExpiryMonth']);
        unset($infoarray['cardExpiryYear']);
        unset($infoarray['cardCVC']);

        $account = New Account;
        $account->subdomain = $subdomain;
        $account->database = "os_" . $database;
        $account->port = $port;
        $account->username = "os_" . $username;
        $account->password = $password;
        $account->client_id = $client_id;
        $account->active = $infoarray['active'];
        $account->licensedusers = $infoarray['number_of_users'];
        $account->plan_name = $infoarray['plan_name'];
        $account->subscription_id = $infoarray['subscription_id'];
        $account->transaction_id = $infoarray['transaction_id'];
        $account->installinfo = $infoarray;
        $account->token = $infoarray['token'];

        if($infoarray['version'] === "BROKER-SIGNUP"){
            $account->broker_id = $infoarray['broker_id'];
            $account->broker_user_id = $infoarray['broker_user_id'];
        }

        $account->save();

        if($infoarray['version'] != "BROKER"){
            if($account->client_id === 0){
                self::AddToLLSClientTable($account);
            }
        }

        return $account;
    }


    public static function AddToLLSClientTable(Account $account){

        self::SwitchConnectionBack();

        $clientaddress = new Address;
        $clientaddress->setConnection('deployment');
        $clientaddress->number = "";
        $clientaddress->address1 = $account->InstallInfo('address1');
        $clientaddress->address2 ="";
        $clientaddress->city = $account->InstallInfo('city');
        $clientaddress->region = "";
        $clientaddress->state = $account->InstallInfo('state');
        $clientaddress->zip = $account->InstallInfo('zip');
        $clientaddress->type = "";
        $clientaddress->save();

        $client = new Client;
        $client->setConnection('deployment');
        $client->name = $account->InstallInfo('company');
        $client->category = $account->InstallInfo('version');
        $client->phonenumber = $account->InstallInfo('telephone');
        $client->address_id = $clientaddress->id;
        $client->primarycontact_id = null;
        $client->date_of_introduction = Carbon::now();
        $client->referral_source = $account->InstallInfo('referred_by') . " " . $account->InstallInfo('referred_name');
        $client->comments = "Type of Business" . $account->InstallInfo('type_business');
        $client->deleted_at = Carbon::now();
        $client->save();

        $contact = new ClientContact;
        $contact->setConnection('deployment');
        $contact->firstname = $account->InstallInfo('firstname');
        $contact->middlename = "";
        $contact->lastname = $account->InstallInfo('lastname');
        $contact->contacttype = $account->InstallInfo('businessrole');
        $contact->address_id = null;
        $contact->ssn = "";
        $contact->driverslicense = "";
        $contact->email = $account->InstallInfo('email');
        $contact->client_id = $client->id;
        $contact->homenumber = $account->InstallInfo('telephone');
        $contact->primaryphonenumber = 3;
        $contact->contacttype = "Signed Up";
        $contact->save();

        $client->primarycontact_id = $contact->id;
        $client->save();

        $account->client_id = $client->id;
        $account->save();

    }
    
    public static function UpdateNumberOfUsers($subdomain, $users){
        self::Elevate();
        
        $account = Account::where('subdomain', $subdomain)->first();
        if(count($account) === 1){
            $account->licensedusers = $users;
            $account->save();
            
            return true;
        }else{
            return false;
        }

    }

}
