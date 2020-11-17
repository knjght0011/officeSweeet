<?php
namespace App\Helpers\OS\Home;


use App\Helpers\OS\Financial\PayrollHelper;
use App\Helpers\OS\Users\UserHelper;
use App\Models\Check;
use App\Models\Client;
use App\Models\Setting;

class HomeHelper
{

    public static function GetClients(){
        return $clients = Client::with('primarycontact')->withTrashed()->get();
    }

    public static function Recevables($clients){

        $recevables = 0.00;

        foreach($clients as $client){
            if($client->getStatus() === "Client"){
                $recevables += $client->getBalence(false);
            }
        }

        return number_format($recevables , 2, '.', '');
    }



    public static function OpenQuoteValue($prospects){

        $quotevalue = 0.00;

        foreach($prospects as $client){
            if($client->getStatus() === "Prospect"){
                $quotevalue += $client->getOpenQuoteValue(false);
            }
        }

        return number_format($quotevalue , 2, '.', '');

    }

    public static function Payables(){

        $checks = Check::where('printqueue', '1')->get();

        $unprintedchecks = 0.00;
        foreach($checks as $check){
            $unprintedchecks = $unprintedchecks + $check->amount;
        }

        return number_format($unprintedchecks , 2, '.', '');

    }

    public static function NextPayrollEndDate() {

        $freqencysetting = Setting::where('name' , 'Payroll-Frequency')->first();
        $optionsetting = Setting::where('name' , 'Payroll-Option')->first();
        $firstsetting = Setting::where('name' , 'Payroll-First')->first();

        if($freqencysetting != null){
            if($firstsetting != null){
                return PayrollHelper::GetNextPayPeriod($freqencysetting->value, $optionsetting->value, $firstsetting->value)->end->toDateString();
            }else{
                return null;
            }
        }else{
            return null;
        }

    }

}