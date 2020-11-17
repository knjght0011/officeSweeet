<?php

namespace App\Http\Controllers\OS\API;

use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Management\SupportVideos;
use App\Models\OS\Chat\ChatNotification;
use Carbon\Carbon;

use Illuminate\Support\Facades\Response;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


use App\Models\OS\Chat\ChatMessage;
use App\Models\OS\Chat\ChatThread;
use App\Models\OS\Chat\ChatParticipants;
use App\Models\User;


class APIController extends Controller
{
    private function Auth($input){

        $userdata = array(
            'email'     => trim ($input['email']),
            'password'  => $input['password'],
            'canlogin'  => 1
        );

        return Auth::attempt($userdata);
        /*
        if(isset($input['email'])){
            if(isset($input['password'])){
                $hash = Hash::make($input['password']);
                $user = User::where('email', $input['email'])
                            ->where('password', $hash)
                            ->where('canlogin', "1")
                            ->first();

                if(count($user) === 1){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
        */

    }

    public function MyPermissions(){

        if($this->Auth(Input::all())){
            $user = UserHelper::GetOneUserByEmail(Input::get('email'));

            return $user->permissions;
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function MyNotifications(){

        if($this->Auth(Input::all())){
            return ChatNotification::where('user_id', '=', Auth::user()->id)->orWhere('user_id', '=', null)->get();
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function GetNotification(){
        if($this->Auth(Input::all())){
            $note = ChatNotification::where('id', Input::get('id'))->first();
            if(count($note) === 1){
                return $note;
            }else{
                return Response::make(view('errors.404'), 404);
            }
        }else{
            return Response::make(view('errors.404'), 404);
        }

    }

    public function MyNotificationsSince(){

        if($this->Auth(Input::all())){
            return ChatNotification::where('user_id', '=', Auth::user()->id)
                                    ->orWhere('user_id', '=', null)
                                    ->whereDate('created_at', '>', Carbon::parse(Input::get('date')))
                                    ->get();
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function MarkNotificationRead(){

        if($this->Auth(Input::all())){
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
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function SupportVideos(){
        return SupportVideos::all();
    }
}
