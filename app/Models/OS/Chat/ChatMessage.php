<?php

namespace App\Models\OS\Chat;

use App\Helpers\OS\Pusher\PusherHelper;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Pusher\Pusher;

class ChatMessage extends Model
{
    use SoftDeletes;

    protected $connection = 'subdomain';

    protected $table = "chat_messages";

    protected $fillable = array('message','chat_threads_id');

    protected $casts = [
        'readby' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function chatthread()
    {
        return $this->belongsTo('App\Models\OS\Chat\ChatThread', 'chat_threads_id', 'id');
    }

    public function chatparticipants()
    {
        return $this->belongsToMany('App\Models\OS\Chat\ChatParticipants');
    }

    public function BroadcastMessage(){

        $this->load('user');
        $this->load('chatthread');
        $this->load('chatthread.chatparticipants');

        $chatthread = $this->chatthread;
        $chatparticipants = $chatthread->chatparticipants;

        foreach($chatparticipants as $chatparticipant){
            PusherHelper::PushToUser(app()->make('account')->subdomain, $chatparticipant->user->email, "ChatMessage", $this->id);
        }

    }

    public function isReadByMe()
    {
        return $this->isReadBy(Auth::user()->id);
    }

    public function isReadBy($id)
    {
        if(isset($this->readby[$id])){
            if($this->readby[$id] === 1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function MarkReadByMe()
    {
        return $this->MarkReadBy(Auth::user()->id);
    }

    public function MarkReadBy($id)
    {
        $array = $this->readby;
        $array[$id] = 1;
        $this->readby = $array;
        $this->save();
    }

}
