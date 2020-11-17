<?php

namespace App\Models\OS\Chat;

use App\Helpers\OS\Pusher\PusherHelper;
use Carbon\Carbon;
use Pusher\Pusher;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class ChatThread extends Model
{
    protected $connection = 'subdomain';

    protected $table = "chat_threads";

    use SoftDeletes;

    protected $appends = ['most_recent_message_date'];

    public function getMostRecentMessageDateAttribute()
    {
        $message = $this->chatmessage->sortByDesc('created_at')->first();
        if(count($message) === 1){
            return $message->created_at->toDateTimeString();
        }else{
            return $this->created_at->toDateTimeString();
        }
    }

    public function chatmessage()
    {
        return $this->hasMany('App\Models\OS\Chat\ChatMessage' , 'chat_threads_id' , 'id');
    }

    public function chatparticipants()
    {
        return $this->hasMany('App\Models\OS\Chat\ChatParticipants', 'chat_threads_id', 'id');
    }

    public function chatparticipantsTrashed()
    {
        return $this->hasMany('App\Models\OS\Chat\ChatParticipants', 'chat_threads_id', 'id')->onlyTrashed();
    }

    public function BroadcastMessage(){

        $this->load('chatmessage');
        $this->load('chatmessage.user');
        $this->load('chatparticipants');
        $this->load('chatparticipants.user');

        foreach($this->chatparticipants as $chatparticipant){
            PusherHelper::PushToUser(app()->make('account')->subdomain, $chatparticipant->user->email, "ChatThread", $this->id);
        }
    }

    public function BroadcastMessageTrashed(){

        $this->load('chatmessage');
        $this->load('chatmessage.user');
        $this->load('chatparticipantsTrashed');
        $this->load('chatparticipantsTrashed.user');

        foreach($this->chatparticipantsTrashed as $chatparticipant){
            PusherHelper::PushToUser(app()->make('account')->subdomain, $chatparticipant->user->email, "ChatThreadDelete", $this->id);
        }
    }

    public function BroadcastMessageToUser($user){

        $this->load('chatparticipants');
        $this->load('chatparticipants.user');

        PusherHelper::PushToUser(app()->make('account')->subdomain, $user->email, "ChatThread", $this);

    }
}
