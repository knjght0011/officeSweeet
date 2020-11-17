<?php

namespace App\Http\Controllers\OS\API;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;


use App\Models\OS\Chat\ChatMessage;
use App\Models\OS\Chat\ChatThread;
use App\Models\OS\Chat\ChatParticipants;

class ChatAPIController extends Controller
{
    private function Auth($input){

        $userdata = array(
            'email'     => trim ($input['email']),
            'password'  => $input['password'],
            'canlogin'  => 1
        );

        return Auth::attempt($userdata);

    }

    public function ChatThread(){

        if($this->Auth(Input::all())){

            $thread = ChatThread::where('id', Input::get('id'))
                ->with('chatmessage')
                ->with('chatmessage.user')
                ->with('chatparticipants')
                ->with('chatparticipants.user')
                ->first();

            if(count($thread) === 1){

                $participantstring = "";

                foreach ($thread->chatparticipants as $participant){
                    if($participant->user_id != Auth::user()->id){
                        $participantstring = $participantstring . $participant->user->name . ", ";
                    }
                }

                $thread->participantstring = substr($participantstring, 0, -2);

                return $thread;
            }else{
                return Response::make(view('errors.404'), 404);
            }
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function ChatThreads(){

        if($this->Auth(Input::all())){

            $threadtemp = DB::table('chat_participants')
                ->join('chat_threads', 'chat_threads.id', '=', 'chat_participants.chat_threads_id')
                ->where('chat_participants.user_id', '=',  Auth::user()->id)
                ->where('chat_participants.deleted_at', '=',  null)
                ->get();

            $ids = array();
            foreach($threadtemp as $t){
                $ids[] = $t->id;
            }

            $threads = ChatThread::whereIn('id', $ids)
                ->with('chatmessage')
                ->with('chatmessage.user')
                ->with('chatparticipants')
                ->with('chatparticipants.user')
                ->get();

            $threads->sortByDesc('most_recent_message_date');

            foreach ($threads as $thread){

                $participantstring = "";

                foreach ($thread->chatparticipants as $participant){
                    if($participant->user_id != Auth::user()->id){
                        $participantstring = $participantstring . $participant->user->name . ", ";
                    }
                }

                $thread->participantstring = substr($participantstring, 0, -2);

            }

            return $threads;

        }else{
            return Response::make(view('errors.404'), 404);
        }

    }

    public function CreateThread(){

        if($this->Auth(Input::all())){

            $thread =  new ChatThread;
            $thread->subject = Input::get('name');
            $thread->save();

            $partme = new ChatParticipants;
            $partme->user_id = Auth::user()->id;
            $partme->chat_threads_id = $thread->id;
            $partme->save();

            foreach(Input::get('users') as $userid){
                $part = new ChatParticipants;
                $part->user_id = $userid;
                $part->chat_threads_id = $thread->id;
                $part->save();
            }

            $thread->BroadcastMessage();

        }else{
            return Response::make(view('errors.404'), 404);
        }


    }

    public function ChatMessage(){

        if($this->Auth(Input::all())){

            $message = ChatMessage::where('id', Input::get('id'))
                ->with('user')
                ->first();

            if(count($message) === 1){

                if($message->user_id === Auth::user()->id){
                    $message->is_me = 1;
                }else{
                    $message->is_me = 0;
                }

                return $message;
            }else{
                return Response::make(view('errors.404'), 404);
            }
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function MarkThreadRead(){

        if($this->Auth(Input::all())){

            $thread = ChatThread::where('id', Input::get('id'))
                ->with('chatmessage')
                ->first();

            if(count($thread) === 1){

                foreach ($thread->chatmessage as $chatmessage){
                    $chatmessage->MarkReadByMe();
                    $chatmessage->save();
                }

                return "OK";
            }else{
                return Response::make(view('errors.404'), 404);
            }
        }else{
            return Response::make(view('errors.404'), 404);
        }

    }


}
