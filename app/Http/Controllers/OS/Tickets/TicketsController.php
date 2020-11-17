<?php

namespace App\Http\Controllers\OS\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


use App\Models\Management\Chat\TicketMessage;
use App\Models\Management\Chat\TicketThread;
use App\Models\User;


class TicketsController extends Controller
{
    public function index()
    {
        return view('OS.Tickets.index');
    }

    public function fetchThread($s,$id){

        try{
            if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local"){
                $thread = TicketThread::where('id', $id)->first();
            }else{
                $thread = TicketThread::where('id', $id)->where('account_id',app()->make('account')->id)->first();
            }

            if(count($thread) === 1){

                $thread->load('ticketmessage');

                $json = json_encode($thread);
                $array = json_decode($json);

                unset($array->account);
                foreach($array->ticketmessage as $messagekey => $messagevalue){
                    unset($array->ticketmessage[$messagekey]->ticketthread);
                }

                return json_encode($array);

            }else{
                return "notfound";
            }
        }catch (\Throwable $t) {
            app('sentry')->captureException($t);
        }

    }

    public function fetchMessage($s,$id){

        try{
            if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local"){
                $message = TicketMessage::where('id', $id)->first();
            }else{
                $message = TicketMessage::where('id', $id)->first();
            }

            if(count($message) === 1){

                $message->load('ticketthread');

                $json = json_encode($message);
                $array = json_decode($json);

                unset($array->ticketthread);

                return json_encode($array);

            }else{
                return "notfound";
            }
        }catch (\Throwable $t) {
            app('sentry')->captureException($t);
        }
    }

    public function fetchThreads()
    {
        try{
            if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local"){
                $threads = TicketThread::with('ticketmessage')->get();
            }else{
                $threads = TicketThread::where('account_id' , app()->make('account')->id)
                    ->with('ticketmessage')
                    ->get();
            }


            $json = json_encode($threads);
            $array = json_decode($json);

            foreach($array as $threadkey => $threadvalue){
                unset($array[$threadkey]->account);
                foreach($array[$threadkey]->ticketmessage as $messagekey => $messagevalue){
                    unset($array[$threadkey]->ticketmessage[$messagekey]->ticketthread);
                }
            }

            return $array;
        }catch (\Throwable $t) {
            app('sentry')->captureException($t);
            if(isset($threadkey)){
                return $threadkey;
            }else{
                return "";
            }
        }
    }

    public function sendMessage(Request $request)
    {

        if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local"){
            $user = null;
        }else{
            $user = Auth::user()->id;
        }

        $message = new TicketMessage;
        $message->message = $request->input('message');
        $message->ticket_threads_id = $request->input('thread');
        $message->user_id = $user;
        $message->save();

        $message->BroadcastMessage();

        return ['status' => 'OK'];
    }

    public function NewThread(Request $request){

        $thread =  new TicketThread;
        $thread->account_id = app()->make('account')->id;
        $thread->subject = $request->input('subject');
        $thread->status = "Open";
        $thread->save();

        $message = new TicketMessage;
        $message->message = $request->input('message');
        $message->ticket_threads_id = $thread->id;
        $message->user_id = Auth::user()->id;
        $message->save();

        $thread->BroadcastMessage();

        return ['status' => 'OK', 'thread' => $thread->id];
    }

    public function updateStatus(Request $request){

        $thread = TicketThread::where('id', $request->input('thread'))->first();

        if(count($thread) === 1){
            $thread->status = $request->input('status');
            $thread->save();

            $thread->BroadcastStatusChange();

            return ['status' => 'OK'];
        }else{
            return ['status' => 'notfound'];
        }

    }

}
