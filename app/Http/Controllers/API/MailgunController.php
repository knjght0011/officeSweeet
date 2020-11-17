<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Management\Account;
use App\Models\OS\Email\MailGunEvent;
use Illuminate\Support\Facades\Input;

class MailgunController extends Controller {

    const apikey = "key-6c0877e58c956c264928ba795e6de19d";

    private static function hashcheck($timestamp, $token, $signature)
    {
        $hash = hash_hmac( "sha256" , $timestamp . $token , self::apikey  );

        if($hash === $signature){
            return true;
        }else{
            return false;
        }
    }

    private static function SwitchConnection($database, $username, $password, $port = "3306")
    {
        config(['database.connections.deployment.username' => $username]);
        config(['database.connections.deployment.password' => $password]);
        config(['database.connections.deployment.port' => $port]);
        config(['database.connections.deployment.database' => $database]);
        \DB::connection('deployment')->reconnect();
    }

    public function Update(){

        try{
            $signature = Input::get('signature');
            $eventdata = Input::get('event-data');

            if($this->hashcheck($signature['timestamp'],$signature['token'],$signature['signature'])){

                if(isset($eventdata['user-variables']['subdomain'])){
                    $account = Account::where('subdomain', $eventdata['user-variables']['subdomain'])->first();
                    if(count($account) != 1){
                        $account = Account::where('subdomain', 'lls')->first();
                    }
                }else{
                    $account = Account::where('subdomain', 'lls')->first();
                }

                if($account->subdomain != "local"){
                    $this->SwitchConnection($account->database, $account->username, $account->password, $account->port);

                    $mailgun = new MailGunEvent;
                    $mailgun->setConnection('deployment');
                    $mailgun->data = $eventdata;

                    if(isset($eventdata['user-variables']['message_type'])){
                        $mailgun->messagetype = $eventdata['user-variables']['message_type'];
                    }else{
                        $mailgun->messagetype = "none";
                    }

                    if(isset($eventdata['event'])){
                        $mailgun->event = $eventdata['event'];
                    }else{
                        $mailgun->event = "none";
                    }

                    if(isset($eventdata['user-variables']['message_id'])){
                        if($eventdata['user-variables']['message_id'] === 0 or $eventdata['user-variables']['message_id'] === "0"){
                            $mailgun->emails_id = null;
                        }else{
                            $mailgun->emails_id = $eventdata['user-variables']['message_id'];
                        }
                    }else{
                        $mailgun->emails_id = null;
                    }

                    $mailgun->save();

                    return "ok";
                }else{
                    return "ok";
                }
            }else{
                return "invalid";
            }
        }catch (\Throwable $t) {
            app('sentry')->captureException($t);
            return "Error";
        }
    }
}

