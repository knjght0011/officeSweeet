<?php
namespace App\Helpers\OS\Pusher;

use Pusher\Pusher;
use App\Models\User;

class PusherHelper
{
    public static function PushToAll($type, $data, $account){

        config(['database.connections.deployment.username' => $account->username]);
        config(['database.connections.deployment.password' => $account->password]);
        config(['database.connections.deployment.port' => $account->port]);
        config(['database.connections.deployment.database' => $account->database]);
        \DB::connection('deployment')->reconnect();

        $u = new User;
        $u->setConnection('deployment');
        $users = $u->where('canlogin', 1)->get();

        foreach($users as $user){
            self::PushToUser($account->subdomain, $user->email, $type, $data);
        }

    }

    public static function PushToUser($subdomain, $email, $type, $data){

        $push = array(
            'type' => $type,
            'data' => json_encode($data),
            );


        $size =

        $pusher = new Pusher( "433d9d443bf08a679fc9", 'f95633e24af26099a751', '538864', array( 'cluster' => 'mt1', 'encrypted' => true ) );

        $pusher->trigger( 'OfficeSweeetMessenger', $subdomain . "-" . $email, $push );

    }


}