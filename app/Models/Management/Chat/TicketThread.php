<?php

namespace App\Models\Management\Chat;

use App\Helpers\Management\AlertHelper;
use App\Helpers\OS\NotificationHelper;
use App\Helpers\OS\Pusher\PusherHelper;
use App\Models\Management\Account;
use Carbon\Carbon;
use Pusher\Pusher;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class TicketThread extends Model
{
    protected $connection = 'management';

    protected $table = "ticket_threads";

    use SoftDeletes;

    protected $appends = ['subdomain'];

    public function getSubdomainAttribute()
    {
        return $this->account->subdomain;
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Management\Account', 'account_id', 'id');
    }

    public function ticketmessage()
    {
        return $this->hasMany('App\Models\Management\Chat\TicketMessage', 'ticket_threads_id' , 'id');
    }

    public function BroadcastMessage(){

        //$this->load('ticketmessage');

        //$json = json_encode($this);
        //$array = json_decode($json);

        //unset($array->account);
        //foreach($array->ticketmessage as $messagekey => $messagevalue){
        //    unset($array->ticketmessage[$messagekey]->ticketthread);
        //}

        PusherHelper::PushToAll('TicketThread', $this->id, $this->account);

        $llsaccount = Account::where('subdomain', 'lls')->first();
        PusherHelper::PushToAll('TicketThread', $this->id, $llsaccount);

        NotificationHelper::CreateNotificationOnAccount("New Ticket created", "From " . $this->account->subdomain, $this->id, "ticket", null, $llsaccount);

        AlertHelper::NewAlert($this->account->subdomain, "New Ticket Created", $this->subject);
    }

    public function BroadcastStatusChange(){

        $data = array(
                'id' => $this->id,
                'status' => $this->status,
                );
        $llsaccount = Account::where('subdomain', 'lls')->first();

        PusherHelper::PushToAll('TicketStatus', $data, $this->account);
        PusherHelper::PushToAll('TicketStatus', $data, $llsaccount);

    }
}
