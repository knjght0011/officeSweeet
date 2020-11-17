<?php

namespace App\Models\OS\Chat;

use App\Helpers\OS\Pusher\PusherHelper;
use Pusher\Pusher;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatNotification extends Model
{

    protected $casts = [
        'readby' => 'array',
    ];

    protected $appends = ['read_by_me', 'short_text'];

    public function getShortTextAttribute(){

        if(strlen($this->text) > 40){
            return strip_tags(substr($this->text, 0,37)) . "...";
        }else{
            return strip_tags($this->text);
        }
    }

    public function getReadByMeAttribute()
    {
        if($this->isReadByMe()){
            return '';
        }else{
            return 'unread';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    //public function BroadcastMessage(){

    //    $this->BroadcastMessageSubdomain(app()->make('account')->subdomain, User::all());
    //}

    //public function BroadcastMessageSubdomain($subdomain, $users){

        //$pusher = new Pusher( "433d9d443bf08a679fc9", 'f95633e24af26099a751', '538864', array( 'cluster' => 'mt1', 'encrypted' => true ) );

     //   if($this->user_id === null){
     //       foreach ($users as $user){
                //$pusher->trigger( 'OfficeSweeetMessenger', $subdomain.'-'.$user->email.'-notification' , $this );
     //       }
     //   }else{
     //       PusherHelper::PushToUser($subdomain, )
            //$pusher->trigger( 'OfficeSweeetMessenger', $subdomain.'-'.$this->email.'-notification' , $this );
      //  }
    //}

    public function MarkReadByMe()
    {
        if (Auth::check()) {
            return $this->MarkReadBy(Auth::user()->id);
        }
    }

    public function MarkReadBy($userid)
    {
        $array = $this->readby;
        $array[$userid] = 1;
        $this->readby = $array;
        $this->save();
    }

    public function isReadByMe()
    {
        if (Auth::check()) {
            return $this->isReadBy(Auth::user()->id);
        }else{
            return false;
        }
    }

    public function isReadBy($userid)
    {
        if(isset($this->readby[$userid])){
            if($this->readby[$userid] === 1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
