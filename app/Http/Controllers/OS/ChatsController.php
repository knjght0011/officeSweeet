<?php

namespace App\Http\Controllers\OS;

use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


use App\Models\OS\Chat\ChatMessage;
use App\Models\OS\Chat\ChatThread;
use App\Models\OS\Chat\ChatParticipants;
use App\Models\User;


class ChatsController extends Controller
{
    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('OS.Chat.chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
        return ChatMessage::with('user')->get();
    }

    public function fetchMessage($s,$id)
    {
        $message = ChatMessage::where('id', $id)->with('user')->first();
        if(count($message) === 1){
            return $message;
        }else{
            return "notfound";
        }
    }

    public function GetThreadsData(){

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

        return $threads;

    }

    public function fetchThreads()
    {
        return $this->GetThreadsData();
    }

    public function fetchThreadsApp($subdomain, $email, $password){

        $userdata = array(
            'email'     => trim ($email),
            'password'  => trim ($password),
            'canlogin'  => 1
        );

        if (Auth::attempt($userdata)){

            $data = $this->GetThreadsData();

            //Session::flush();
            //Auth::logout();

            return json_encode($data);

        }else{
            return "Unauthorised";
        }

    }

    public function fetchThread($s,$id)
    {

        $thread = ChatThread::where('id', '=', $id)
            ->with('chatmessage')
            ->with('chatmessage.user')
            ->with('chatparticipants')
            ->with('chatparticipants.user')
            ->first();

        return $thread;
    }


    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        if($request->input('thread') === "0"){
            $thread =  new ChatThread;
            $thread->save();

            $partme = new ChatParticipants;
            $partme->user_id = Auth::user()->id;
            $partme->chat_threads_id = $thread->id;
            $partme->save();

            $partyou = new ChatParticipants;
            $partyou->user_id = $request->input('user');
            $partyou->chat_threads_id = $thread->id;
            $partyou->save();

            $message = $user->chatmessage()->create([
                'message' => $request->input('message'),
                'chat_threads_id' =>  $thread->id,
            ]);

            $code = ['status' => 'Message Sent!', 'threadid' => $thread->id, 'name' => '', 'message' => $message->message, 'created_at' => $message->created_at->toDateTimeString()];
        }else{
            $message = $user->chatmessage()->create([
                'message' => $request->input('message'),
                'chat_threads_id' =>  $request->input('thread'),
            ]);


            $code = ['status' => 'Message Sent!'];
        }

        //$message->load('user');
        //$message->load('chatthread');
        //$message->load('chatthread.chatparticipants');

        $message->MarkReadByMe();

        $message->BroadcastMessage();

        return $code;
    }

    public function sendMessageApp()
    {
        $data = array(
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'chat_thread_id' => Input::get('chat_thread_id'),
            'message' => Input::get('message'),
        );

        $userdata = array(
            'email'     => trim ($data['email']),
            'password'  => trim ($data['password']),
            'canlogin'  => 1
        );

        if (Auth::attempt($userdata)){

            $message = Auth::user()->chatmessage()->create([
                'message' => $data['message'],
                'chat_threads_id' =>  $data['chat_thread_id'],
            ]);

            $message->MarkReadByMe();
            $message->BroadcastMessage();

            return ['status' => 'Message Sent!'];

        }else{
            return "Unauthorised";
        }


    }
    public function NewThread(Request $request){

        $thread =  new ChatThread;
        $thread->subject = $request->input('name');
        $thread->save();

        $partme = new ChatParticipants;
        $partme->user_id = Auth::user()->id;
        $partme->chat_threads_id = $thread->id;
        $partme->save();

        foreach($request->input('users') as $userid){
            $part = new ChatParticipants;
            $part->user_id = $userid;
            $part->chat_threads_id = $thread->id;
            $part->save();
        }

        $thread->BroadcastMessage();

        return ['status' => 'OK', 'thread' => $thread->id];
    }


    public function MarkReadThread(){

        $thread = ChatThread::where('id', '=', Input::get('thread'))->first();

        if(count($thread) === 1){

            foreach ($thread->chatmessage as $chatmessage){
                $chatmessage->MarkReadByMe();
                $chatmessage->save();
            }

            return ['status' => 'OK'];

        }else{
            return ['status' => 'notfound'];
        }
    }


    public function UpdateThread(){

        $thread = ChatThread::where('id', '=', Input::get('thread'))->first();

        if(count($thread) === 1){

            $thread->subject = Input::get('name');

            foreach($thread->chatparticipants as $chatparticipant){

                $chatparticipant->delete();

            }

            foreach (Input::get('users') as $userid){

                $chatparticipant = ChatParticipants::withTrashed()
                                                    ->where('user_id', $userid)
                                                    ->where('chat_threads_id', $thread->id)
                                                    ->first();

                if(count($chatparticipant) === 1){
                    $chatparticipant->restore();
                }else{
                    $newparticipant = new ChatParticipants;
                    $newparticipant->user_id = $userid;
                    $newparticipant->chat_threads_id = $thread->id;
                    $newparticipant->save();

                }
            }

            $chatparticipant = ChatParticipants::withTrashed()
                ->where('user_id', Auth::user()->id)
                ->where('chat_threads_id', $thread->id)
                ->first();

            if(count($chatparticipant) === 1){
                $chatparticipant->restore();
            }

            $thread->save();
            $thread->BroadcastMessage();
            $thread->BroadcastMessageTrashed();

            return ['status' => 'OK'];

        }else{
            return ['status' => 'notfound'];
        }
    }

    /* No longer needed with new UI
    public function AddUserThread(){

        $thread = ChatThread::where('id', '=', Input::get('thread'))->first();
        $user = UserHelper::GetOneUserByID(Input::get('user'));

        if(count($thread) === 1 & count($user) === 1){

            foreach ($thread->chatparticipants as $chatparticipant){
                if($chatparticipant->user->id === $user->id){
                    return ['status' => 'allreadyinthread'];
                }
            }

            $part = new ChatParticipants;
            $part->user_id = $user->id;
            $part->chat_threads_id = $thread->id;
            $part->save();

            $thread->BroadcastMessage();

            return ['status' => 'OK', 'userid' => $user->id];

        }else{
            return ['status' => 'notfound'];
        }
    }

    public function RemoveUserThread(){

        $thread = ChatThread::where('id', '=', Input::get('thread'))->first();
        $user = UserHelper::GetOneUserByID(Input::get('user'));

        if(count($thread) === 1){

            $chatpart = $thread->chatparticipants->where('user_id', '=', Input::get('user'))->first();

            if(count($chatpart) === 1){

                $chatpart->delete();

                $thread->BroadcastMessage();
                if(count($user) === 1){
                    $thread->BroadcastMessageToUser($user);
                }

                return ['status' => 'OK', 'userid' => $chatpart->user_id];
            }else{
                return ['status' => 'notfound'];
            }

        }else{
            return ['status' => 'notfound'];
        }
    }
     public function RenameThread(){

         $thread = ChatThread::where('id', '=', Input::get('thread'))->first();

         if(count($thread) === 1){

             $thread->subject = Input::get('subject');
             $thread->save();
             $thread->BroadcastMessage();

             return ['status' => 'OK'];

         }else{
             return ['status' => 'notfound'];
         }
     }
    */
}
