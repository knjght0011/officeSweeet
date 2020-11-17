<?php

namespace App\Http\Controllers\Management;

#use Session;
use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
#use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Auth;
use Cache;
use Config;

#use Illuminate\Support\Facades\Redirect;
#use Illuminate\Http\Request;


// [START use_task_and_queue]
// [START use_task]
#use google\appengine\api\taskqueue\PushTask;
// [END use_task]
#use google\appengine\api\taskqueue\PushQueue;
// [END use_task_and_queue]
#use Silex\Application;
#use Symfony\Component\HttpFoundation\Request;

use App\Helpers\OS\EventHelper;
use App\Helpers\AccountHelper;
use App\Models\Management\Account;

use App\Http\Controllers\Controller;
 
class TestingController extends Controller
{

    public function Blank(){

        return View::make('DevelopmentStuff.blank');
    }

    public function GetCache()
    {
        env('DB_LOG_CONNECTION' , "management");

        return var_dump(Cache::get('migration-test'));
    }

    public function GoogleQueueTest(){

        $input = Input::all();

        EventHelper::add( var_export ($input) );

        return "done";
        /*
        $account = Account::where('id', $id)->first();

        if(count($account) === 1){
            AccountHelper::Elevate();
            AccountHelper::CreateDB($this->account->database, $this->account->username, $this->account->password);
            AccountHelper::Deelevate();

            return "done";
        }else{
            return "fail";
        }
        */

    }

    public function PutJobInQueue(){


        $task = new PushTask(
            '/GoogleQueueTest',
            ['id' => '21', 'action' => 'test_queue']);
        $task_name = $task->add();

        return $task_name;
    }

    public function DeleteAccount($sub, $id){

        $account= Account::where('id', '=', $id)->first();

        if(count($account) === 1){
            $account->Remove();
            return "ok";
        }else{
            return "unknown";
        }

    }


}
