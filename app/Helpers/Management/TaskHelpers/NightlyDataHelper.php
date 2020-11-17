<?php
namespace App\Helpers\Management\TaskHelpers;

#use Barryvdh\DomPDF\Facade as PDF;
#use Illuminate\Support\Facades\Validator;
#use \App\Providers\EventLog;
#use App\Models\ProductLibrary;
#use App\Models\Receipt;
#use App\Helpers\OS\EventHelper;
use App\Helpers\OS\QuoteHelper;

use App\Models\Address;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Management\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\OS\RecurringInvoice;
        
class NightlyDataHelper
{
    public static function SwitchConnection($database, $username, $password, $port = "3306")
    {
        config(['database.connections.subdomain.username' => $username]);
        config(['database.connections.subdomain.password' => $password]);
        config(['database.connections.subdomain.port' => $port]);
        config(['database.connections.subdomain.database' => $database]);
        \DB::connection('subdomain')->reconnect();
    }

    public static function Run($account)
    {

        if($account->installstage === 7){
            self::SwitchConnection($account->database, $account->username, $account->password, $account->port);

            $branches = Branch::all();
            $users = User::where('os_support_permission', '=', 0)->get();

            $lls = Account::where('subdomain', '=', 'lls')->first();

            self::SwitchConnection($lls->database, $lls->username, $lls->password, $lls->port);

            $client = Client::where('id', '=', $account->client_id)->withTrashed()->first();

            echo($client->getName());

            if(count($client) === 1){
                return self::DoIt($branches, $users, $client);
            }else{
                return "Unable to find LLS client";
            }
        }else{
            return "System hasn't completed setup yet";
        }
    }

    private static function DoIt($branches, $users, $client){

        if(count($branches) === 1){
            if($client->address->address1 === "Unknown"){
                $id = self::NewAddress($branches->first());
                $client->address_id = $id;
                $client->save();
            }
        }else{
            if(count($branches) > 1){
                foreach($branches as $branch){
                    if($branch->default === "1"){
                        if($client->address->address1 != $branch->address1){
                            $id = self::NewAddress($branch);
                            $client->address_id = $id;
                            $client->save();
                        }
                    }
                }
            }
        }

        $lastlogin = self::LastLogin($users);

        if($lastlogin != null) {
            if ($client->deleted_at === null) {
                if ($lastlogin->diffInDays(Carbon::now()) > 90) {
                    //$client->deleted_at = Carbon::now();
                    //$client->save();
                }
            } else {
                if ($lastlogin->diffInDays(Carbon::now()) < 8) {
                    $client->deleted_at = null;
                    $client->save();
                }
            }
        }else{
            $client->deleted_at = Carbon::now();
        }

        return "Done";

    }

    private static function LastLogin($users){
        $lastlogin = null;
        foreach($users as $user) {
            if ($user->last_login != null){
                $userLL = Carbon::parse($user->last_login);
                if ($lastlogin === null) {
                    $lastlogin = $userLL;
                } else {
                    if ($userLL->gt($lastlogin)) {
                        $lastlogin = $userLL;
                    }
                }
            }
        }

        return $lastlogin;
    }

    private static function NewAddress($branch){
        $address = new Address();
        $address->number = $branch->number;
        $address->address1 = $branch->address1;
        $address->address2 = $branch->address2;
        $address->city = $branch->city;
        $address->state = $branch->state;
        $address->region = $branch->region;
        $address->zip = $branch->zip;
        $address->save();

        return $address->id;
    }
}