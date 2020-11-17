<?php
namespace App\Helpers\OS;

use App\Helpers\OS\Pusher\PusherHelper;
use App\Models\OS\Chat\ChatNotification;

class NotificationHelper
{

    public static function CreateNotification($title, $text, $link, $code, $user_id){

        $note = new ChatNotification;
        $note->title = $title;
        $note->text = $text;
        $note->link = $link;
        $note->code = $code;
        $note->user_id = $user_id;
        $note->save();

        if($note->user_id === null){
            PusherHelper::PushToAll("Notification", $note->id, app()->make('account'));
        }else{
            PusherHelper::PushToUser(app()->make('account')->subdomain, $note->user->email, "Notification", $note->id);
        }

    }

    public static function CreateNotificationOnAccount($title, $text, $link, $code, $user_id, $account){

        config(['database.connections.deployment.username' => $account->username]);
        config(['database.connections.deployment.password' => $account->password]);
        config(['database.connections.deployment.port' => $account->port]);
        config(['database.connections.deployment.database' => $account->database]);
        \DB::connection('deployment')->reconnect();

        $note = new ChatNotification;
        $note->setConnection('deployment');
        $note->title = $title;
        $note->text = $text;
        $note->link = $link;
        $note->code = $code;
        $note->user_id = $user_id;
        $note->save();

        if($note->user_id === null){
            PusherHelper::PushToAll("Notification", $note->id, $account);
        }else{
            PusherHelper::PushToUser($account->subdomain, $note->user->email, "Notification", $note->id);
        }

    }

}