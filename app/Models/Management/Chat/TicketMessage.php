<?php

namespace App\Models\Management\Chat;

use App\Helpers\Management\AlertHelper;
use App\Helpers\OS\NotificationHelper;
use App\Helpers\OS\Pusher\PusherHelper;
use App\Models\Management\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Pusher\Pusher;

class TicketMessage extends Model
{

    protected $connection = 'management';

    protected $table = "ticket_messages";

    use SoftDeletes;

    protected $fillable = array('message','ticket_threads_id');

    protected $casts = [
        'readby' => 'array',
    ];

    protected $appends = ['user'];

    public function getUserAttribute()
    {
        if($this->user_id === null){
            return "OfficeSweeet Support";
        }else{

            if(config('database.connections.deployment.database') != $this->ticketthread->account->database){
                config(['database.connections.deployment.username' => $this->ticketthread->account->username]);
                config(['database.connections.deployment.password' => $this->ticketthread->account->password]);
                config(['database.connections.deployment.port' => $this->ticketthread->account->port]);
                config(['database.connections.deployment.database' => $this->ticketthread->account->database]);
                \DB::connection('deployment')->reconnect();
            }

            $u = new User;
            $u->setConnection('deployment');
            $user = $u->where('id', $this->user_id)->withTrashed()->first();
            if(isset($user->name)){
                return $user->name;
            }else{
                return "UNKNOWN USER:";
            }
        }
    }

    public function ticketthread()
    {
        return $this->belongsTo('App\Models\Management\Chat\TicketThread', 'ticket_threads_id', 'id');
    }

    public function BroadcastMessage(){

        //$this->load('ticketthread');

        //$json = json_encode($this);
        //$array = json_decode($json);

        //unset($array->ticketthread);

        PusherHelper::PushToAll('TicketMessage', $this->id, $this->ticketthread->account);

        $llsaccount = Account::where('subdomain', 'lls')->first();
        PusherHelper::PushToAll('TicketMessage', $this->id, $llsaccount);

        if($this->user_id === null){
            //create notification in their system
            NotificationHelper::CreateNotificationOnAccount("New Ticket Message", "New message from Officesweeet Support", $this->ticket_threads_id, "ticket", null, $this->ticketthread->account);
        }else{
            //create notification in LLS
            //$account = Account::where('subdomain', 'lls')->first();
            NotificationHelper::CreateNotificationOnAccount("New Ticket Message", "New message from " . $this->user, $this->ticket_threads_id, "ticket", null, $llsaccount);
            AlertHelper::NewAlert($this->ticketthread->account->subdomain, "New Ticket Reply", "From: " . $this->user . "<br>Subdomain: " . $this->ticketthread->account->subdomain . "<br>Message: " . $this->message);
        }
    }

}
