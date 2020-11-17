<?php

namespace App\Http\Controllers\OS;

use App\Helpers\OS\NotificationHelper;
use App\Helpers\OS\Users\UserHelper;

use App\Http\Controllers\Controller;
use App\Models\OS\Chat\ChatNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use mysql_xdevapi\Exception;

use Mail;


class NotificationController extends Controller
{

    public function fetchNotifications()
    {
       return ChatNotification::where('user_id', '=', Auth::user()->id)->orWhere('user_id', '=', null)->get();
    }

    public function fetchNotification($s,$id){

        $message = ChatNotification::where('id', $id)->first();
        if(count($message) === 1){
            return $message;
        }else{
            return "notfound";
        }

    }

    public function testcreate(){

        NotificationHelper::CreateNotification('test', 'test', '', null, Auth::user()->id);
        return "ok";

    }

    public function MarkRead(){

        $id = Input::get('id');

        if($id === 'all'){
            $notes = ChatNotification::where('user_id', '=', Auth::user()->id)->get();
            foreach($notes as $note){
                $note->MarkReadByMe();
            }
            $notes = ChatNotification::where('user_id', '=', null)->get();
            foreach($notes as $note){
                $note->MarkReadByMe();
            }

            return "alldone";
        }else{
            $note = ChatNotification::where('id', '=', $id)->first();

            if(count($note) === 1){

                $note->MarkReadByMe();

                return "done";

            }else{
                return "notfound";
            }
        }
    }

    public function New(){

        $data = array(
            'subject' => Input::get('subject'),
            'message' => Input::get('message'),
            'recipients' => Input::get('recipients'),
        );

        $user = UserHelper::GetOneUserByID($data['recipients']);

        if(count($user) === 1){
            NotificationHelper::CreateNotification($data['subject'], $data['message'], '', null, $user->id);
            return ['status' => 'OK'];
        }else{
            return ['status' => 'USERNOTFOUND'];
        }
    }

    public function NewAndEmail(){
        $data = array(
            'subject' => Input::get('subject'),
            'message' => Input::get('message'),
            'recipients' => Input::get('recipients'),
        );

        $user = UserHelper::GetOneUserByID($data['recipients']);
        NotificationHelper::CreateNotification($data['subject'], $data['message'], '', null, $user->id);

        Mail::raw($data['message'], function ($message) {
            $data = array(
                'subject' => Input::get('subject'),
                'message' => Input::get('message'),
                'recipients' => Input::get('recipients'),
            );
            $user = UserHelper::GetOneUserByID($data['recipients']);
            $message->to($user->email);
            $message->from('NoReply@officesweeet.com');
            $message->subject($data['subject']);
        });


        return ['status' => 'OK'];
    }

    public function PatList(){
        Mail::raw([], function ($message) {
            $data = array(
                'message' => Input::get('message'),
                'email' => Input::get('email'),
            );
            $message->to($data['email']);
            $message->from('NoReply@officesweeet.com');
            $message->setBody($data['message'], 'text/html');
            $message->subject('Patient Scheduling List');
        });

        return ['status' => 'OK'];
    }
}
